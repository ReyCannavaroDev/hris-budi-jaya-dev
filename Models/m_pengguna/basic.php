<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_pengguna extends Model
{   
    use ModelTrait;

    protected $table    = 'm_pengguna';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_dir_id","kode","default_user_id","m_kary_id","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_dir_id","kode","default_user_id","m_kary_id","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_dir_id:bigint","kode:string:191","default_user_id:bigint","m_kary_id:bigint","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_dir.id=m_pengguna.m_dir_id","default_users.id=m_pengguna.default_user_id","m_karyawan.id=m_pengguna.m_kary_id","default_users.id=m_pengguna.creator_id","default_users.id=m_pengguna.last_editor_id"];
    public $details     = [];
    public $heirs       = ["m_pengesahan_doc"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["is_active"];
    public $createable  = ["m_dir_id","kode","default_user_id","m_kary_id","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_dir_id","kode","default_user_id","m_kary_id","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_dir_id","kode","default_user_id","m_kary_id","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function default_user() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'default_user_id', 'id');
    }
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_karyawan', 'm_kary_id', 'id');
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
