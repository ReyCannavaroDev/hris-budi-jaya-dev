<?php

namespace App\Models\CustomModels;

class t_pelamar_det_pend extends \App\Models\BasicModels\t_pelamar_det_pend
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = ['ijazah_foto'];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}