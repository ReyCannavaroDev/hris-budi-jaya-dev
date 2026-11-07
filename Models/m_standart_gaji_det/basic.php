<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_standart_gaji_det extends Model
{   
    use ModelTrait;

    protected $table    = 'm_standart_gaji_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_standart_gaji_id","komponen","faktor","nilai","periode","creator_id","last_editor_id"];

    public $columns     = ["id","m_standart_gaji_id","komponen","faktor","nilai","periode","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_standart_gaji_id:bigint","komponen:string:191","faktor:string:191","nilai:decimal","periode:string:191","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_standart_gaji.id=m_standart_gaji_det.m_standart_gaji_id","default_users.id=m_standart_gaji_det.creator_id","default_users.id=m_standart_gaji_det.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["komponen","faktor","nilai","periode"];
    public $createable  = ["m_standart_gaji_id","komponen","faktor","nilai","periode","creator_id","last_editor_id"];
    public $updateable  = ["m_standart_gaji_id","komponen","faktor","nilai","periode","creator_id","last_editor_id"];
    public $searchable  = ["id","m_standart_gaji_id","komponen","faktor","nilai","periode","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_standart_gaji() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_standart_gaji', 'm_standart_gaji_id', 'id');
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
