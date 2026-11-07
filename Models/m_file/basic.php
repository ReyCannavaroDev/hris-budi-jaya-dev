<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_file extends Model
{   
    use ModelTrait;

    protected $table    = 'm_file';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_dir_id","name","type","filename","tags","note","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_dir_id","name","type","filename","tags","note","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_dir_id:bigint","name:string:191","type:string:191","filename:string:191","tags:string:191","note:string:191","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_dir.id=m_file.m_dir_id","default_users.id=m_file.creator_id","default_users.id=m_file.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [
    "name"=> "unique:m_file,name"
	];
    public $required    = ["is_active"];
    public $createable  = ["m_dir_id","name","type","filename","tags","note","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_dir_id","name","type","filename","tags","note","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_dir_id","name","type","filename","tags","note","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
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
