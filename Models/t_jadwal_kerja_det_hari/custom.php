<?php

namespace App\Models\CustomModels;

class t_jadwal_kerja_det_hari extends \App\Models\BasicModels\t_jadwal_kerja_det_hari
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}