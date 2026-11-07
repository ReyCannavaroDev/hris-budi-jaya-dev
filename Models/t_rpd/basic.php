<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_rpd extends Model
{   
    use ModelTrait;

    protected $table    = 't_rpd';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","t_spd_id","total_biaya_spd","total_biaya_selisih","pengambilan_spd","keterangan","status","creator_id","last_editor_id"];

    public $columns     = ["id","nomor","m_comp_id","t_spd_id","total_biaya_spd","total_biaya_selisih","pengambilan_spd","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","t_spd_id:bigint","total_biaya_spd:decimal","total_biaya_selisih:decimal","pengambilan_spd:decimal","keterangan:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_rpd.m_comp_id","t_spd.id=t_rpd.t_spd_id","default_users.id=t_rpd.creator_id","default_users.id=t_rpd.last_editor_id"];
    public $details     = ["t_rpd_det"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["nomor","m_comp_id","t_spd_id","total_biaya_spd","total_biaya_selisih","pengambilan_spd","keterangan","status","creator_id","last_editor_id"];
    public $updateable  = ["nomor","m_comp_id","t_spd_id","total_biaya_spd","total_biaya_selisih","pengambilan_spd","keterangan","status","creator_id","last_editor_id"];
    public $searchable  = ["id","nomor","m_comp_id","t_spd_id","total_biaya_spd","total_biaya_selisih","pengambilan_spd","keterangan","status","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_rpd_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_rpd_det', 't_rpd_id', 'id');
    }
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function t_spd() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_spd', 't_spd_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
}
