<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pelamar_det_peng extends Model
{   
    use ModelTrait;

    protected $table    = 't_pelamar_det_peng';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_pelamar_id","nama_pengalaman","posisi","date_from","date_to","kota_id","keterangan","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","t_pelamar_id","nama_pengalaman","posisi","date_from","date_to","kota_id","keterangan","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_pelamar_id:bigint","nama_pengalaman:string:191","posisi:string:191","date_from:date","date_to:date","kota_id:bigint","keterangan:text","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_pelamar.id=t_pelamar_det_peng.t_pelamar_id","m_general.id=t_pelamar_det_peng.kota_id","default_users.id=t_pelamar_det_peng.creator_id","default_users.id=t_pelamar_det_peng.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nama_pengalaman","posisi","date_from","date_to","kota_id","is_active"];
    public $createable  = ["t_pelamar_id","nama_pengalaman","posisi","date_from","date_to","kota_id","keterangan","is_active","creator_id","last_editor_id"];
    public $updateable  = ["t_pelamar_id","nama_pengalaman","posisi","date_from","date_to","kota_id","keterangan","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","t_pelamar_id","nama_pengalaman","posisi","date_from","date_to","kota_id","keterangan","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_pelamar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pelamar', 't_pelamar_id', 'id');
    }
    public function kota() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'kota_id', 'id');
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
