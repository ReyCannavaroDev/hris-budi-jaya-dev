<?php

namespace App\Models\CustomModels;

class m_standart_gaji extends \App\Models\BasicModels\m_standart_gaji
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
        // $data = m_posisi::where('id', $arrayData['m_posisi_id'])->first();
        // if($arrayData['gaji_pokok'] < $data['min_gaji_pokok'] && $arrayData['gaji_pokok'] > $data['max_gaji_pokok'] ){
        //     return response()->json(['errors' => 'Data Gaji Pokok tidak boleh kurang dari '.$data['min_gaji_pokok'].' dan tidak boleh lebih dari '.$data['max_gaji_pokok'].'']);
        // }

        return [
            "model"  => $model,
            "data"   => array_merge($arrayData,[
                'kode' =>  app()->request->kode ?? $this->helper->generateNomor('KODE STANDART GAJI'),
                //'m_dir_id' =>  $m_dir_id
            ])
        ];
    }
    
}