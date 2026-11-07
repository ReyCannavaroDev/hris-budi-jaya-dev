<?php

namespace App\Models\CustomModels;

class m_tarif_group extends \App\Models\BasicModels\m_tarif_group
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function scopeWithDetail(){
        return $this->with('m_tarif');
    }

     public function custom_detail(){
        $request = app()->request;
        $id = $request('id');
        $detailtarif = m_tarif_group::where('id',$id)->with('m_tarif')->get();
        $result = [
            'message' => 'success',
            'error' => 'false',
            'detail' => $detailtarif,
        ];
        return response($result,200);
    }
}