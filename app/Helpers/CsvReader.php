<?php

namespace App\Helpers;

class CsvReader
{
    protected $file;
    protected $file_path;
    public function __construct($file){
        $this->file = fopen($file,'r');
        $this->file_path = $file;
    }
    public function rows(){
        while(!feof($this->file)){
            $row = fgetcsv($this->file);
            yield $row;
        }
        return;
    }
    public function head(){
        $first_row = [];
        foreach ($this->rows() as $key => $row){
            if ($key === 0){
                $first_row[] = $row;
                break;
            }
        }
        return $first_row;
    }
}
