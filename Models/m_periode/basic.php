<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_periode extends Model
{   
    use ModelTrait;

    protected $table    = 'm_periode';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["comp_id","tipe","tahun","bulan","start_periode","end_periode","deskripsi","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","comp_id","tipe","tahun","bulan","start_periode","end_periode","deskripsi","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","comp_id:bigint","tipe:string:50","tahun:string:50","bulan:string:50","start_periode:date","end_periode:date","deskripsi:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_periode.comp_id","default_users.id=m_periode.creator_id","default_users.id=m_periode.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tipe","tahun","bulan","start_periode","end_periode","deskripsi","is_active"];
    public $createable  = ["comp_id","tipe","tahun","bulan","start_periode","end_periode","deskripsi","is_active","creator_id","last_editor_id"];
    public $updateable  = ["comp_id","tipe","tahun","bulan","start_periode","end_periode","deskripsi","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","comp_id","tipe","tahun","bulan","start_periode","end_periode","deskripsi","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'comp_id', 'id');
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
