<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_final_gaji extends Model
{   
    use ModelTrait;

    protected $table    = 't_final_gaji';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["nomor","m_comp_id","periode_awal","periode_akhir","total_pengeluaran_gaji","desc","status","creator_id","last_editor_id","tipe_gaji","type_perhitungan"];

    public $columns     = ["id","nomor","m_comp_id","periode_awal","periode_akhir","total_pengeluaran_gaji","desc","status","creator_id","last_editor_id","created_at","updated_at","tipe_gaji","type_perhitungan"];
    public $columnsFull = ["id:bigint","nomor:string:50","m_comp_id:bigint","periode_awal:date","periode_akhir:date","total_pengeluaran_gaji:decimal","desc:text","status:string:50","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","tipe_gaji:bigint","type_perhitungan:string:191"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_final_gaji.m_comp_id","default_users.id=t_final_gaji.creator_id","default_users.id=t_final_gaji.last_editor_id","m_general.id=t_final_gaji.tipe_gaji"];
    public $details     = ["t_final_gaji_det","t_potongan_det_bayar"];
    public $heirs       = [];
    public $detailsChild= ["t_final_gaji_det_rincian"];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["periode_awal","periode_akhir","total_pengeluaran_gaji","desc"];
    public $createable  = ["nomor","m_comp_id","periode_awal","periode_akhir","total_pengeluaran_gaji","desc","status","creator_id","last_editor_id","tipe_gaji","type_perhitungan"];
    public $updateable  = ["nomor","m_comp_id","periode_awal","periode_akhir","total_pengeluaran_gaji","desc","status","creator_id","last_editor_id","tipe_gaji","type_perhitungan"];
    public $searchable  = ["id","nomor","m_comp_id","periode_awal","periode_akhir","total_pengeluaran_gaji","desc","status","creator_id","last_editor_id","created_at","updated_at","tipe_gaji","type_perhitungan"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    public function t_final_gaji_det() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_final_gaji_det', 't_final_gaji_id', 'id');
    }
    public function t_potongan_det_bayar() :\HasMany
    {
        return $this->hasMany('App\Models\BasicModels\t_potongan_det_bayar', 't_final_gaji_id', 'id');
    }
    
    
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
    public function tipe_gaji() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_general', 'tipe_gaji', 'id');
    }
}
