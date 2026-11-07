<?php

namespace App\Models\CustomModels;
use Illuminate\Database\Eloquent\Builder;

class m_pengguna extends \App\Models\BasicModels\m_pengguna
{    
    public function __construct()
    {
        parent::__construct();

        $req = app()->request;
        $user = auth()->user();
        if ($req->isMethod('get') && $req->route('modelname') == 'm_menu') {
            static::addGlobalScope('global', function (Builder $builder) use ($req, $user) {
                if($user->m_dir_id == null){
                    $comp_id = $user->m_comp_id ?? 0 ;
                    $builder->whereRaw("m_menu.m_dir_id in(select d.id from m_dir d where d.m_comp_id = $comp_id)");
                }else{
                    $builder->where('m_menu.m_dir_id', $user->m_dir_id);
                }
            });
        }
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];


    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
    
        $check = default_users::where('username', req('username'))->exists();
        if($check && req('username'))
            return ["errors"=> ["Username sudah dipakai"]];

        $check = default_users::where('email', req('email'))->exists();
        if($check && req('email'))
            return ["errors"=> ["Email sudah dipakai"]];

        if(req('password') && req('password') != req('password_confirm'))
            return ["errors"=> ["Konfirmasi password salah"]];
     
        $m_dir_id = auth()->user()->m_dir_id;
        if(!$m_dir_id)  
            return [
                "errors" => ['Maaf akun anda tidak memiliki akses untuk menambahkan data']
            ];
        return [
            "model"  => $model,
            "data"   => array_merge($arrayData,[
                'm_dir_id' =>  $m_dir_id
            ])
        ];
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {
    
        if(req('password') && req('password') != req('password_confirm'))
            return ["errors"=> "Konfirmasi password salah"];
     
        return [
            "model"  => $model,
            "data"   => $arrayData
        ];
    }

    public function createAfter( $model, $arrayData, $metaData, $id=null )
    {        
        $hasher = app()->make('hash');
        $user = default_users::create([
            'name' => req('name'),
            'email' => req('email'),
            'username'=> req('username'),
            'password' => $hasher->make(req('password')),
            'm_comp_id' => auth()->user()->m_comp_id,
            'm_dir_id'  => auth()->user()->m_dir_id
        ]);

        $this->find($model['id'])->update(['default_user_id'=>$user->id]);
    }
    
    public function updateAfter( $model, $arrayData, $metaData, $id=null )
    {
        $hasher = app()->make('hash');
        if(!req('password')){
            $user = default_users::find($arrayData['default_user_id'])
            ->update([
                'name' => req('name'),
                'email' => req('email'),
                'username'=> req('username')
            ]);
        }else{
            $user = default_users::find($arrayData['default_user_id'])
            ->update([
                'name' => req('name'),
                'email' => req('email'),
                'username'=> req('username'),
                'password' => $hasher->make(req('password'))
            ]);
        }
    }
    
    public function deleteAfter( $model, $arrayData, $metaData, $id=null )
    {
        $user = default_users::find($arrayData['default_user_id'])->delete();
    }

    public function onRetrieved($model)
    {
        $req = app()->request;
        if($req->from == 'role_access'){
            $model->is_superadmin = 
                m_role_access::join('m_role as r','r.id','m_role_access.m_role_id')
                ->where('m_role_access.user_id', $model->default_user_id)
                ->pluck('is_superadmin')->first();
            if($req->detail)
                $model->detail =  m_role_access::join('m_role as r','r.id','m_role_access.m_role_id')
                ->select("r.*")
                ->where('m_role_access.user_id', $model->default_user_id)
                ->get();
        }
    }
    
}