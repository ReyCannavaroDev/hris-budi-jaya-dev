<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class presensi_absensi extends Model
{   
    use ModelTrait;

    protected $table    = 'presensi_absensi';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","default_user_id","tanggal","status","checkin_time","checkin_foto","checkin_lat","checkin_long","checkin_address","checkin_region","checkin_on_scope","checkout_time","checkout_foto","checkout_lat","checkout_long","checkout_address","checkout_region","checkout_on_scope","creator_id","last_editor_id","catatan_in","catatan_out","is_lembur","shift","jadwal_kerja_id","jam_lembur"];

    public $columns     = ["id","m_comp_id","default_user_id","tanggal","status","checkin_time","checkin_foto","checkin_lat","checkin_long","checkin_address","checkin_region","checkin_on_scope","checkout_time","checkout_foto","checkout_lat","checkout_long","checkout_address","checkout_region","checkout_on_scope","creator_id","last_editor_id","created_at","updated_at","catatan_in","catatan_out","is_lembur","shift","jadwal_kerja_id","jam_lembur"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","default_user_id:bigint","tanggal:date","status:string:191","checkin_time:time","checkin_foto:string:191","checkin_lat:string:191","checkin_long:string:191","checkin_address:string:191","checkin_region:string:191","checkin_on_scope:boolean","checkout_time:time","checkout_foto:string:191","checkout_lat:string:191","checkout_long:string:191","checkout_address:string:191","checkout_region:string:191","checkout_on_scope:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","catatan_in:text","catatan_out:text","is_lembur:boolean","shift:string:191","jadwal_kerja_id:bigint","jam_lembur:integer"];
    public $rules       = [];
    public $joins       = ["m_comp.id=presensi_absensi.m_comp_id","default_users.id=presensi_absensi.default_user_id","default_users.id=presensi_absensi.creator_id","default_users.id=presensi_absensi.last_editor_id","t_jadwal_kerja.id=presensi_absensi.jadwal_kerja_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["tanggal","status","checkin_time","checkin_lat","checkin_long","checkin_address","checkin_region","checkin_on_scope"];
    public $createable  = ["m_comp_id","default_user_id","tanggal","status","checkin_time","checkin_foto","checkin_lat","checkin_long","checkin_address","checkin_region","checkin_on_scope","checkout_time","checkout_foto","checkout_lat","checkout_long","checkout_address","checkout_region","checkout_on_scope","creator_id","last_editor_id","catatan_in","catatan_out","is_lembur","shift","jadwal_kerja_id","jam_lembur"];
    public $updateable  = ["m_comp_id","default_user_id","tanggal","status","checkin_time","checkin_foto","checkin_lat","checkin_long","checkin_address","checkin_region","checkin_on_scope","checkout_time","checkout_foto","checkout_lat","checkout_long","checkout_address","checkout_region","checkout_on_scope","creator_id","last_editor_id","catatan_in","catatan_out","is_lembur","shift","jadwal_kerja_id","jam_lembur"];
    public $searchable  = ["id","m_comp_id","default_user_id","tanggal","status","checkin_time","checkin_foto","checkin_lat","checkin_long","checkin_address","checkin_region","checkin_on_scope","checkout_time","checkout_foto","checkout_lat","checkout_long","checkout_address","checkout_region","checkout_on_scope","creator_id","last_editor_id","created_at","updated_at","catatan_in","catatan_out","is_lembur","shift","jadwal_kerja_id","jam_lembur"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function default_user() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'default_user_id', 'id');
    }
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
    public function jadwal_kerja() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_jadwal_kerja', 'jadwal_kerja_id', 'id');
    }
}
