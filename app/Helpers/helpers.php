<?php
use App\Models\StaticOptions as StaticOption;


function set_static_option($key, $value)
{
    if (!StaticOption::where('option_name', $key)->first()) {
        StaticOption::create([
            'option_name' => $key,
            'option_value' => $value
        ]);
        return true;
    }
    return false;
}

function get_static_option($key,$default = null)
{
    global $option_name;
    $option_name = $key;
    $value = \Illuminate\Support\Facades\Cache::remember($option_name,6400, function () {
        global $option_name;
        return StaticOption::where('option_name', $option_name)->first();
    });

    return $value->option_value ?? $default;
}

function update_static_option($key, $value)
{
    $static_option = null;
    if ($static_option === null){
        $static_option = StaticOption::query();
    }
    $static_option->updateOrCreate(['option_name' => $key],[
        'option_name' => $key,
        'option_value' => $value
    ]);
    \Illuminate\Support\Facades\Cache::forget($key);
    return true;
}

function delete_static_option($key)
{
    return StaticOption::where('option_name', $key)->delete();
}
