<?php

namespace App\Models\CustomModels;

class m_file extends \App\Models\BasicModels\m_file
{    
    public function __construct()
    {
        parent::__construct();
    }
    
     public $fileColumns    = ['filename'];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
      $newArrayData  = array_merge( $arrayData,[
        'm_dir_id' => auth()->user()->m_dir_id ?? 0
      ] );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }
    
}