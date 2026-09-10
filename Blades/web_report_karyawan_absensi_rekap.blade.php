@php
  $req = app()->request;
  $tipe = $req->tipe_report;

  $rekap = [];
  $periode = $req->periode.'-01';
  $dateNow = date('Y-m-d');
  $data = \DB::select("
    select 
      employee_attendance(?,k.id, ?) absen,
      (select   
        TO_CHAR(INTERVAL '1 second' * AVG(EXTRACT(EPOCH FROM pa.checkin_time::TIME)), 'HH24:MI:SS')
        from presensi_absensi pa where pa.default_user_id = u.id) checkin_avg,
      (select   
        TO_CHAR(INTERVAL '1 second' * AVG(EXTRACT(EPOCH FROM pa.checkout_time::TIME)), 'HH24:MI:SS')
        from presensi_absensi pa where pa.default_user_id = u.id) checkout_avg,
      k.id, kode, nama_lengkap, d.nama dept 
    from m_kary k
    join default_users u on u.m_kary_id = k.id
    join m_dept d on d.id = k.m_dept_id
        where k.is_active = true 
        and k.m_dept_id IS NOT NULL and k.m_dept_id != 0
        and k.m_divisi_id = COALESCE(?, k.m_divisi_id) 
        and k.m_dept_id = COALESCE(?, k.m_dept_id)
        and k.id = COALESCE(?, k.id)
        ",[
          $periode, $dateNow, $req->m_divisi_id, $req->m_dept_id, $req->kary_id
        ]);
  
@endphp
<table style="font-size: 10pt; font-weight: bold;">
    <tr>
        <td style="font-size: 10pt;">{{$tipe}}</td>
    </tr>
    <tr>
        <td style="font-size: 9pt;">Periode {{$req->periode}}</td>
    </tr>
</table>

<table style="font-size: 8pt; font-weight: bold;">
    <tr>
        <td style="font-style: italic;">HK</td>
        <td>: Hari Kerja</td>
    </tr>
    <tr>
        <td style="font-style: italic;">H</td>
        <td>: Masuk Kerja</td>
    </tr>
    <tr>
        <td style="font-style: italic;">I</td>
        <td>: Ijin</td>
    </tr>
    <tr>
        <td style="font-style: italic;">A</td>
        <td>: Tidak Masuk Kerja / Tanpa Keterangan</td>
    </tr>
    <tr>
        <td style="font-style: italic;">CI AVG</td>
        <td>: Rata-rata Jam Checkin</td>
    </tr>
    <tr>
        <td style="font-style: italic;">CO AVG</td>
        <td>: Rata-rata Jam Checkout</td>
    </tr>
</table>

<table v-else class="table-auto w-full" cellpadding="3">
  <thead class="bg-[#c6c6c6]">
    <tr>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6; width: 25%;">Karyawan</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6; width: 27%;">Departemen</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6; width: 3.5%;">HK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6; width: 3.5%;">H</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6; width: 3.5%;">I</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6; width: 3.5%;">A</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">CI AVG</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">CO AVG</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $dt)
        <tr>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ $dt->kode }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; width: 25%;">{{ $dt->nama_lengkap }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; width: 27%;">{{ $dt->dept }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; text-align: right; width: 3.5%">{{ @json_decode($dt->absen)->work_days_in_month }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; text-align: right; width: 3.5%;">{{ @json_decode($dt->absen)->work_present }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; text-align: right; width: 3.5%;">{{ @json_decode($dt->absen)->cuti_terpakai }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; text-align: right; width: 3.5%;">{{ @json_decode($dt->absen)->work_not_present }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; text-align: right;">{{ $dt->checkin_avg }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; text-align: right;">{{ $dt->checkout_avg }}</td>
        </tr>
    @endforeach
  </tbody>
</table>
