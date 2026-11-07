<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_lembur extends Model
{   
    use ModelTrait;

    protected $table    = 't_lembur';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","m_dir_id","m_kary_id","tanggal","jam_mulai","jam_selesai","tipe_lembur_id","alasan_id","no_doc","doc","keterangan","status","creator_id","last_editor_id","interval_min","pic_id","ref_table","ref_id"];

    public $columns     = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","tanggal","jam_mulai","jam_selesai","tipe_lembur_id","alasan_id","no_doc","doc","keterangan","status","creator_id","last_editor_id","created_at","updated_at","interval_min","pic_id","ref_table","ref_id"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","m_dir_id:bigint","m_kary_id:bigint","tanggal:date","jam_mulai:time","jam_selesai:time","tipe_lembur_id:bigint","alasan_id:bigint","no_doc:string:191","doc:string:191","keterangan:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","interval_min:integer","pic_id:bigint","ref_table:string:191","ref_id:bigint"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_lembur.m_comp_id","m_dir.id=t_lembur.m_dir_id","m_kary.id=t_lembur.m_kary_id","m_general.id=t_lembur.tipe_lembur_id","m_general.id=t_lembur.alasan_id","default_users.id=t_lembur.creator_id","default_users.id=t_lembur.last_editor_id","default_users.id=t_lembur.pic_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_kary_id","tanggal","jam_mulai","jam_selesai","tipe_lembur_id","alasan_id"];
    public $createable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","tanggal","jam_mulai","jam_selesai","tipe_lembur_id","alasan_id","no_doc","doc","keterangan","status","creator_id","last_editor_id","interval_min","pic_id","ref_table","ref_id"];
    public $updateable  = ["nomor","m_comp_id","m_dir_id","m_kary_id","tanggal","jam_mulai","jam_selesai","tipe_lembur_id","alasan_id","no_doc","doc","keterangan","status","creator_id","last_editor_id","interval_min","pic_id","ref_table","ref_id"];
    public $searchable  = ["id","nomor","m_comp_id","m_dir_id","m_kary_id","tanggal","jam_mulai","jam_selesai","tipe_lembur_id","alasan_id","no_doc","doc","keterangan","status","creator_id","last_editor_id","created_at","updated_at","interval_min","pic_id","ref_table","ref_id"];
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
    public function m_kary() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_kary', 'm_kary_id', 'id');
    }
    public function tipe_lembur() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_lembur_id', 'id');
    }
    public function alasan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'alasan_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
    public function pic() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'pic_id', 'id');
    }
}
