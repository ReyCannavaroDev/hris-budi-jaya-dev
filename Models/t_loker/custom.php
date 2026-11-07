<?php

namespace App\Models\CustomModels;

class t_loker extends \App\Models\BasicModels\t_loker
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
        $newArrayData  = array_merge( $arrayData,[
            'nomor' => $this->helper->generateNomor('KODE LOWONGAN PEKERJAAN')
        ]);
       
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function public_lowongan($req)
    {
        $paginate = $req->paginate ?? 3;
        return t_loker::with(['m_comp', 'm_dir', 'm_dept', 'jenis_loker', 'prioritas'])->paginate($paginate);
    }

    public function public_detLowongan($req)
    {
        $id = $req->id;

        $data = t_loker::with(['m_comp', 'm_dir', 'm_dept', 'jenis_loker', 'prioritas'])->where('id', $id)->first();

        if(!$data){
            return response()->json(['errors' => 'Data Tidak ada']);
        }

        return $data;
    }
    
}