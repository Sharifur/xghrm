<?php

namespace App\Helpers;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Rats\Zkteco\Lib\ZKTeco;

class ZktecoHelper
{
    private static $instance;
    private $connection;
    private $attendnace;
    private $users;

    public static function init(){
        if (is_null(self::$instance)){
            return (new self());
        }
        return self::$instance;
    }

    private function connection(){
        if (!is_null($this->connection)){
            return $this->connection;
        }
        $con = new ZKTeco('192.168.0.100','4370');
        $con->connect();
        return  $this->connection =$con;
    }

    public function connectDevice(){
        $this->connection();
        return $this;
    }
    public function getData(){
        set_time_limit(-1);
        if (is_null($this->attendnace)){
            $this->attendnace = Cache::remember("zktech_attendance",Carbon::now()->addHours(1),function (){
                $col = collect(array_reverse($this->connection()->getAttendance()));
                return $col->filter(function ($item){
                    if (Carbon::parse($item['timestamp'])->gt(Carbon::today()->subDays(30))){
                        return $item;
                    }
                });
            });
        }
        return $this->attendnace;
    }
    public function users(){
        if (is_null($this->users)){
            return $this->users = Cache::remember("zktech_users",Carbon::now()->addHours(1),function (){
                return collect($this->connection()->getUser());
            });
        }
        return $this->users;
    }

    public function getType($type){
        return match ($type){
          0 => 'C/In',
          1 => 'C/Out',
        };
    }

    //https://github.com/raihanafroz/zkteco
    //test ZKTECO
//    $zk = new ZKTeco('192.168.0.100'); //192.168.0.100
//    $zk->connect();
//    $deviceName = $zk->deviceName();
//    //  $zk->getTime();
//    // $zk->setUser();
////    $zk->removeUser();
//    /**
//    "uid" => 1
//    "id" => "1"
//    "state" => 1
//    "timestamp" => "2022-11-22 12:58:04"
//    "type" => 0
//     * */
//    dd($zk->getAttendance());
//    $attendance =  collect($zk->getAttendance());
//    dd($attendance->where(''));
}
