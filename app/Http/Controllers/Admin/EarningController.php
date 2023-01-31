<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CsvReader;
use App\Http\Controllers\Controller;
use App\Http\Requests\EarningValidationRequest;
use App\Models\Earning;
use App\Models\Employee;
use App\Models\MediaUpload;
use App\Models\Product;
use App\Models\StaticOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EarningController extends Controller
{

    public function index(){
        $statements = Earning::orderBy('id','desc')->paginate(10);
        return Inertia::render('Backend/Earnings/Statements',[
            'statements' =>  $statements
        ]);
    }

    public function settings_page(){
        $employee = Employee::where('status',1)->get()->map(function ($item){
            return ['value' => $item->id,'label' => $item->name];
        });
        $settings = StaticOptions::whereIn('option_name',[
            'calculate_expect_employee'
        ])->get()->map(function ($item){
            return [ $item->option_name =>  $item->option_value];
        });
        return Inertia::render('Backend/Earnings/Settings',[
            'employees' => $employee,
            'settings' => $settings
        ]);
    }
    public function update_settings(Request $request){
        $this->validate($request,[
           'calculate_expect_employee' => 'nullable|numeric'
        ]);
        $fields = ['calculate_expect_employee'];
        foreach ($fields as $field){
            update_static_option($field,$request->$field);
        }
        return back();
    }

    public function new(){
        return Inertia::render('Backend/Earnings/New');
    }

    public function store(EarningValidationRequest $request){

        $last_inserted_item = Earning::create($request->validated());
        return back()->with(['flashMsg' => ['msg' => 'Save Success','type' => 'success']]);
    }

    public function update(EarningValidationRequest $request){
        $last_inserted_item = Earning::find($request->id);
        $last_inserted_item->update($request->validated());
        return back()->with(['flashMsg' => ['msg' => 'Update Success','type' => 'success']]);
    }

    public function delete(Request $request){
        Earning::find($request->id)->delete();
        return back();
    }

    private function updateStatementData(string $db_file_name,$percentage)
    {
        $getCsvFilePath = MediaUpload::find($db_file_name);

//        dd($getCsvFilePath);
        $csv_data = new CsvReader(public_path('uploads/media-upload/'.$getCsvFilePath->path));
        $filedata = [];
        $first_row = [];

        $sale_reverse_items = [];
        $sale_items = [];
        foreach ($csv_data->rows() as $key => $row){

            if ($key === 0){
                $first_row[] = $row;
                continue;
            }

            if ($key !== 0 && is_array($row) && count($row) > 1){

                $item_id_index = array_search("Item ID", current($first_row)); // "Item ID"
                $item_details_index = array_search("Detail", current($first_row)); // "Item ID"
                $sale_type_index = array_search("Type",  current($first_row));
                $item_order_id_index = array_search("Order ID",  current($first_row));
                $sale_type = $row[$sale_type_index];
                $amount_index = array_search("Amount",  current($first_row));//Amount
//                $sale_type = ''; //Sale,Author Fee,Author Fee Reversal,Sale Reversal

                if ($sale_type === 'Sale'){
                    $sale_items = $this->getSale_items($sale_items, $row[$item_id_index], $row[$amount_index], $sale_items,$row[$item_details_index],$row[$item_order_id_index]);
                }elseif($sale_type === 'Sale Reversal'){
                    $sale_reverse_items = $this->getSale_items($sale_reverse_items, $item_id_index, $row[$amount_index], $sale_reverse_items,$row[$item_details_index],$row[$item_order_id_index]);
                }
            }



        }

        $number_of_sales =  array_sum(array_column($sale_items,'sales'));
        $number_of_sale_reversal =  array_sum(array_column($sale_reverse_items,'sales'));

        $total_sales = $number_of_sales - $number_of_sale_reversal;


        $total_amount_of_sales = array_sum(array_column($sale_items,'amount'));
        $total_amount_of_sales_reversal = array_sum(array_column($sale_reverse_items,'amount'));

//        dd($total_amount_of_sales,$total_amount_of_sales_reversal,$final_amount_of_sales);
        $final_amount_of_sales_personal =  $this->get_final_amount($total_amount_of_sales,$total_amount_of_sales_reversal,$percentage);
        $final_amount_of_sales_in_total = $final_amount_of_sales_personal;
       // get all except selected employee
        //todo: get employe id and get all item id attached to this employee
        $exclude_employee_id = get_static_option('calculate_expect_employee');

       $exclude_envato_item_id_list = Product::where('developer',$exclude_employee_id)->get()->pluck('enItemId');
       $excluded_item_array = [];
       foreach ($exclude_envato_item_id_list as $list){
            if (array_key_exists($list,$sale_items)){
                unset($sale_items[$list]);
            }
       }

        $total_amount_of_sales = array_sum(array_column($sale_items,'amount'));
        $total_amount_of_sales_reversal = array_sum(array_column($sale_reverse_items,'amount'));

        $final_amount_of_sales_office =  $this->get_final_amount($total_amount_of_sales,$total_amount_of_sales_reversal,$percentage);
        $final_amount_of_sales_personal -= $final_amount_of_sales_office;

        return [
            'office_earning' => $final_amount_of_sales_office,
            'personal_earning' => $final_amount_of_sales_personal,
            'total_earning' => $final_amount_of_sales_in_total,
        ];

    }

    public function calculate($id){
        $statement = Earning::find($id);
        if (is_null($statement->statement)){
            return back();
        }
        $data =   $this->updateStatementData($statement->statement,$statement->percentage);
        return response()->json($data);
    }

    /**
     * @param array $sale_reverse_items
     * @param $item_id_index
     * @param $row
     * @param array $sale_items
     * @return array
     */
    private function getSale_items(array $sale_reverse_items, $item_id_index, $row, array $sale_items,$item_details,$order_id): array
    {
        if (!isset($sale_reverse_items[$item_id_index])) {
            $sale_items[$item_id_index] = [
                'sales' => Str::contains($item_details,"License") ? 1 : 0,
                'amount' => $row,
                'details' => $item_details,
                'Order ID' => $order_id,
            ];
        } else {
                $sale_items[$item_id_index]['amount']  += $row;
            if (Str::contains($item_details,"License")){
                $sale_items[$item_id_index]['sales']++;
            }
        }
        return $sale_items;
    }

    private function get_final_amount($total_amount_of_sales,$total_amount_of_sales_reversal,$percentage)
    {
        $final_amount_of_sales = $total_amount_of_sales + $total_amount_of_sales_reversal; // plus mean sale number is negative that's why calculating it as plug
        $envato_charge = $final_amount_of_sales / 100 * $percentage ;
        $final_amount_of_sales -= $envato_charge;
        return $final_amount_of_sales;
    }
}
