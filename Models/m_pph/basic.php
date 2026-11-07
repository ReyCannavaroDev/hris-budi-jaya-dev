<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_pph extends Model
{   
    use ModelTrait;

    protected $table    = 'm_pph';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["comp_id","tgl_pengaturan","dependant_amt","cost_level","metode_penentuan","note","besaran_nikah_pria","besaran_nikah_wanita","besaran_single_pria","besaran_single_wanita","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","comp_id","tgl_pengaturan","dependant_amt","cost_level","metode_penentuan","note","besaran_nikah_pria","besaran_nikah_wanita","besaran_single_pria","besaran_single_wanita","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","comp_id:bigint","tgl_pengaturan:date","dependant_amt:decimal","cost_level:decimal","metode_penentuan:string:191","note:string:191","besaran_nikah_pria:decimal","besaran_nikah_wanita:decimal","besaran_single_pria:decimal","besaran_single_wanita:decimal","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["default_users.id=m_pph.creator_id","default_users.id=m_pph.last_editor_id"];
    public $details     = ["m_pph_det"];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tgl_pengaturan","dependant_amt","metode_penentuan","besaran_nikah_pria","besaran_nikah_wanita","besaran_single_pria","besaran_single_wanita","is_active"];
    public $createable  = ["comp_id","tgl_pengaturan","dependant_amt","cost_level","metode_penentuan","note","besaran_nikah_pria","besaran_nikah_wanita","besaran_single_pria","besaran_single_wanita","is_active","creator_id","last_editor_id"];
    public $updateable  = ["comp_id","tgl_pengaturan","dependant_amt","cost_level","metode_penentuan","note","besaran_nikah_pria","besaran_nikah_wanita","besaran_single_pria","besaran_single_wanita","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","comp_id","tgl_pengaturan","dependant_amt","cost_level","metode_penentuan","note","besaran_nikah_pria","besaran_nikah_wanita","besaran_single_pria","besaran_single_wanita","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function m_pph_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\m_pph_det', 'm_pph_id', 'id');
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
