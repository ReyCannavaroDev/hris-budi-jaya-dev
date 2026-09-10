@php
  $req = app()->request;
  $tipe = $req->tipe_report;
  $divisi = $req->m_divisi_id;
  $dept = $req->m_dept_id;
  $date_start = $req->date_start;
  $date_end = $req->date_end;

  $getKary = \DB::table('m_kary')
  ->join('m_divisi','m_kary.m_divisi_id','m_divisi.id')
  ->join('m_dept','m_kary.m_dept_id','m_dept.id')
  ->select('m_kary.*','m_divisi.nama as nama_divisi', 'm_dept.nama as nama_dept','m_kary.is_active')
  ->where('m_kary.m_divisi_id',$divisi)->where('m_kary.is_active', true);

  if($dept){
    $getKary = $getKary->where('m_kary.m_dept_id',$dept);
  }     

  $getKary = $getKary->get();

  $divisi = $getKary->isNotEmpty() ? $getKary[0]->nama_divisi : 'Unknown Division';
  $dept = $getKary->isNotEmpty() ? $getKary[0]->nama_dept : '-';
@endphp

<span style="font-weight:bold; font-size: 10pt"> Absensi Karyawan Divisi {{$divisi}} Departemen {{$dept}}</span>
<table style="width: 100%; font-size: 7pt" cellpadding="2">

@foreach($getKary as $single)
  @php
  $rekap = [];
  $dateNow = date('Y-m-d');
  $data = \DB::select("
     select * from employee_attendance_detail_range(?,?,?)
    ",[$date_start, $date_end, $single->id]);
  
  $kary_id = @json_decode(@$data[0]->kary)->m_kary_id ?? 0;
  $check_kary_jam_kerja_tipe = \DB::table('m_kary as k')->join('m_general as g','g.id','k.tipe_jam_kerja_id')
    ->where('k.id', $kary_id)->pluck('g.code')->first();
  
  $rekap = \DB::select("
    WITH attendance_avg AS (
        SELECT 
            pa.default_user_id,
            TO_CHAR(INTERVAL '1 second' * AVG(EXTRACT(EPOCH FROM pa.checkin_time::TIME)) 
                FILTER (WHERE pa.checkin_time IS NOT NULL), 'HH24:MI:SS') AS checkin_avg,
            TO_CHAR(INTERVAL '1 second' * AVG(EXTRACT(EPOCH FROM pa.checkout_time::TIME)) 
                FILTER (WHERE pa.checkout_time IS NOT NULL), 'HH24:MI:SS') AS checkout_avg
        FROM presensi_absensi pa
        WHERE pa.tanggal BETWEEN ? AND ?  -- Filter based on date_start and date_end
        GROUP BY pa.default_user_id
    )
    SELECT 
        employee_attendance_range(?, ?, k.id, ?) AS absen,
        COALESCE(a.checkin_avg, '00:00:00') AS checkin_avg,  -- Default to '00:00:00' if no data
        COALESCE(a.checkout_avg, '00:00:00') AS checkout_avg,
        k.id, k.kode, k.nama_lengkap, d.nama AS dept
    FROM m_kary k
    JOIN default_users u ON u.m_kary_id = k.id
    JOIN m_dept d ON d.id = k.m_dept_id
    LEFT JOIN attendance_avg a ON a.default_user_id = u.id
    WHERE k.is_active = TRUE 
        AND k.m_dept_id IS NOT NULL 
        AND k.m_dept_id != 0
        AND k.id = COALESCE(?, k.id)
  ", [ $date_start, $date_end, $date_start, $date_end, date('Y-m-d'), $kary_id ]);

  $total_checkin_telat = 0;
  $total_checkout_lebih_awal = 0;
  $total_checkin_lebih_awal = 0;
  $total_checkout_telat = 0;
  @endphp
<table  style="width: 100%; margin-bottom: 10px;">
  <tr>
    <td colspan="6" style="font-weight: bold; font-size: 10pt;">Absensi Karyawan Detail</td>
  </tr>
  <tr>
    <td colspan="6" style="font-weight: bold; font-size: 12pt;">
      {{ @json_decode(@$data[0]->kary)->nik }} - {{ @json_decode(@$data[0]->kary)->nama_lengkap }}
    </td>
  </tr>
  <tr>
    <td colspan="6" style="font-weight: bold; font-size: 7pt; padding-top: 10px;">Periode {{$date_start}} - {{$date_end}}</td>
  </tr>
</table>


<br/>

<table style="width: 100%; font-size: 7pt" cellpadding="2">
  <thead class="bg-[#c6c6c6]">
    <tr>
      <th  style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; background-color: #c6c6c6; width: 15%;"colspan="4">Tanggal</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; background-color: #c6c6c6; width: 6%; " colspan="3">Tipe Hari</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; background-color: #c6c6c6; width: 8%; text-align: center;" colspan="2">Status</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; background-color: #c6c6c6; width: 20%; text-align: center;" colspan="4">Checkin Time</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; background-color: #c6c6c6; width: 20%; text-align: center;" colspan="4">Checkout Time</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $dt)
        @php
          if(strtolower($check_kary_jam_kerja_tipe) == 'office'){
            $jadwal = \DB::table('t_jadwal_kerja as t')
              ->join('t_jadwal_kerja_det_hari as h','h.t_jadwal_kerja_id','t.id')
              ->where('t.status','POSTED')->whereRaw("lower(h.day) = ?", [strtolower($dt->day_name_idn)])
              ->first();
          }else{
            $jadwal = \DB::table('t_jadwal_kerja as t')
              ->join('t_jadwal_kerja_det_hari as h','h.t_jadwal_kerja_id','t.id')
              ->join('t_jadwal_kerja_det as d','d.t_jadwal_kerja_det_hari_id','h.id')
              ->where('t.status','POSTED')
              ->whereRaw("lower(h.day) = ? and d.m_kary_id = ?", [ strtolower($dt->day_name_idn) , $kary_id ])
              ->first();
          }
          $waktu_mulai = strtotime(@$jadwal->waktu_mulai);
          $waktu_checkin = strtotime(@json_decode($dt->absensi)->checkin_time);


          // HITUNG TELAT CHECKIN
          $checkin_result = @json_decode($dt->absensi)->checkin_time;
          if(@json_decode($dt->absensi)->checkin_time != null && @$jadwal->waktu_mulai){
            // Menghitung selisih waktu dalam detik
            $selisih_detik = $waktu_mulai - $waktu_checkin;

            // Mengonversi selisih detik menjadi menit
            $late = abs(round($selisih_detik / 60));
            
            if(($waktu_mulai < $waktu_checkin)){
              $checkin_result = @json_decode($dt->absensi)->checkin_time . ' / '.@$jadwal->waktu_mulai.'  <span style="color: red">('.$late.' Menit )</span>'; 
              $total_checkin_telat += $late;
            }else{
              $checkin_result = @json_decode($dt->absensi)->checkin_time .(@$jadwal->waktu_mulai ?  ' / '. @$jadwal->waktu_mulai : '');
              $total_checkin_lebih_awal += $late;
            }
          }

          // HITUNG TELAT CHECKOUT
          $checkout_result = @json_decode($dt->absensi)->checkout_time;
          $waktu_akhir = strtotime(@$jadwal->waktu_akhir);
          $waktu_checkout = strtotime(@json_decode($dt->absensi)->checkout_time);

          if(@json_decode($dt->absensi)->checkout_time != null && @$jadwal->waktu_akhir){
            // Menghitung selisih waktu dalam detik
            $selisih_detik = $waktu_akhir - $waktu_checkout;

            // Mengonversi selisih detik menjadi menit
            $late = abs(round($selisih_detik / 60));

            if(($waktu_akhir > $waktu_checkout)){
              $checkout_result = @json_decode($dt->absensi)->checkout_time . ' / '.@$jadwal->waktu_akhir.'  <span style="color: red">('.$late.' Menit )</span>'; 
              $total_checkout_lebih_awal += $late;
            }else{
              $total_checkout_telat += $late;
              $checkout_result = @json_decode($dt->absensi)->checkout_time .(@$jadwal->waktu_akhir ?  ' / '. @$jadwal->waktu_akhir : '');
            }
          }
        @endphp
        <tr>
          <td colspan="4" style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; width: 15%;">{{ $dt->day_name_idn }}, {{$dt->date_to_idn}}</td>
          <td colspan="3" style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; width: 6%;">{{ $dt->type }}</td>
          <td  colspan="2" style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; width: 8%; text-align: center;">{{ @json_decode($dt->absensi)->status }}</td>
          <td colspan="4" style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; width: 20%; text-align: center;">
            {!! $checkin_result !!}
          </td>
          <td colspan="4" style="border:0.5px solid black; padding: 2px; font-size: 7pt; border-collapse: collapse; width: 20%; text-align: center;">
            {!! $checkout_result !!}
          </td>
        </tr>
    @endforeach
  </tbody>
</table>
<br/>
<table style="width: 100%; font-size: 7pt;">
  <tbody>
    <tr>
      <td style="color: red;">Total Checkin Telat</td>
      <td>:</td>
      <td>{{ round($total_checkin_telat / 60) . ' Jam (' . $total_checkin_telat . ' Menit)' }}</td>
      <td></td>
      <td>Hari Kerja</td>
      <td>:</td>
      <td>{{ json_decode(@$rekap[0]->absen)->work_days_in_month ?? '-' }}</td>
    </tr>
    <tr>
      <td style="color: red;">Total Checkout Lebih Awal</td>
      <td>:</td>
      <td>{{ round($total_checkout_lebih_awal / 60) . ' Jam (' . $total_checkout_lebih_awal . ' Menit)' }}</td>
      <td></td>
      <td>Hadir</td>
      <td>:</td>
      <td>{{ json_decode(@$rekap[0]->absen)->work_present ?? '-' }}</td>
    </tr>
    <tr>
      <td>Total Checkin Lebih Awal</td>
      <td>:</td>
      <td>{{ round($total_checkin_lebih_awal / 60) . ' Jam (' . $total_checkin_lebih_awal . ' Menit)' }}</td>
      <td></td>
      <td>Ijin / Cuti</td>
      <td>:</td>
      <td>{{ json_decode(@$rekap[0]->absen)->cuti_terpakai ?? '-' }}</td>
    </tr>
    <tr>
      <td>Total Checkout Telat</td>
      <td>:</td>
      <td>{{ round($total_checkout_telat / 60) . ' Jam (' . $total_checkout_telat . ' Menit)' }}</td>
      <td></td>
      <td>Alpha</td>
      <td>:</td>
      <td>{{ json_decode(@$rekap[0]->absen)->work_not_present ?? '-' }}</td>
    </tr>
    <tr>
      <td>Rata-rata Jam Checkin</td>
      <td>:</td>
      <td>{{ @$rekap[0]->checkin_avg ?? '-' }}</td>
    </tr>
    <tr>
      <td>Rata-rata Jam Checkout</td>
      <td>:</td>
      <td>{{ @$rekap[0]->checkout_avg ?? '-' }}</td>
    </tr>
  </tbody>
</table>
<br/>
@endforeach