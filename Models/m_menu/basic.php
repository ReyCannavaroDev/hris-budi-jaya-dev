<?php

namespace App\Models\BasicModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelTrait;

class m_menu extends Model
{   
    use ModelTrait;

    protected $table    = 'm_menu';
    protected $guarded  = ["id"];
    protected $casts    = [
    "created_at"=> "datetime:d\/m\/Y H:i",
    "updated_at"=> "datetime:d\/m\/Y H:i"
	];
    protected $fillable = ["m_comp_id","m_dir_id","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id"];

    public $columns     = ["id","m_comp_id","m_dir_id","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $columnsFull = ["id:bigint","m_comp_id:bigint","m_dir_id:bigint","modul:string:191","submodul:string:191","menu:string:191","path:string:191","endpoint:string:191","icon:string:191","sequence:decimal","description:string:255","note:string:255","truncatable:boolean","is_active:boolean","creator_id:bigint","last_editor_id:bigint","created_at:datetime","updated_at:datetime"];
    public $rules       = [];
    public $joins       = ["m_comp.id=m_menu.m_comp_id","m_dir.id=m_menu.m_dir_id","default_users.id=m_menu.creator_id","default_users.id=m_menu.last_editor_id"];
    public $details     = [];
    public $heirs       = ["m_approval","m_role_det"];
    public $detailsChild= [];
    public $detailsHeirs= [];
    public $unique      = [];
    public $required    = ["modul","menu","path","endpoint","is_active"];
    public $createable  = ["m_comp_id","m_dir_id","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id"];
    public $updateable  = ["m_comp_id","m_dir_id","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id"];
    public $searchable  = ["id","m_comp_id","m_dir_id","modul","submodul","menu","path","endpoint","icon","sequence","description","note","truncatable","is_active","creator_id","last_editor_id","created_at","updated_at"];
    public $deleteable  = true;
    public $cascade     = true;
    public $deleteOnUse = false;

    
    
    
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
