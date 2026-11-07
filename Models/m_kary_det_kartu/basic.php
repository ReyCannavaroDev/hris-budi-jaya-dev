<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary_det_kartu extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kary_det_kartu';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_kary_id","m_comp_id","m_dir_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id","bpjs_no_kesehatan","bpjs_no_ketenagakerjaan"];

    public $columns     = ["id","m_kary_id","m_comp_id","m_dir_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id","created_at","updated_at","bpjs_no_kesehatan","bpjs_no_ketenagakerjaan"];
    public $columnsFull = ["id:bigint","m_kary_id:bigint","m_comp_id:bigint","m_dir_id:bigint","ktp_no:string:25","ktp_foto:string:191","pas_foto:string:191","kk_no:string:25","kk_foto:string:191","npwp_no:string:25","npwp_foto:string:191","npwp_tgl_berlaku:date","bpjs_tipe_id:bigint","bpjs_no:string:30","bpjs_foto:string:191","berkas_lain:string:191","desc_file:text","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","bpjs_no_kesehatan:string:191","bpjs_no_ketenagakerjaan:string:191"];
    public $rules       = [];
    public $joins       = ["m_kary.id=m_kary_det_kartu.m_kary_id","m_comp.id=m_kary_det_kartu.m_comp_id","m_dir.id=m_kary_det_kartu.m_dir_id","m_general.id=m_kary_det_kartu.bpjs_tipe_id","default_users.id=m_kary_det_kartu.creator_id","default_users.id=m_kary_det_kartu.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["m_kary_id","m_comp_id","m_dir_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id","bpjs_no_kesehatan","bpjs_no_ketenagakerjaan"];
    public $updateable  = ["m_kary_id","m_comp_id","m_dir_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id","bpjs_no_kesehatan","bpjs_no_ketenagakerjaan"];
    public $searchable  = ["id","m_kary_id","m_comp_id","m_dir_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id","created_at","updated_at","bpjs_no_kesehatan","bpjs_no_ketenagakerjaan"];
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
    public function bpjs_tipe() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'bpjs_tipe_id', 'id');
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
