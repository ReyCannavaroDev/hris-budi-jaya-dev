<?php
namespace App\Cores;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Middleware
{
    public function handle( Request $request, Closure $next ) 
    {
        @$modelName = $request->route()[2]['modelname'];
        if(!$modelName) return $next($request);
        $modelInstance = @app("App\\Models\\CustomModels\\$modelName");
        $user = auth()->user();
        $requestData = $request->all();
   
        if(app()->request->header('Source') === 'mobile') {
            $modelInstance->addGlobalScope('global', function (Builder $builder) use($modelName, $modelInstance, $user) {
                if(in_array('creator_id', $modelInstance->columns) && !in_array(@$modelInstance->getTable(),['generate_approval','generate_approval_det','m_kary','default_users','m_general','m_dir','m_zona','m_lokasi','m_divisi','m_dept','m_posisi','m_spd','presensi_app_version','t_pengumuman'])){
                    $builder = $builder->where("$modelName.creator_id", $user->id);
                    // inject auto relation for mobile with column assigned to pic_id
                    if(in_array('pic_id', $modelInstance->columns) ){
                        $builder = $builder->orWhere("$modelName.pic_id", $user->id);
                    }
                    return $builder;
                }
                if(in_array('pic_id', $modelInstance->columns) && !in_array(@$modelInstance->getTable(),['generate_approval','generate_approval_det','m_kary','default_users','m_general','m_dir','m_zona','m_lokasi','m_divisi','m_dept','m_posisi','m_spd','presensi_app_version'])){
                    return $builder->where("$modelName.creator_id", $user->id);
                }
                if(in_array('tanggal', $modelInstance->columns)){
                    if(req('date_from') && req('date_to')){
                        return $builder->whereBetween('tanggal',[req('date_from'),req('date_to')]);
                    }
                }
                if(in_array('date_from', $modelInstance->columns)){
                    if(req('date_from') && req('date_to')){
                        return $builder->whereRaw("$modelName.date_from >= ? or $modelName.date_to <= ?",[req('date_from'), req('date_to')]);
                    }
                }
            });
            if(in_array('m_kary_id', $modelInstance->columns)) {
                $requestData['m_kary_id'] = @$user->m_kary_id;
            }
        }
        $request->replace($requestData);

        return $next($request);
    }
}
