<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pelamar_det_bhs extends Model
{   
    use ModelTrait;

    protected $table    = 't_pelamar_det_bhs';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["t_pelamar_id","bhs_dikuasai","nilai_lisan","level_lisan","nilai_tertulis","level_tertulis","desc","creator_id","last_editor_id"];

    public $columns     = ["id","t_pelamar_id","bhs_dikuasai","nilai_lisan","level_lisan","nilai_tertulis","level_tertulis","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","t_pelamar_id:bigint","bhs_dikuasai:string:100","nilai_lisan:integer","level_lisan:string:191","nilai_tertulis:integer","level_tertulis:string:191","desc:text","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["t_pelamar.id=t_pelamar_det_bhs.t_pelamar_id"];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["bhs_dikuasai","level_lisan","level_tertulis"];
    public $createable  = ["t_pelamar_id","bhs_dikuasai","nilai_lisan","level_lisan","nilai_tertulis","level_tertulis","desc","creator_id","last_editor_id"];
    public $updateable  = ["t_pelamar_id","bhs_dikuasai","nilai_lisan","level_lisan","nilai_tertulis","level_tertulis","desc","creator_id","last_editor_id"];
    public $searchable  = ["id","t_pelamar_id","bhs_dikuasai","nilai_lisan","level_lisan","nilai_tertulis","level_tertulis","desc","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
    public function t_pelamar() :\BelongsTo
    {
        return $this->belongsTo('App\Models\BasicModels\t_pelamar', 't_pelamar_id', 'id');
    }
}
