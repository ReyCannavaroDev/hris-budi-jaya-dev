<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_final_gaji_det_rincian extends Model
{   
    use ModelTrait;

    protected $table    = 't_final_gaji_det_rincian';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_final_gaji_det_id","seq","name","label","type","factor","value_ref","value","can_adjust","detail","deskripsi","status","creator_id","last_editor_id","t_potongan_id","t_cuti_id"];

    public $columns     = ["id","t_final_gaji_det_id","seq","name","label","type","factor","value_ref","value","can_adjust","detail","deskripsi","status","creator_id","last_editor_id","created_at","updated_at","t_potongan_id","t_cuti_id"];
    public $columnsFull = ["id:bigint","t_final_gaji_det_id:bigint","seq:decimal","name:string:191","label:text","type:string:191","factor:string:191","value_ref:decimal","value:decimal","can_adjust:boolean","detail:json","deskripsi:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","t_potongan_id:bigint","t_cuti_id:bigint"];
    public $rules       = [];
    public $joins       = ["t_final_gaji_det.id=t_final_gaji_det_rincian.t_final_gaji_det_id","default_users.id=t_final_gaji_det_rincian.creator_id","default_users.id=t_final_gaji_det_rincian.last_editor_id","t_potongan.id=t_final_gaji_det_rincian.t_potongan_id","t_cuti.id=t_final_gaji_det_rincian.t_cuti_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["seq","label","type","factor","value"];
    public $createable  = ["t_final_gaji_det_id","seq","name","label","type","factor","value_ref","value","can_adjust","detail","deskripsi","status","creator_id","last_editor_id","t_potongan_id","t_cuti_id"];
    public $updateable  = ["t_final_gaji_det_id","seq","name","label","type","factor","value_ref","value","can_adjust","detail","deskripsi","status","creator_id","last_editor_id","t_potongan_id","t_cuti_id"];
    public $searchable  = ["id","t_final_gaji_det_id","seq","name","label","type","factor","value_ref","value","can_adjust","detail","deskripsi","status","creator_id","last_editor_id","created_at","updated_at","t_potongan_id","t_cuti_id"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_final_gaji_det() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_final_gaji_det', 't_final_gaji_det_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
    public function t_potongan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_potongan', 't_potongan_id', 'id');
    }
    public function t_cuti() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_cuti', 't_cuti_id', 'id');
    }
}
