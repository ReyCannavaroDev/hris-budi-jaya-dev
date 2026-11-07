<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_pph_det extends Model
{   
    use ModelTrait;

    protected $table    = 'm_pph_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_pph_id","gaji_min","gaji_max","npwp","non_npwp"];

    public $columns     = ["id","m_pph_id","gaji_min","gaji_max","npwp","non_npwp","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_pph_id:bigint","gaji_min:decimal","gaji_max:decimal","npwp:decimal","non_npwp:decimal","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_pph.id=m_pph_det.m_pph_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["gaji_min","gaji_max","npwp","non_npwp"];
    public $createable  = ["m_pph_id","gaji_min","gaji_max","npwp","non_npwp"];
    public $updateable  = ["m_pph_id","gaji_min","gaji_max","npwp","non_npwp"];
    public $searchable  = ["id","m_pph_id","gaji_min","gaji_max","npwp","non_npwp","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function m_pph() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_pph', 'm_pph_id', 'id');
    }
}
