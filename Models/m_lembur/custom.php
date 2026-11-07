<?php

namespace App\Models\CustomModels;

class m_lembur extends \App\Models\BasicModels\m_lembur
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
      $newArrayData  = array_merge( $arrayData,[
        'comp_id' => auth()->user()->comp_id ?? 0,
        'grading' => @m_general::find($arrayData['grading_id'])->value ?? ''
      ] );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }
    

    
}