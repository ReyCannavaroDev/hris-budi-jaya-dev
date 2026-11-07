<?php

namespace App\Models\CustomModels;

class m_kary_det_kartu extends \App\Models\BasicModels\m_kary_det_kartu
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = ['ktp_foto', 'kk_foto', 'npwp_foto', 'bpjs_foto', 'berkas_lain', 'pas_foto'];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}