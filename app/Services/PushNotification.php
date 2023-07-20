<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PushNotification
{
    protected $instancne = null;
    protected $data = [];
    protected  $server_key = null;
    private int $topicID;

    public static function init(){
        if (!is_null((new self())->instancne)){
            return (new self())->instancne;
        }
        return new self();
    }
    public function send(){
       $ee =  Http::withHeaders([
            'Content-Type'=> 'application/json',
            "Authorization" => "key=" . $this->getServerKey()
        ])->post("https://fcm.googleapis.com/fcm/send",
            [
            "message" => [
                "body" =>"subject",
                "title" => "title"
            ],
            "priority" => "high",
            "data" => $this->getData(),
            "to" => "/topics/".$this->getTopicId()
        ]);
    }
    public function setServerKey(){
        $this->server_key = "AAAAOZyYxu4:APA91bFPVf5BgHcyunweP7GwBi9wvlJcozVFxG8N8Kz_YSLDWTNt1eBlf0gF09cl9FmEPGEIhp04l-7uvRG0tozpowfeaHX7HFG0cpcItGjei63dm5t1IpD7cF6BpDWsF0TFQti-x1qF";
        return $this;
    }
    public function getServerKey(){
        return $this->server_key;
    }

    public function setTopicId(int $id){
        $this->topicID = $id;
        return $this;
    }
    public function getTopicId(){
        return $this->topicID;
    }
    public function getData(){
        return $this->data;
    }
    public function setData(array $data){
       $this->data = [
            "title" => $data["title"], //title
            "id" => $data["id"], //database user id
            "body" => $data["body"], // message
            "sound" => "default",
            "screen" => ""
        ];
        return $this;
    }
}
