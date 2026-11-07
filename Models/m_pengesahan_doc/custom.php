<?php

namespace App\Models\CustomModels;

class m_pengesahan_doc extends \App\Models\BasicModels\m_pengesahan_doc
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    // public $fileColumns    = [ 'dokumen' ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
      $newArrayData  = array_merge( $arrayData,[
        'comp_id' => auth()->user()->comp_id ?? 0
      ] );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }
    
}