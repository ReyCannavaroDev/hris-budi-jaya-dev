<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class t_pengumuman extends Model
{   
    use ModelTrait;

    protected $table    = 't_pengumuman';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["judul","thumb","slug","tag","content","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","judul","thumb","slug","tag","content","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","judul:string:191","thumb:string:191","slug:string:191","tag:string:191","content:text","is_active:boolean","creator_id:integer","last_editor_id:integer","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = [];
    public $details     = [];
    public $heirs       = [];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["judul","thumb","content","is_active"];
    public $createable  = ["judul","thumb","slug","tag","content","is_active","creator_id","last_editor_id"];
    public $updateable  = ["judul","thumb","slug","tag","content","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","judul","thumb","slug","tag","content","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
}
