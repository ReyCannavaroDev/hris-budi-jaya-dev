<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_contract extends Model
{   
    use ModelTrait;

    protected $table    = 'm_contract';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","kode","deskripsi","tipe","job","file","note","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","kode","deskripsi","tipe","job","file","note","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","kode:string:191","deskripsi:text","tipe:string:100","job:string:100","file:string:191","note:text","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_contract.m_comp_id","default_users.id=m_contract.creator_id","default_users.id=m_contract.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["deskripsi","tipe","job","file"];
    public $createable  = ["m_comp_id","kode","deskripsi","tipe","job","file","note","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","kode","deskripsi","tipe","job","file","note","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","kode","deskripsi","tipe","job","file","note","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
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
