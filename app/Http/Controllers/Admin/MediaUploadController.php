<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaUploadRequest;
use App\Models\MediaUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class MediaUploadController extends Controller
{
    public function upload_media_file(MediaUploadRequest $request)
    {
        if ($request->hasFile('file')) {

            $image = $request->file;

            $image_extenstion = $image->getClientOriginalExtension();
            $image_name_with_ext = $image->getClientOriginalName();

            $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
            $image_name = strtolower(Str::slug($image_name));

            $image_db = $image_name . time() . '.' . $image_extenstion;

            $folder_path = public_path('uploads/media-upload/');


            if (in_array($image_extenstion,['pdf','doc','docx','txt','svg','zip','csv','xlsx','xlsm','xlsb','xltx','pptx','pptm','ppt'])){
                $request->file->move($folder_path, $image_db);
                MediaUpload::create([
                    'title' => $image_name_with_ext,
                    'size' => null,
                    'path' => $image_db,
                    'type' => $request->user_type,
                    'dimensions' => null,
                    'extension' => pathinfo($image_name_with_ext,PATHINFO_EXTENSION),
                ]);
            }

            if (in_array($image_extenstion,['jpg','jpeg','png','gif'])){
                $this->handle_image_upload(
                    $image_db,
                    $image,
                    $image_name_with_ext,
                    $folder_path,
                    $request
                );
            }


        }
    }

    public function all_upload_media_file(Request $request)
    {
        $all_images = MediaUpload::orderBy('id', 'DESC')->take(20)->get();
        $selected_image = MediaUpload::find($request->selected);

        $all_image_files = [];

        if (!empty($selected_image)){
            [$image_url, $all_image_files] = $this->extracted($selected_image, $all_image_files);

        }
        foreach ($all_images as $image){
            [$image_url, $all_image_files] = $this->extracted($image, $all_image_files);
        }

        return response()->json($all_image_files);
    }

    public function delete_upload_media_file(Request $request)
    {
        $get_image_details = MediaUpload::find($request->id);
        if (is_null($get_image_details)){
             abort(400,'image not found in database');
        }
        if (file_exists(public_path('uploads/media-upload/'.$get_image_details->path))){
            unlink(public_path('uploads/media-upload/'.$get_image_details->path));
        }
        if (file_exists(public_path('uploads/media-upload/grid-'.$get_image_details->path))){
            unlink(public_path('uploads/media-upload/grid-'.$get_image_details->path));
        }
        if (file_exists(public_path('uploads/media-upload/large-'.$get_image_details->path))){
            unlink(public_path('uploads/media-upload/large-'.$get_image_details->path));
        }
        if (file_exists(public_path('uploads/media-upload/thumb-'.$get_image_details->path))){
            unlink(public_path('uploads/media-upload/thumb-'.$get_image_details->path));
        }
        $get_image_details->delete();

        return back();
    }

    public function regenerate_media_images(){
        $all_media_file = MediaUpload::all();
        foreach ($all_media_file as $img){

            if (!file_exists(public_path('uploads/media-upload/'.$img->path))){
                continue;
            }
            $image = public_path('uploads/media-upload/'. $img->path);
            $image_dimension = getimagesize($image);;
            $image_width = $image_dimension[0];
            $image_height = $image_dimension[1];

            $image_db = $img->path;
            $image_grid = 'grid-'.$image_db ;
            $image_large = 'large-'. $image_db;
            $image_thumb = 'thumb-'. $image_db;

            $folder_path = public_path('uploads/media-upload/');
            $resize_grid_image = Image::make($image)->resize(350, null,function ($constraint) {
                $constraint->aspectRatio();
            });
            $resize_large_image = Image::make($image)->resize(740, null,function ($constraint) {
                $constraint->aspectRatio();
            });
            $resize_thumb_image = Image::make($image)->resize(150, 150);

            if ($image_width > 150){
                $resize_thumb_image->save($folder_path . $image_thumb);
                $resize_grid_image->save($folder_path . $image_grid);
                $resize_large_image->save($folder_path . $image_large);
            }

        }
        return 'regenerate done';
    }

    public function alt_change_upload_media_file(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'alt' => 'nullable',
        ]);
        MediaUpload::where('id',$request->id)->update(['alt' => $request->alt]);
        return back();
    }

    public function all_upload_media_images_for_page(){
        $all_media_images = MediaUpload::orderBy('id','desc')->get();

        return view('backend.media-images.media-images')->with(['all_media_images' => $all_media_images]);
    }

    public function get_image_for_loadmore(Request $request){
        $all_images = MediaUpload::orderBy('id', 'DESC')->skip($request->skip)->take(20)->get();

        $all_image_files = [];
        foreach ($all_images as $image){
            if (file_exists(public_path('uploads/media-upload/'.$image->path))){
                $image_url = asset('uploads/media-upload/'.$image->path);
                if (file_exists(public_path('uploads/media-upload/grid-' . $image->path))) {
                    $image_url = asset('uploads/media-upload/grid-' . $image->path);
                }
                $all_image_files[] = [
                    'image_id' => $image->id,
                    'title' => $image->title,
                    'dimensions' => $image->dimensions,
                    'alt' => $image->alt,
                    'user_type' => $image->user_type,
                    'type' => pathinfo($image_url,PATHINFO_EXTENSION),
                    'size' => $image->size,
                    'path' => $image->path,
                    'img_url' => $image_url,
                    'upload_at' => date_format($image->created_at, 'd M y')
                ];

            }
        }

        return response()->json($all_image_files);
    }

    public function fetch_single_image(Request $request){

        $image_file = MediaUpload::find($request->id);

        if (!is_null($image_file) && file_exists(public_path('uploads/media-upload/'.$image_file->path))){
            $image_url = asset('uploads/media-upload/'.$image_file->path);
            if (file_exists(public_path('uploads/media-upload/grid-' . $image_file->path))) {
                $image_url = asset('uploads/media-upload/grid-' . $image_file->path);
            }
            $all_image_files = [
                'image_id' => $image_file->id,
                'title' => $image_file->title,
                'dimensions' => $image_file->dimensions,
                'alt' => $image_file->alt,
                'user_type' => $image_file->user_type,
                'type' => pathinfo($image_url,PATHINFO_EXTENSION),
                'size' => $image_file->size,
                'path' => $image_file->path,
                'img_url' => $image_url,
                'upload_at' => date_format($image_file->created_at, 'd M y')
            ];
        }
        return response()->json($all_image_files);
    }

    private function handle_image_upload(
        $image_db,
        $image,
        $image_name_with_ext,
        $folder_path,
        $request
    )
    {
        $image_dimension = getimagesize($image);
        $image_width = $image_dimension[0];
        $image_height = $image_dimension[1];
        $image_dimension_for_db = $image_width . ' x ' . $image_height . ' pixels';
        $image_size_for_db = $image->getSize();

        $image_grid = 'grid-'.$image_db ;
        $image_large = 'large-'. $image_db;
        $image_thumb = 'thumb-'. $image_db;

        $resize_grid_image = Image::make($image)->resize(350, null,function ($constraint) {
            $constraint->aspectRatio();
        });
        $resize_large_image = Image::make($image)->resize(740, null,function ($constraint) {
            $constraint->aspectRatio();
        });
        $resize_thumb_image = Image::make($image)->resize(150, 150);
        $request->file->move($folder_path, $image_db);

        MediaUpload::create([
            'title' => $image_name_with_ext,
            'size' => $this->formatBytes($image_size_for_db),
            'path' => $image_db,
            'type' => $request->user_type,
            'extension' => pathinfo($image_name_with_ext,PATHINFO_EXTENSION),
            'dimensions' => $image_dimension_for_db
        ]);

        if ($image_width > 150){
            $resize_thumb_image->save($folder_path . $image_thumb);
            $resize_grid_image->save($folder_path . $image_grid);
            $resize_large_image->save($folder_path . $image_large);
        }
    }

    private function formatBytes($image_size_for_db)
    {
            $precision = 2;
            $base = log($image_size_for_db, 1024);
            $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

            return round(1024 ** ($base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
     * @param $selected_image
     * @param array $all_image_files
     * @return array
     */
    private function extracted($selected_image, array $all_image_files): array
    {
        $image_url = '';
        if (file_exists(public_path('uploads/media-upload/' . $selected_image->path))) {

            $image_url = asset('uploads/media-upload/' . $selected_image->path);
            if (file_exists(public_path('uploads/media-upload/grid-' . $selected_image->path))) {
                $image_url = asset('uploads/media-upload/grid-' . $selected_image->path);
            }

            $all_image_files[] = [
                'image_id' => $selected_image->id,
                'title' => $selected_image->title,
                'dimensions' => $selected_image->dimensions,
                'alt' => $selected_image->alt,
                'size' => $selected_image->size,
                'type' => pathinfo($image_url, PATHINFO_EXTENSION),
                'path' => $selected_image->path,
                'img_url' => $image_url,
                'upload_at' => date_format($selected_image->created_at, 'd M y')
            ];
        }
        return array($image_url, $all_image_files);
    }
}
