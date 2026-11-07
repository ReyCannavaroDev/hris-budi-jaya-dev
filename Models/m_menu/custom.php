<?php

namespace App\Models\CustomModels;
use Illuminate\Database\Eloquent\Builder;

class m_menu extends \App\Models\BasicModels\m_menu
{       
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

   
}