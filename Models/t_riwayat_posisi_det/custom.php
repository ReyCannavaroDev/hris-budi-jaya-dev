<?php

namespace App\Models\CustomModels;

class t_riwayat_posisi_det extends \App\Models\BasicModels\t_riwayat_posisi_det
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}