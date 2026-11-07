<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_salary_borongan_det extends Model
{   
    use ModelTrait;

    protected $table    = 't_salary_borongan_det';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_salary_borongan_id","m_tarif_group_id","m_tarif_id","tarif","tarif_desc","qty","subtotal","keterangan","creator_id","last_editor_id"];

    public $columns     = ["id","t_salary_borongan_id","m_tarif_group_id","m_tarif_id","tarif","tarif_desc","qty","subtotal","keterangan","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_salary_borongan_id:bigint","m_tarif_group_id:bigint","m_tarif_id:bigint","tarif:decimal","tarif_desc:string:100","qty:decimal","subtotal:decimal","keterangan:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_salary_borongan.id=t_salary_borongan_det.t_salary_borongan_id","m_tarif_group.id=t_salary_borongan_det.m_tarif_group_id","m_tarif.id=t_salary_borongan_det.m_tarif_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["m_tarif_group_id","m_tarif_id","tarif","qty","subtotal"];
    public $createable  = ["t_salary_borongan_id","m_tarif_group_id","m_tarif_id","tarif","tarif_desc","qty","subtotal","keterangan","creator_id","last_editor_id"];
    public $updateable  = ["t_salary_borongan_id","m_tarif_group_id","m_tarif_id","tarif","tarif_desc","qty","subtotal","keterangan","creator_id","last_editor_id"];
    public $searchable  = ["id","t_salary_borongan_id","m_tarif_group_id","m_tarif_id","tarif","tarif_desc","qty","subtotal","keterangan","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_salary_borongan() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_salary_borongan', 't_salary_borongan_id', 'id');
    }
    public function m_tarif_group() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_tarif_group', 'm_tarif_group_id', 'id');
    }
    public function m_tarif() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\m_tarif', 'm_tarif_id', 'id');
    }
}
