<?php

namespace App\Models\CustomModels;

class t_final_gaji_det_rincian extends \App\Models\BasicModels\t_final_gaji_det_rincian
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $newArrayData  = array_merge( $arrayData,[
            'name' =>$arrayData['name'] ?? $arrayData['label'],
            'detail' => @$arrayData['detail'] ? json_encode($arrayData['detail']) : ''
        ]);
       
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }
}