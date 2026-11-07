<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pelamar_det_kartu extends Model
{   
    use ModelTrait;

    protected $table    = 't_pelamar_det_kartu';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_pelamar_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id"];

    public $columns     = ["id","t_pelamar_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_pelamar_id:bigint","ktp_no:string:25","ktp_foto:string:191","pas_foto:string:191","kk_no:string:25","kk_foto:string:191","npwp_no:string:25","npwp_foto:string:191","npwp_tgl_berlaku:date","bpjs_tipe_id:bigint","bpjs_no:string:30","bpjs_foto:string:191","berkas_lain:string:191","desc_file:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_pelamar.id=t_pelamar_det_kartu.t_pelamar_id","m_general.id=t_pelamar_det_kartu.bpjs_tipe_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = [""];
    public $createable  = ["t_pelamar_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id"];
    public $updateable  = ["t_pelamar_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id"];
    public $searchable  = ["id","t_pelamar_id","ktp_no","ktp_foto","pas_foto","kk_no","kk_foto","npwp_no","npwp_foto","npwp_tgl_berlaku","bpjs_tipe_id","bpjs_no","bpjs_foto","berkas_lain","desc_file","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_pelamar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pelamar', 't_pelamar_id', 'id');
    }
    public function bpjs_tipe() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'bpjs_tipe_id', 'id');
    }
}
