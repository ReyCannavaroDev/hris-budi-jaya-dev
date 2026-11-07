<?php

namespace App\Models\CustomModels;

class m_jam_kerja extends \App\Models\BasicModels\m_jam_kerja
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
        // $key_check = m_general::where('key','OFFICE')->pluck('id')->first();
        // $officeCek = $model->where('tipe_jam_kerja_id', $key_check);
      
        // if($officeCek->exists())
        //     return [
        //         "errors" => ['Maaf tipe jam kerja Office sudah tersedia']
        //     ];
        return [
            "model"  => $model,
            "data"   => array_merge($arrayData,[
                'kode' =>  $this->helper->generateNomor('KODE JAM KERJA')
            ])
        ];
    }
    
}