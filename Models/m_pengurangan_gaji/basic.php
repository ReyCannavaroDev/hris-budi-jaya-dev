<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_pengurangan_gaji extends Model
{   
    use ModelTrait;

    protected $table    = 'm_pengurangan_gaji';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["comp_id","tipe","k_minimun","k_maksimum","berdasarkan","n_pengurangan","periode","referensi","variabel","deskripsi","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","comp_id","tipe","k_minimun","k_maksimum","berdasarkan","n_pengurangan","periode","referensi","variabel","deskripsi","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","comp_id:bigint","tipe:string:100","k_minimun:decimal","k_maksimum:decimal","berdasarkan:string:100","n_pengurangan:decimal","periode:string:100","referensi:string:100","variabel:string:100","deskripsi:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_pengurangan_gaji.comp_id","default_users.id=m_pengurangan_gaji.creator_id","default_users.id=m_pengurangan_gaji.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tipe","berdasarkan","n_pengurangan","periode","referensi","variabel","is_active"];
    public $createable  = ["comp_id","tipe","k_minimun","k_maksimum","berdasarkan","n_pengurangan","periode","referensi","variabel","deskripsi","is_active","creator_id","last_editor_id"];
    public $updateable  = ["comp_id","tipe","k_minimun","k_maksimum","berdasarkan","n_pengurangan","periode","referensi","variabel","deskripsi","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","comp_id","tipe","k_minimun","k_maksimum","berdasarkan","n_pengurangan","periode","referensi","variabel","deskripsi","is_active","creator_id","last_editor_id","created_at","updated_at"];
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
