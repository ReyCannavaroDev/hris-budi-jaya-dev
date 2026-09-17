<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary_det_pemb extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kary_det_pemb';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_kary_id","m_comp_id","m_dir_id","periode_gaji_id","metode_id","tipe_id","bank_id","no_rek","atas_nama_rek","desc","creator_id","last_editor_id"];

    public $columns     = ["id","m_kary_id","m_comp_id","m_dir_id","periode_gaji_id","metode_id","tipe_id","bank_id","no_rek","atas_nama_rek","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_kary_id:bigint","m_comp_id:bigint","m_dir_id:bigint","periode_gaji_id:bigint","metode_id:bigint","tipe_id:bigint","bank_id:bigint","no_rek:string:50","atas_nama_rek:string:191","desc:text","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_kary.id=m_kary_det_pemb.m_kary_id","m_comp.id=m_kary_det_pemb.m_comp_id","m_dir.id=m_kary_det_pemb.m_dir_id","m_general.id=m_kary_det_pemb.periode_gaji_id","m_general.id=m_kary_det_pemb.metode_id","m_general.id=m_kary_det_pemb.tipe_id","m_general.id=m_kary_det_pemb.bank_id","default_users.id=m_kary_det_pemb.creator_id","default_users.id=m_kary_det_pemb.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["periode_gaji_id"];
    public $createable  = ["m_kary_id","m_comp_id","m_dir_id","periode_gaji_id","metode_id","tipe_id","bank_id","no_rek","atas_nama_rek","desc","creator_id","last_editor_id"];
    public $updateable  = ["m_kary_id","m_comp_id","m_dir_id","periode_gaji_id","metode_id","tipe_id","bank_id","no_rek","atas_nama_rek","desc","creator_id","last_editor_id"];
    public $searchable  = ["id","m_kary_id","m_comp_id","m_dir_id","periode_gaji_id","metode_id","tipe_id","bank_id","no_rek","atas_nama_rek","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
    }
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function m_dir() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_dir', 'm_dir_id', 'id');
    }
    public function periode_gaji() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'periode_gaji_id', 'id');
    }
    public function metode() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'metode_id', 'id');
    }
    public function tipe() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_id', 'id');
    }
    public function bank() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'bank_id', 'id');
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
