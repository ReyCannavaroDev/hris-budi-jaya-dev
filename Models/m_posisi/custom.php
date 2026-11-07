<?php

namespace App\Models\CustomModels;

class m_posisi extends \App\Models\BasicModels\m_posisi
{    
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore('Helper');
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
      $newArrayData  = array_merge( $arrayData,[
        'kode' =>  $this->helper->generateNomor('KODE POSISI'),
        'comp_id' => auth()->user()->comp_id ?? 0
      ] );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }

    public function custom_seeder(){
        
    }
    
}