<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_hasil_tes extends Model
{   
    use ModelTrait;

    protected $table    = 't_hasil_tes';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_dir_id","t_pelamar_id","tanggal","jenis_tes","nilai_tes","deskripsi","status","creator_id","last_editor_id"];

    public $columns     = ["id","nomor","m_comp_id","m_dir_id","t_pelamar_id","tanggal","jenis_tes","nilai_tes","deskripsi","status","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_dir_id:bigint","t_pelamar_id:bigint","tanggal:date","jenis_tes:string:191","nilai_tes:decimal","deskripsi:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_hasil_tes.m_comp_id","m_dir.id=t_hasil_tes.m_dir_id","t_pelamar.id=t_hasil_tes.t_pelamar_id","default_users.id=t_hasil_tes.creator_id","default_users.id=t_hasil_tes.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["t_pelamar_id","tanggal","jenis_tes","nilai_tes","deskripsi"];
    public $createable  = ["nomor","m_comp_id","m_dir_id","t_pelamar_id","tanggal","jenis_tes","nilai_tes","deskripsi","status","creator_id","last_editor_id"];
    public $updateable  = ["nomor","m_comp_id","m_dir_id","t_pelamar_id","tanggal","jenis_tes","nilai_tes","deskripsi","status","creator_id","last_editor_id"];
    public $searchable  = ["id","nomor","m_comp_id","m_dir_id","t_pelamar_id","tanggal","jenis_tes","nilai_tes","deskripsi","status","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function t_pelamar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pelamar', 't_pelamar_id', 'id');
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
