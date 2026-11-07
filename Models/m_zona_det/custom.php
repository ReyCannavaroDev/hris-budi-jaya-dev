<?php

namespace App\Models\CustomModels;

class m_zona_det extends \App\Models\BasicModels\m_zona_det
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

     public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        return [
            "model"  => $model,
            "data"   => array_merge($arrayData,[
                //'m_dir_id' =>  $m_dir_id
            ])
        ];
    }
}