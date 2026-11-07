<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_potongan_det_bayar extends Model
{   
    use ModelTrait;

    protected $table    = 't_potongan_det_bayar';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","t_potongan_id","t_final_gaji_id","percentage","nilai","paid_at","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","t_potongan_id","t_final_gaji_id","percentage","nilai","paid_at","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","t_potongan_id:bigint","t_final_gaji_id:bigint","percentage:decimal","nilai:decimal","paid_at:datetime","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=t_potongan_det_bayar.m_comp_id","t_potongan.id=t_potongan_det_bayar.t_potongan_id","t_final_gaji.id=t_potongan_det_bayar.t_final_gaji_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["nilai","paid_at"];
    public $createable  = ["m_comp_id","t_potongan_id","t_final_gaji_id","percentage","nilai","paid_at","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","t_potongan_id","t_final_gaji_id","percentage","nilai","paid_at","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","t_potongan_id","t_final_gaji_id","percentage","nilai","paid_at","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_comp() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_comp', 'm_comp_id', 'id');
    }
    public function t_potongan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_potongan', 't_potongan_id', 'id');
    }
    public function t_final_gaji() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_final_gaji', 't_final_gaji_id', 'id');
    }
}
