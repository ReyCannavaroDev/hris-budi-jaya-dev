<?php

namespace App\Models\CustomModels;

class generate_num_type extends \App\Models\BasicModels\generate_num_type
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];    
}