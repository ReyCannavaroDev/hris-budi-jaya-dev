<?php
namespace App\Cores;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Defaults\User;
use Illuminate\Support\Facades\Hash;
use Validator;

class Frontend
{
    function __construct($app)
    {
        //  notification per push gitlab larahan
        $instance = $this;

        $app->router->post('/register', function () use ($instance) {
            return $instance->registerUser(app()->request);
        });

        $app->router->post('/register-otp', function () use ($instance) {
            if (!($code = app()->request->otp))
                return response()->json(['message' => 'otp is required'], 422);
            if (!($userId = \Cache::pull("otp_$code")))
                return response()->json(['message' => 'otp tidak valid atau mungkin expired'], 422);
            User::where('id', $userId)->update([
                'email_verified_at' => \Carbon::now()
            ]);
            return ['message' => 'Verifikasi akun berhasil, silahkan login dengan akun anda'];
        });

        $app->router->post('/register-request-otp', function () use ($instance) {
            if (!($phone = app()->request->phone))
                return response()->json(['message' => 'phone is required'], 422);
            if (!($user = User::where('phone', $phone)->first()))
                return response()->json(['message' => 'User dengan nomor ini tak ditemukan'], 422);

            $dst = $user->phone;
            if (\Str::startsWith($dst, '08')) {
                $dst = \Str::replaceFirst('08', '628', $dst);
            }
            $dst = filter_var($dst, FILTER_VALIDATE_INT);
            $dst = "$dst@c.us"; // ASSIGN ME

            $code = getCore('Helper')->random_str(5);
            while (\Cache::has("otp_$code")) {
                $code = getCore('Helper')->random_str(5);
            }
            $msg = "*OTP Posyandu-Care:*\n\n_" . $code . "_\n\nToken will expiry in 5 mins";
            \Cache::put("otp_$code", $user->id, $seconds = 5 * 60);

            getCore('Notification')->whatsapp($dst, $msg, true);

            return ['message' => 'Verifikasi akun berhasil, silahkan login dengan akun anda'];
        });

        $app->router->post('/me', [
            'middleware' => ['auth:api'],
            function () use ($instance) {
                return $instance->updateUser(app()->request);
            }
        ]);

        $app->router->get('/report/{type}', [
            /*'middleware' => ['auth:api'],*/    function ($type) {
                    return getCore('Report')->report($type);
                }
        ]);

        $default_menu = \DB::table('m_general')->where('group','DEFAULT MENU')->pluck('value')->first();
        $default_menu = explode(',',$default_menu);

        $fix_default_menu = [];
        foreach($default_menu as $dm){
            if($dm == 'Notifikasi'){
                $fix_default_menu[] = [
                    'modul' => $dm,
                    'text' => $dm,
                    'path' => '/notifikasi',
                    "truncatable" => false,
                    "icon" => "bell",
                    "description" => null,
                    "endpoint" => "/notifikasi"
                ];
            }
            if($dm == 'Absen Online'){
                $fix_default_menu[] = [
                    'modul' => $dm,
                    'text' => $dm,
                    'path' => '/presensi_absen_online',
                    "truncatable" => false,
                    "icon" => "camera",
                    "description" => null,
                    "endpoint" => "/presensi_absen_online"
                ];
            }
            if($dm == 'Pesan Makan Siang'){
                $fix_default_menu[] = [
                    'modul' => $dm,
                    'text' => $dm,
                    'path' => '/pesan_maksi',
                    "truncatable" => false,
                    "icon" => "cutlery",
                    "description" => null,
                    "endpoint" => "/pesan_maksi"
                ];
            }
            if($dm == 'Profil'){
                $fix_default_menu[] = [
                    'modul' => $dm,
                    'text' => $dm,
                    'path' => '/account',
                    "truncatable" => false,
                    "icon" => "user",
                    "description" => null,
                    "endpoint" => "/account"
                ];
            }
            if($dm == 'Lembur'){
                $fix_default_menu[] = [
                    'modul' => $dm,
                    'text' => $dm,
                    'path' => '/t_lembur',
                    "truncatable" => false,
                    "icon" => "hammer",
                    "description" => null,
                    "endpoint" => "/t_lembur"
                ];
            }
            if($dm == 'Cuti'){
                $fix_default_menu[] = [
                    'modul' => $dm,
                    'text' => $dm,
                    'path' => '/t_cuti',
                    "truncatable" => false,
                    "icon" => "calendar",
                    "description" => null,
                    "endpoint" => "/t_cuti"
                ];
            }
            if($dm == 'Slip Gaji User'){
                $fix_default_menu[] = [
                    'modul' => $dm,
                    'text' => $dm,
                    'path' => '/t_slip_gaji',
                    "truncatable" => false,
                    "icon" => "calendar",
                    "description" => null,
                    "endpoint" => "/t_slip_gaji"
                ];
            }
        }

        $app->router->get('/frontend-menu', [
            'middleware' => ['auth:api'],
            function () use ($instance, $fix_default_menu) {
                return count($instance->formatMenu($instance->getMenu())) ? $instance->formatMenu($instance->getMenu()) : $fix_default_menu;
            }
        ]);
        $app->router->get('/frontend-menu/{name}', [
            'middleware' => ['auth:api'],
            function ($name) use ($instance) {
                return $instance->getMenu($name);
            }
        ]);

        $app->router->get('/frontend-template', function () use ($instance) {
            return $instance->getTemplate();
        });

        $app->router->get('/frontend-file', function () use ($instance) {
            return $instance->getFile();
        });

        $app->router->get('/frontend-component', function () use ($instance) {
            return $instance->generateFrontend();
        });

        // ======================================== APP
        $app->router->get('app', function () {
            return \File::get(public_path('app/index.html'));
        });
        $app->router->get('app/{other}', function ($other) {
            if (\Str::contains($other, '.')) {
                return \File::get(public_path("app/$other"));
            }
            return \File::get(public_path('app/index.html'));
        });
        $app->router->get('app/{other}/{another}', function ($other, $another) {
            return \File::get(public_path('app/index.html'));
        });
        // ======================================== APP END
    }

    public function registerUser($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            // 'email' => 'required|string|email|unique:default_users',
            'username' => 'required|string|unique:default_users',
            // 'nip' => 'required|numeric|unique:default_users',
            'type' => 'required|string|in:pasien,dokter',
            'phone' => 'required|numeric|digits_between:9,13|unique:default_users',
            // 'gender' => 'required|string|in:Male,Perempuan',
            'password' => 'required|string|min:6|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $request->email = $request->username;

        try {
            \DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                // 'nip' => $request->nip,
                'type' => $request->type,
                'phone' => $request->phone,
                // 'gender' => $request->gender,
                'password' => Hash::make($request->password)
            ]);

            $dst = $request->phone;
            if (\Str::startsWith($dst, '08')) {
                $dst = \Str::replaceFirst('08', '628', $dst);
            }
            $dst = filter_var($dst, FILTER_VALIDATE_INT);
            $dst = "$dst@c.us"; // ASSIGN ME

            $code = getCore('Helper')->random_str(5);
            while (\Cache::has("otp_$code")) {
                $code = getCore('Helper')->random_str(5);
            }

            $msg = "*OTP Posyandu-Care:*\n\n_" . $code . "_\n\nToken will expiry in 5 mins";
            \Cache::put("otp_$code", $user->id, $seconds = 5 * 60);

            getCore('Notification')->whatsapp($dst, $msg, true);
        } catch (\Exception $err) {
            \DB::rollback();
            return response()->json([
                'message' => "Terjadi kesalahan",
                'programmer_message' => $err->getMessage(),
                'errors' => [$err->getFile(), $err->getLine(), $err->getMessage()]
            ], 400);
        }
        \DB::commit();

        return response()->json([
            'message' => 'Silahkan Masukkan OTP yang terkirim sebelum 5 menit.',
            'url_verify' => url("/register-otp [POST]")
        ], 201);
    }

    public function updateUser($request)
    {
        $user = \Auth::user();
        $validationArr = [
            'email' => "filled|string|email|unique:default_users,email,$user->id",
            'phone' => "filled|digits_between:9,13|unique:default_users,phone,$user->id",
            'gender' => 'filled|string|in:Laki-laki,Perempuan',
            'file_avatar' => 'filled|file|image|max:5250',
            'password' => 'filled|string|min:6|max:12|confirmed'
        ];

        $validator = Validator::make($request->all(), $validationArr);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $profileArr = [];

        if ($request->has('file_avatar')) {
            $request->file = $request->file_avatar;
            $filename = uploadfile(getBasic('default_users'), $request, md5(md5($user->id)) . ".");
            $request->file_avatar = $filename;
        }

        foreach ($validationArr as $key => $val) {
            if (!in_array($key, ['password_confirmation']) && $request->has($key)) {
                $profileArr[$key] = ($key == 'password' ? Hash::make($request->$key) : $request->$key);
            }
        }

        $user->update($profileArr);

        return response()->json([
            'message' => 'Profile anda telah berhasil diupdate',
            'updated_keys' => array_keys($profileArr),
            'profile' => $user
        ]);
    }

    public function landing($req)
    {
        return \File::get(public_path('app/index.html'));
    }

    public function getFile()
    {
        $request = app()->request;
        $contents = base64_decode(\Cache::get($request->file));
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->buffer($contents);
        $length = strlen($contents);
        return response($contents)->header('Cache-Control', 'no-cache private')->header('Content-Type', $mime);
    }

    private function formatMenu($allMenu)
    {
        $req = app()->request;
        $fixedMenu = [];
        foreach ($allMenu as $row) {
            $modul = @$row["modul"] ?? $row["text"];
            $modulLower = strtolower($modul);
            $injIcon = getCustom('m_general')->where('is_active', true)->whereRaw("lower(key) = 'sidebar-icon' and lower(code) = '$modulLower'")->pluck('value')->first();

            if(@$row['type'] == 'single'){
            // jika type single pakai path + endpoint
                $fixedMenu[] = [
                    'modul' => $modul,
                    'text' => $modul,
                    'path' => $row['path'],
                    'truncatable' => @$row['truncatable'] ? true : false,
                    'icon' => ($injIcon ?? $row['icon']) ?? 'cog',
                    'description' =>  $row['description'],
                    'endpoint' =>  $row['path']
                ];
            }else{
                // menu yang punya child (dropdown)
                $fixedMenu[] = [
                    'modul' => $modul,
                    'text' => $modul,
                    'path' => '#',
                    'truncatable' => true,
                    'icon' => ($injIcon ?? $row['icon']) ?? 'cog',
                    'description' => null,
                    'endpoint' => null,
                    'children' => $this->getMenu($modul)
                ];
            }

            // tambahakan separator untuk pemisah modul
            $fixedMenu[] =   [
                "separator" => true,
            ];
        }

        if ($req->has('search') && $req->search !== '' && $req->search !== 'null') {
            $searchText = strtolower($req->search);
            $fixedMenu = array_values(array_filter($fixedMenu, function ($menu) use ($searchText) {
                return \Str::contains(strtolower($menu['modul']), $searchText) || \Str::contains(strtolower($menu['menu']), $searchText)
                    || \Str::contains(strtolower($menu['path']), $searchText);
            }));
        }


        if ($req->has('notin') && $req->notin !== '' && $req->notin !== 'null') {
            $notinArr = explode(',', strtolower($req->notin));
            $fixedMenu = array_values(array_filter($fixedMenu, function ($menu) use ($notinArr) {
                return !in_array(strtolower($menu['menu']), $notinArr);
            }));
        }

        return $fixedMenu;
    }

    public function getMenu($filterModul = null)
    {
        $findMenu = null;
        $user = \Auth::user();
        $user_id = @$user->id ?? 0;

        if(!$filterModul){
            $result = \DB::select("
            select 
                (select case 
                    when count(1) < 2 then 'single'
                    else 'multi'
                end
                from m_menu a where a.modul = b.modul and a.m_dir_id = b.m_dir_id limit 3) type ,
                b.modul,
                (select 
                    case
                        when count(1) < 2 then (select c.sequence  from m_menu c where c.modul = b.modul)
                        else (select c.sequence from m_menu c where c.modul = b.modul order by c.sequence asc limit 1)
                    end
                    from m_menu a where a.modul = b.modul and a.m_dir_id = b.m_dir_id group by a.modul limit 2) sequence,
                (select 
                    case
                        when count(1) < 2 then (select c.path from m_menu c where c.modul = b.modul)
                        else '#'
                    end
                    from m_menu a where a.modul = b.modul and a.m_dir_id = b.m_dir_id group by a.modul limit 2) path,
                (select 
                    case
                        when count(1) < 2 then (select c.description  from m_menu c where c.modul = b.modul)
                        else '#'
                    end
                    from m_menu a where a.modul = b.modul and a.m_dir_id = b.m_dir_id group by a.modul limit 2) description,
                (select 
                        case
                            when count(1) < 2 then (select c.icon  from m_menu c where c.modul = b.modul)
                            else null
                        end
                    from m_menu a where a.modul = b.modul and a.m_dir_id = b.m_dir_id group by a.modul limit 2) icon
                from m_menu b where b.is_active = true 
                -- tampilkan menu berdasarkan role access dan superadmin by company
                and case
                    when (select mr.is_superadmin from m_role mr 
                        join m_role_access mra on mr.id = mra.m_role_id
                        join default_users u on u.id = mra.user_id 
                        where mra.user_id = ? limit 1) = false then 
                        b.id in(select
                            rd.m_menu_id 
                            from m_role_det rd 
                            join m_role r on r.id = rd.m_role_id 
                            join m_role_access ra on ra.m_role_id = r.id
                            where r.is_active = true and ra.user_id  = ?
                        ) 
                    -- is superadmin true
                  when (select mr.is_superadmin from m_role mr 
                        join m_role_access mra on mr.id = mra.m_role_id
                        join default_users u on u.id = mra.user_id 
                        where mra.user_id = ? limit 1) = true 
                        then b.id = b.id
                else 
                    case 
                		when (select m_dir_id from default_users u where u.id = ?) is null then b.id = b.id
                	end
                end
                group by modul, m_dir_id order by 3", [
                    $user_id, 
                    $user_id, 
                    $user_id, 
                    $user_id
                    // $user_id, 
                    // @$user->m_dir_id ?? 0
                ]);
        }else{
            $result = \DB::select("
                select menu text, path, endpoint, icon from m_menu b where b.is_active = true and b.modul = ?
                -- tampilkan menu berdasarkan role acces    s dan superadmin by company
                and case
                    when (select mr.is_superadmin from m_role mr 
                        join m_role_access mra on mr.id = mra.m_role_id
                        join default_users u on u.id = mra.user_id 
                        where mra.user_id = ? limit 1) is false then 
                        b.id in(select
                            rd.m_menu_id 
                            from m_role_det rd 
                            join m_role r on r.id = rd.m_role_id 
                            join m_role_access ra on ra.m_role_id = r.id
                            where r.is_active = true and ra.user_id  = ?
                        ) 
                    -- is superadmin true
                    when (select mr.is_superadmin from m_role mr 
                        join m_role_access mra on mr.id = mra.m_role_id
                        join default_users u on u.id = mra.user_id 
                        where mra.user_id = ? limit 1) = true 
                        then b.id = b.id
                else 
                    case 
                		when (select m_dir_id from default_users u where u.id = ?) is null then b.id = b.id
                	end
                 end
                order by b.sequence
            ", [
                $filterModul, $user_id, $user_id, $user_id, $user_id
                // $user_id, @$user->m_dir_id ?? 0
            ]);
        }


        return json_decode(json_encode($result), true);

        // $userMenu = [];
        // $allMenus = [
        //     [
        //         "text" => "Notification",
        //         "path" => "/t_notification",
        //         "icon" => "bell",
        //         "endpoint" => "/t_notification",
        //         "version" => $this->xgetViewVersion('t_notification'),
        //     ],
        //     [
        //         "text" => "Data Master",
        //         "path" => "#",
        //         "icon" => "database",
        //         "children" => $this->filterSubMenu([
        //             [
        //                 "text" => "User",
        //                 "path" => "/m_user",
        //                 "icon" => "users",
        //                 "endpoint" => "/default_users",
        //                 "version" => $this->xgetViewVersion('m_user'),
        //             ],
        //         ], $userMenu),
        //     ],
            
        //     [
        //         "separator" => true,
        //     ],
        // ];

        // $filteredMenus = array_values(array_filter($allMenu->toArray(), function ($menu) use ($access) {
        //     if (isset($menu["text"]) && isset($access)) {
        //         return in_array($menu["id"], $access->toArray());
        //     } else {
        //         return false; 
        //     }

        // }));

        return $filteredMenus;

    }

    private function filterSubMenu($all, $userMenu) 
    {
        return $all;
        $fixedChild = [];
        foreach ($all as $subMenu) {
            if (in_array($subMenu['text'], $userMenu)) {
                array_push($fixedChild, $subMenu);
            }
        }
        return $fixedChild;
    }



    public function displayMenuItems()
    {
        $filteredMenus = $this->getMenu();
        foreach ($filteredMenus as $menu) {
            if ($menu["text"] === "Data Master" && isset($menu["children"])) {
                foreach ($menu["children"] as $childMenu) {
                    echo $childMenu["text"] . PHP_EOL;
                }
                break;
            }
        }
    }
    public function getTemplate()
    {
        try {
            $req = app()->request;
            $origin = getOrigin(true);
            $isAuthed = true;
            // $dontAuth = ['pos-sandbox.isolva.com'];

            if (!$req->has('modul'))
                abort(404, json_encode(['message' => 'Template was Not Found']));
            $user = (new \App\Models\Defaults\User)->getFromHeaderToken();
            $isPublic = $req->has('isPublic') && ($req->isPublic == 'true');

            $modul = strtolower(explode(":", $req->modul)[0]);

            $action = $req->has('action') ? strtolower($req->action) : 'read';
            $id = $req->has("id") ? $req->id : null;
            if (!$req->has('action') && strtolower($id) == 'create') {
                $action = 'create';
            }
            if ($action == 'edit')
                $action = 'update';

            if (!$isPublic && !$user && !$isAuthed)
                abort(401, json_encode(['message' => 'Unauthorized']));

            if (!$isAuthed && !$isPublic && $user->role != 'superuser' && $action != 'copy' && strpos($modul, 'tutorial') === false && strpos($modul, 'dashboard') === false && strpos($modul, 'account') === false && !\Str::startsWith($modul, 'login_')) {

                // try{
                //     $isAuthed = \DB::table('m_user_d_access as usr_d')
                //     ->join("m_user as usr","usr.id","=","usr_d.m_user_id")
                //     ->where("usr.user_id", $user->id)
                //     ->where("path", '/'.$modul)
                //     ->where("can_".$action, true)->exists();
                // }catch(\Throwable $e){
                //     $isAuthed = null;
                // }
                if (!$isAuthed)
                    return "<template><h1 class='bg-white text-center p-2 text-red-600'>Tidak Memiliki hak akses ini, aksi ini tercatat! </h1></template>";
            }

            $version = $req->has('version') ? $req->version : 1;
            if ($version == 'undefined' && !$isPublic)
                abort(404);

            $html = view("projects.$modul", compact('req'))->render();
            $jsString = \File::get(public_path("js/$modul.js"));
            $js = Blade::render($jsString, ['id' => $id]);

            $fixedTemplate = "<template>\n$html\n</template>\n<script setup>\n$js\n</script>";
            return $req->has('v3') ? base64_encode($fixedTemplate) : $fixedTemplate;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /* Mendapatkan template blade native */
    private function getBladeNative($req)
    {
        if (!$req->modul) {
            $req->modul = 'index';
        }
        return view("projects.cmp_$req->modul", compact('req'));
    }

    private function xgetViewVersion(string $modul)
    {
        try {
            $bladeLastChanged = filemtime(resource_path("views/projects/$modul.blade.php"));
            $jsLastChanged = filemtime(public_path("js/$modul.js"));
            return abs($bladeLastChanged + $jsLastChanged);
        } catch (\Exception $e) {
            return 1;
        }
    }

    private function xfilterMenu(array $menus, array $filters)
    {
        if ($filters || config('SuperAdmin') || app()->request->has('table')) {
            $fixedMenu = [];
            foreach ($menus as $menu) {
                if (config('SuperAdmin') || app()->request->has('table')) {
                    $fixedMenu[] = array_merge($menu, [
                        'can_create' => true,
                        'can_delete' => true,
                        'can_read' => true,
                        'can_update' => true
                    ]);
                    continue;
                }
                $foundIdx = array_search($menu['path'], array_column($filters, 'path'));
                if (is_numeric($foundIdx)) {
                    $fixedMenu[] = array_merge($menu, Arr::only($filters[$foundIdx], [
                        'can_create',
                        'can_delete',
                        'can_read',
                        'can_update'
                    ]));
                }
            }
            return $fixedMenu;
        } else {
            return [];
        }
    }

    public function generateFrontend()
    {
        $tag = app()->request->tag;
        return view("projects.bank_component", compact('tag'))->render();
    }
}
