<?php

namespace App\Models\CustomModels;

class m_libur_nasional extends \App\Models\BasicModels\m_libur_nasional
{    
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore('Helper');
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        return [
            "model"  => $model,
            "data"   => array_merge($arrayData,[
                'kode' =>  $this->helper->generateNomor('KODE LIBUR NASIONAL'),
                //'m_dir_id' =>  $m_dir_id
            ])
        ];
    }
}