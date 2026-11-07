<?php

namespace App\Models\CustomModels;

class m_comp extends \App\Models\BasicModels\m_comp
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    // public function custom_dashboard(){
    //     $data = \DB::select('select * from public.dashboard()');
    //     $parse = json_decode($data[0]->dashboard);
    //     return response()->json(['message'=> 'success', 'data' => $parse],200);
    // }
}