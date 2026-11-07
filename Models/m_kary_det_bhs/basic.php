<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_kary_det_bhs extends Model
{   
    use ModelTrait;

    protected $table    = 'm_kary_det_bhs';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_kary_id","m_comp_id","m_dir_id","bhs_dikuasai","nilai_lisan","nilai_tertulis","desc","creator_id","last_editor_id","level_lisan","level_tertulis"];

    public $columns     = ["id","m_kary_id","m_comp_id","m_dir_id","bhs_dikuasai","nilai_lisan","nilai_tertulis","desc","creator_id","last_editor_id","created_at","updated_at","level_lisan","level_tertulis"];
    public $columnsFull = ["id:bigint","m_kary_id:bigint","m_comp_id:bigint","m_dir_id:bigint","bhs_dikuasai:string:100","nilai_lisan:integer","nilai_tertulis:integer","desc:text","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime","level_lisan:string:191","level_tertulis:string:191"];
    public $rules       = [];
    public $joins       = ["m_kary.id=m_kary_det_bhs.m_kary_id","m_comp.id=m_kary_det_bhs.m_comp_id","m_dir.id=m_kary_det_bhs.m_dir_id","default_users.id=m_kary_det_bhs.creator_id","default_users.id=m_kary_det_bhs.last_editor_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["bhs_dikuasai"];
    public $createable  = ["m_kary_id","m_comp_id","m_dir_id","bhs_dikuasai","nilai_lisan","nilai_tertulis","desc","creator_id","last_editor_id","level_lisan","level_tertulis"];
    public $updateable  = ["m_kary_id","m_comp_id","m_dir_id","bhs_dikuasai","nilai_lisan","nilai_tertulis","desc","creator_id","last_editor_id","level_lisan","level_tertulis"];
    public $searchable  = ["id","m_kary_id","m_comp_id","m_dir_id","bhs_dikuasai","nilai_lisan","nilai_tertulis","desc","creator_id","last_editor_id","created_at","updated_at","level_lisan","level_tertulis"];
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
    public function creator() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'creator_id', 'id');
    }
    public function last_editor() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\default_users', 'last_editor_id', 'id');
    }
}
