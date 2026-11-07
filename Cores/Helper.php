<?php

namespace App\Cores;

use Carbon\Carbon;
use DB;
use App\Models\CustomModels\generate_num;
use App\Models\CustomModels\generate_num_type;
use App\Models\CustomModels\generate_num_log;
use App\Models\CustomModels\generate_num_det;
use App\Models\CustomModels\m_approval;
use App\Models\CustomModels\m_approval_det;
use App\Models\CustomModels\generate_approval;
use App\Models\CustomModels\generate_approval_det;
use App\Models\CustomModels\generate_approval_log;
use App\Models\CustomModels\m_kary;
use App\Models\CustomModels\default_users;
use \stdClass;

class Helper
{
    function __construct()
    {
        $this->timestamp = \Carbon\Carbon::now();
    }

    public function generateNomor($nama, $counter = true, $static = null)
    {
        // check header config
        $generate_num = generate_num::where("nama", $nama)
            ->where("is_active", true)
            ->first();

        if (!$static && !$generate_num) {
            trigger_error("Format penomoran tidak ditemukan");
        }

        DB::beginTransaction();

        try {
            // check details config and assemble code
            $temporaryCode = "";

            if ($static) {
                $generate_num_det = $static;
            } else {
                $generate_num_det = generate_num_det::where(
                    "generate_num_id",
                    $generate_num->id
                )
                    ->orderBy("seq", "asc")
                    ->get();
            }

            foreach ($generate_num_det as $tnd) {
                $trx_type = generate_num_type::find(
                    @$tnd["generate_num_type_id"]
                );

                if ($trx_type) {
                    if ($trx_type->ref_type === "text") {
                        // type text
                        $temporaryCode .= (string) $trx_type->value;
                    } elseif (
                        in_array($trx_type->ref_type, ["day", "month", "year"])
                    ) {
                        // type dating
                        $temporaryCode .= date($trx_type->value);
                    } elseif ($trx_type->ref_type === "seq") {
                        // type seq
                        $table = "generate_num";
                        $length = (int) $trx_type->value ?? 6;
                        $lastDataQuery = generate_num_log::where(
                            "nama",
                            @$generate_num->nama
                        )
                            ->where("table", $table)
                            ->orderBy("created_at", "DESC");

                        $latest = $lastDataQuery->pluck("seq")->first();

                        if (!$latest) {
                            $latest = "";

                            for ($i = 0; $i < $length; $i++) {
                                $latest .= "0";
                            }
                        }

                        $latest = sprintf("%0" . $length . "d", $latest + 1);
                        $temporaryCode .= $latest;

                        if ($counter && !$static) {
                            if ($lastDataQuery->exists()) {
                                generate_num_log::where("table", $table)
                                    ->where("nama", $generate_num->nama)
                                    ->update([
                                        "value" => $temporaryCode,
                                        "seq" => $latest,
                                    ]);
                            } else {
                                generate_num_log::create([
                                    "table" => $table,
                                    "nama" => $generate_num->nama,
                                    "value" => $temporaryCode,
                                    "seq" => $latest,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseCatch($e);
        }

        return $temporaryCode;
    }

    public function terbilang($x)
    {
        $angka = [
            "",
            "Satu",
            "Dua",
            "Tiga",
            "Empat",
            "Lima",
            "Enam",
            "Tujuh",
            "Delapan",
            "Sembilan",
            "Sepuluh",
            "Sebelas",
        ];

        if ($x < 12) {
            return " " . $angka[$x];
        } elseif ($x < 20) {
            return $this->terbilang($x - 10) . " Belas ";
        } elseif ($x < 100) {
            return $this->terbilang($x / 10) .
                " Puluh " .
                $this->terbilang($x % 10);
        } elseif ($x < 200) {
            return "Seratus" . $this->terbilang($x - 100);
        } elseif ($x < 1000) {
            return $this->terbilang($x / 100) .
                " Ratus" .
                $this->terbilang($x % 100);
        } elseif ($x < 2000) {
            return "Seribu" . $this->terbilang($x - 1000);
        } elseif ($x < 1000000) {
            return $this->terbilang($x / 1000) .
                " Ribu " .
                $this->terbilang($x % 1000);
        } elseif ($x < 1000000000) {
            return $this->terbilang($x / 1000000) .
                " Juta " .
                $this->terbilang($x % 1000000);
        }
    }

    public function responseValidate($validator)
    {
        $err = [];
        $errText = "";
        $error = $validator->messages()->toArray();

        foreach ($error as $key => $value) {
            $err[$key] = $value[0];
            if (count($error) > 1) {
                $errText .= $value[0] . "<br>";
            } else {
                $errText .= $value[0];
            }
        }

        $data = [
            "errors" => $err,
            "errorText" => $errText,
        ];

        return response(
            [
                "timestamp" => Carbon::now()->format("d-m-Y H:i:s"),
                "code" => 422,
                "message" => "Cek kembali form yang anda kirim.",
                "data" => $data,
            ],
            422
        );
    }

    public function customResponse(
        $message = "OK",
        $code = 200,
        $basic = null,
        $noData = true
    ) {
        if (!in_array($code, [200, 201])) {
            $err = [];
            $errText = "";
            $error = [$basic ?? $message];

            if (!$basic) {
                foreach ($error as $key => $value) {
                    $err[$key] = $value;
                    if ($key != 0) {
                        $errText .= $value . "<br>";
                    } else {
                        $errText .= $value;
                    }
                }

                $data = [
                    "errors" => $err,
                    "errorText" => $errText,
                ];
            } else {
                $data = $basic ?? [$message];
            }
        } else {
            if (!$noData) {
                $data = [
                    "data" => $basic ?? [$message],
                ];
            } else {
                $data = $basic ?? [$message];
            }
        }

        return response(
            [
                "timestamp" => Carbon::now()->format("d-m-Y H:i:s"),
                "code" => $code,
                "message" => $message,
                "data" => $data,
            ],
            $code
        );
    }

    public function responseCatch($e)
    {
        return response(
            [
                "timestamp" => Carbon::now()->format("d-m-Y H:i:s"),
                "code" => 400,
                "message" => $e->getMessage(),
                "data" => [
                    "errors" => [
                        $e->getMessage() .
                        "-" .
                        $e->getLine() .
                        "-" .
                        $e->getFile(),
                    ],
                    "errorText" => $e->getMessage(),
                ],
            ],
            400
        );
    }

    const STATUS_MENYETUJUI = "MENYETUJUI";
    const STATUS_PROGRESS = "PROGRESS";
    const STATUS_APPROVED = "APPROVED";
    const STATUS_REJECTED = "REJECTED";
    const STATUS_REVISED = "REVISED";

    protected function checkUserCanCreateTicket($m_approval)
    {
        $text = "Anda tidak memiliki akses untuk membuat tiket approval ini";
        $userAuth = auth()->user();
        $next = true;
        $kode = "";

        $fixedConfig = "select d.* from m_approval_det d join m_approval a on a.id = d.m_approval_id where a.id= ? AND tipe = 'MENGAJUKAN' ORDER BY d.level ASC";
        $details = DB::select($fixedConfig, [$m_approval->id]);

        if (!count($details)) {
            $text = "Setting approval tipe MENGAJUKAN tidak ditemukan";
            $next = false;
            $kode = "0000001";
        }

        $detail = $details[0];

        if ($detail->default_user_id && $detail->default_user_id !== $userAuth->id) {
            $text = "Anda tidak memiliki akses untuk membuat tiket approval ini";
            $next = false;
            $kode = "0000003";
        }

        if (!$next) {
            trigger_error($text . " | Warning Code : $kode");
        }
    }

    protected function checkUserCanApprove($approval_det)
    {
        $text = "Anda tidak memiliki akses untuk melanjutkan approval ini";
        $userAuth = auth()->user();
        $next = true;
        $kode = "";

        if ($approval_det->tipe !== self::STATUS_MENYETUJUI) {
            $next = false;
            $kode = "0000001";
        }

        $check = generate_approval::whereRaw("generate_approval.status = 'PROGRESS' 
                and generate_approval.id = $approval_det->generate_approval_id
                and case when generate_approval.last_approve_id is not null then
                    generate_approval.last_approve_id = $userAuth->id
                else
                    generate_approval.id in(select d.generate_approval_id from generate_approval_det d where 
                        d.m_role_id in(select r.m_role_id from m_role_access r where r.user_id = $userAuth->id)
                    )
                end
            ")->exists();
            
        if(!$check){
            $next = false;
            $kode = "0000002, $text";
        }

        $check_log = generate_approval_log::where('generate_approval_det_id', $approval_det->id)->exists();

        if ($check_log) {
            $next = false;
            $kode = "0000003, Approval sudah dilakukan sebelumnya";
        }

        if (!$next) {
            trigger_error($text . " | Warning Code : $kode");
        }
    }

    public function approvalCreateTicket(array $config, bool $errorOnFailed = false)
    {
        $app_name = @$config["app_name"];
        $trx_id = @$config["trx_id"];
        $trx_table = @$config["trx_table"];
        $trx_name = @$config["trx_name"];
        $form_name = @$config["form_name"];
        $trx_nomor = @$config["trx_nomor"];
        $trx_date = @$config["trx_date"];
        $trx_creator_id = @$config["trx_creator_id"];

        if (!$trx_table) {
            trigger_error("Config trx_table diperlukan");
        }

        if (!$trx_name) {
            trigger_error("Config trx_name diperlukan");
        }

        if (!$form_name) {
            trigger_error("Config form_name diperlukan");
        }

        if (!$trx_nomor) {
            trigger_error("Config trx_nomor diperlukan");
        }

        if (!$trx_date) {
            trigger_error("Config trx_date diperlukan");
        }

        if (!$trx_creator_id) {
            trigger_error("Config trx_creator_id diperlukan");
        }

        $m_approval = m_approval_det::join("m_approval as a", "a.id", "m_approval_det.m_approval_id")
            ->where("a.nama", $app_name)
            ->first();

        if (!$m_approval) {
            trigger_error("Maaf data approval $app_name tidak ditemukan");
        }

        $fixedConfig = "select d.* from m_approval_det d join m_approval a on a.id = d.m_approval_id where a.id= ? AND tipe<>'MENGAJUKAN' ORDER BY d.level ASC";
        $details = DB::select($fixedConfig, [$m_approval->m_approval_id]);

        $this->checkUserCanCreateTicket($m_approval);

        $check_approve_atasan = m_approval_det::join('m_role','m_role.id','m_approval_det.m_role_id')
            ->where('m_approval_id',$m_approval->id)
            ->where('level', 2)
            ->first();

        
        if(strtolower(@$check_approve_atasan->name) == 'atasan'){
            $atasan_id = m_kary::join('default_users as u','u.m_kary_id','m_kary.id')->where('u.id',auth()->user()->id)->pluck('atasan_id')->first() ?? 0;
            $user_atasan_id = default_users::whereRaw("default_users.m_kary_id = $atasan_id")->orderBy('id','asc')->pluck('id')->first();
        }

        $header = [
            "m_comp_id" => auth()->user()->m_comp_id,
            "m_dir_id" => auth()->user()->m_dir_id,
            "nomor" => $this->generateNomor('KODE APPROVAL'),
            "m_approval_id" => $m_approval->m_approval_id,
            "trx_id" => $trx_id,
            "trx_table" => $trx_table,
            "trx_name" => $trx_name,
            "form_name" => $form_name,
            "trx_nomor" => $trx_nomor,
            "trx_date" => $trx_date,
            "trx_creator_id" => $trx_creator_id,
            "creator_id" => auth()->user()->id,
            "status" => self::STATUS_PROGRESS,
            "last_approve_id" => @$user_atasan_id ?? null
        ];

        DB::beginTransaction();

        try {
            $g_app = generate_approval::create($header);

            foreach ($details as $idx => $d) {
                $d->generate_approval_id = $g_app->id;

                generate_approval_det::create([
                    "generate_approval_id" => $g_app->id,
                    "level" => $d->level,
                    "urutan_level" => $idx + 1,
                    "tipe" => $d->tipe,
                    "m_role_id" => $d->m_role_id,
                    "default_user_id" => $d->default_user_id,
                    "is_full_approve" => $d->is_full_approve,
                    "is_skippable" => $d->is_skippable,
                    "assigned_at" => $this->timestamp,
                    "creator_id" => auth()->user()->id,
                ]);
            }

            
            // insert log pengajuan
            $fixedConfigPengajuan = "select d.* from m_approval_det d 
                join m_approval a on a.id = d.m_approval_id 
                where a.id= ? AND tipe = 'MENGAJUKAN' ORDER BY d.level ASC";
            $pengajuan = DB::select($fixedConfigPengajuan, [ $m_approval->m_approval_id ]);
            if(count($pengajuan)){
                $log_insert = generate_approval_log::create([
                    'nomor'                     => $g_app->nomor,
                    'generate_approval_id'      => $g_app->id,
                    'generate_approval_det_id'  => null,
                    'trx_id'                    => $g_app->trx_id,
                    'trx_table'                 => $g_app->trx_table,
                    'trx_name'                  => $g_app->trx_name,
                    'trx_nomor'                 => $g_app->trx_nomor,
                    'trx_date'                  => $g_app->trx_date,
                    'form_name'                 => $g_app->form_name,
                    'trx_creator_id'            => $g_app->trx_creator_id,
                    'action_type'               => 'MENGAJUKAN',
                    'action_user_id'            => auth()->user()->id,
                    'creator_id'                => auth()->user()->id,
                    'action_at'                 => $this->timestamp,
                    'action_note'               => ''
                ]); 
            }

            //update last approver by detail approval
            $last_det_approver = generate_approval_det::where('generate_approval_id',$g_app->id)
                ->where('tipe','MENYETUJUI')
                ->where('is_done',false)
                ->orderBy('id','asc')->pluck('id')->first();
            generate_approval::where('id',$g_app->id)->update([
                'last_approve_det_id' => $last_det_approver 
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            if ($errorOnFailed) {
                trigger_error($e->getMessage());
            }
            return false;
        }
        return true;
    }

    public function approvalProgress(array $config, bool $errorOnFailed = false)
    {
        $app_id = $config["app_id"];
        $app_type = $config["app_type"];
        $app_note = $config["app_note"];

        $generate_approval = generate_approval::where('id', $app_id)->first();

        if (!$generate_approval) {
            trigger_error("Maaf data approval tidak ditemukan");
        }

        if (!$app_type) {
            trigger_error("Tipe approval diperlukan!");
        }

        if (!in_array($app_type, [self::STATUS_APPROVED, self::STATUS_REVISED, self::STATUS_REJECTED])) {
            trigger_error("Tipe approval tidak sesuai");
        }

        if (!$app_note) {
            trigger_error("Catatan approval diperlukan!");
        }

        $fixedConfig = "select d.* from generate_approval_det d join generate_approval a on a.id = d.generate_approval_id where a.id = ? and d.is_done = false order by d.urutan_level limit 1";
        $process_data = DB::select($fixedConfig, [$app_id]);

        if (!count($process_data)) {
            trigger_error("Maaf data approval tidak ditemukan");
        }

        $process_data = $process_data[0];
        $this->checkUserCanApprove($process_data);
        
        DB::beginTransaction();

        try {
            if ($process_data->is_full_approve) {
                $whereRawUpdate = "generate_approval_id = $generate_approval->id and urutan_level >= $process_data->urutan_level";
            } else {
                $whereRawUpdate = "generate_approval_id = $generate_approval->id and id = $process_data->id";
            }
            
            // Update ticket approval sesuai kondisi di atas
            $check = generate_approval_det::whereRaw($whereRawUpdate)->update([
                'action_at' => $this->timestamp,
                'action_type' => $app_type,
                'action_user_id' => auth()->user()->id,
                'action_note' => $app_note,
                'is_done' => true,
            ]);
            // Check approval level berikutnya
            $outstanding = generate_approval_det::where('generate_approval_id', $generate_approval->id)
                ->where('is_done', false)
                ->where('urutan_level', '>', $process_data->urutan_level)
                ->exists();
            
            // Jika masih ada outstanding, update waktu assign approval selanjutnya
            if ($outstanding && $app_type != 'REJECTED') {
                $finish = false;
                //update last approver by detail approval
               $last_det_approver = generate_approval_det::where('generate_approval_id',$generate_approval->id)
                ->where('tipe','MENYETUJUI')
                ->where('is_done',false)
                ->orderBy('id','asc')->pluck('id')->first();

                generate_approval::where('id',$generate_approval->id)->update([
                    'last_approve_det_id' => $last_det_approver
                ]);

                generate_approval_det::where('id',$last_det_approver)->update(['assigned_at' => $this->timestamp]);
                generate_approval::find($generate_approval->id)->update(['last_approve_id'=>null]);
            } else {
                $finish = true;

                // Jika tidak ada, update status header approval
                generate_approval::find($generate_approval->id)->update(['status' => $app_type, 'last_approve_id'=>null,'last_approve_det_id'=>null]);
            }
            $log_insert = generate_approval_log::create([
                'nomor'                     => $generate_approval->nomor,
                'generate_approval_id'      => $generate_approval->id,
                'generate_approval_det_id'  => @$proccess_data->id ?? 0,
                'trx_id'                    => $generate_approval->trx_id,
                'trx_table'                 => $generate_approval->trx_table,
                'trx_name'                  => $generate_approval->trx_name,
                'trx_nomor'                 => $generate_approval->trx_nomor,
                'trx_date'                  => $generate_approval->trx_date,
                'form_name'                 => $generate_approval->form_name,
                'trx_creator_id'            => $generate_approval->trx_creator_id,
                'action_type'               => $app_type,
                'action_user_id'            => auth()->user()->id,
                'action_at'                 => $this->timestamp,
                'action_note'               => $app_note
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            if ($errorOnFailed) {
                trigger_error($e->getMessage());
            }

            $app = new stdClass();
            $app->status = false;
            $app->trx_id = 0;

            return $app;
        }

        $app = new stdClass();
        $app->status = true;
        $app->finish = $finish;
        $app->trx_id = $generate_approval->trx_id;

        return $app;
    }
    
    public function approvalOustanding()
    {
        $userAuth = auth()->user();
        $model = new generate_approval;

        $data = generate_approval::selectRaw("generate_approval.*,(select u.name from default_users u where u.id = generate_approval.creator_id) creator")
            ->leftJoin('default_users', 'default_users.id', 'generate_approval.creator_id')
            ->whereRaw("generate_approval.status = 'PROGRESS' 
                and case when generate_approval.last_approve_id is not null then
                    generate_approval.last_approve_id = $userAuth->id
                else
                    generate_approval.id in(select d.generate_approval_id from generate_approval_det d where 
                        d.m_role_id in(select r.m_role_id from m_role_access r where r.user_id = $userAuth->id)
                           and d.is_done = false and d.id = generate_approval.last_approve_det_id
                    )
                end
            ")
            ->orderBy('generate_approval.id', 'desc')
            ->search(['trx_name', 'nomor', 'trx_date', 'trx_nomor', 'default_users.name'])
            ->paginate(app()->request->paginate ?? 50);

        return $data;
    }

    public function approvalDetail($id)
    {
        $app = DB::table('generate_approval')->selectRaw("*,(select u.name from default_users u where u.id = generate_approval.creator_id) creator")->where('id', $id)->first();

        if (!$app) {
            trigger_error("Approval tidak ditemukan");
        }

        $app->tahap_saat_ini = DB::table('generate_approval_det')->where('generate_approval_id', $app->id)->where('is_done', false)->orderBy('urutan_level', 'asc')->pluck('urutan_level')->first() ?? 0;
        $app->tahap_total = DB::table('generate_approval_det')->where('generate_approval_id', $app->id)->count();

        $trx = DB::table($app->trx_table)->where('id', $app->trx_id)->first();

        $data = new stdClass();
        $data->approval = $app;
        $data->approval_log = $this->approvalLog(['trx_id' => $app->trx_id, 'trx_table' => $app->trx_table]);
        $data->trx = $trx;

        return $data;
    }

    public function approvalLog($conf)
    {
        $trx_id = @$conf['trx_id'];
        $trx_table = @$conf['trx_table'];

        $data = generate_approval_log::selectRaw("*,(select u.name from default_users u where u.id = generate_approval_log.action_user_id) action_user")
            ->where('trx_table', $trx_table)
            ->where('trx_id', $trx_id)
            ->orderBy('action_at', 'asc')
            ->get();

        return $data;
    }

    function snakeCaseToCapitalize($str)
    {
        $words = explode('_', $str);
        $capitalizedWords = array_map('ucfirst', $words);
        $result = implode(' ', $capitalizedWords);

        return $result;
    }

}
