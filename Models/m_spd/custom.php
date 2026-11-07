<?php

namespace App\Models\CustomModels;

class m_spd extends \App\Models\BasicModels\m_spd
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
        return [
            "model"  => $model,
            "data"   => array_merge($arrayData,[
                'kode' =>  $this->helper->generateNomor('KODE SURAT PERJALANAN DINAS'),
                //'m_dir_id' =>  $m_dir_id
            ])
        ];
    }
    
    
}