<?php

namespace App\Models\CustomModels;

class m_kary_group extends \App\Models\BasicModels\m_kary_group
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function transformRowData( array $row )
    {
        $data = [];
        $req = app()->request;
        if($req->pic){
            $pj_id = m_kary_group_det::where('m_kary_group_id', $row['id'])->where('is_pj', true)->pluck('m_kary_id')->first() ?? 0;
            $m_kary = m_kary::find($pj_id);
            $data['pic'] = @$m_kary->nama_lengkap;
            $data['pic_id'] = $pj_id;
        }
        return array_merge( $row, $data );
    }
    
}