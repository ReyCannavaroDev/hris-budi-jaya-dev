<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_blacklist extends Model
{   
    use ModelTrait;

    protected $table    = 'm_blacklist';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["no_ktp","nama","alamat","telp","tempat_lahir","tgl_lahir","keterangan","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","no_ktp","nama","alamat","telp","tempat_lahir","tgl_lahir","keterangan","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","no_ktp:string:191","nama:string:191","alamat:string:191","telp:string:191","tempat_lahir:string:191","tgl_lahir:date","keterangan:string:191","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["default_users.id=m_blacklist.creator_id","default_users.id=m_blacklist.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["no_ktp","nama","is_active"];
    public $createable  = ["no_ktp","nama","alamat","telp","tempat_lahir","tgl_lahir","keterangan","is_active","creator_id","last_editor_id"];
    public $updateable  = ["no_ktp","nama","alamat","telp","tempat_lahir","tgl_lahir","keterangan","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","no_ktp","nama","alamat","telp","tempat_lahir","tgl_lahir","keterangan","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
}
