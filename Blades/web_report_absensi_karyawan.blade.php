@php
  $req = app()->request;
  $tipe = $req->tipe_report;

  $rekap = [];
  $periode = $req->periode.'-01';
  if($tipe === 'Rekap'){
    $data = \DB::select("
      select 
        employee_attendance(?,k.id) absen,
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
            $periode, $req->m_divisi_id, $req->m_dept_id, $req->kary_id
          ]);
  }
  
@endphp
<span style="font-weight:bold; font-size: 10pt"> Absensi Karyawan - {{$tipe}}</span><br/>
<span style="font-weight:bold; font-size: 9pt"> Periode  {{$req->periode}}</span><br/></br>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> HK : Hari Kerja</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> H : Masuk Kerja</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> I : Ijin</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> A : Tidak Masuk Kerja / Tanpa Keterangan</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> CI AVG : Rata-rata Jam Checkin</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> CO AVG : Rata-rata Jam Checkout</span><br/>
<br/>
  @if($tipe === 'Rekap')
    <table v-else class="table-auto w-full">
      <thead class="bg-[#c6c6c6]">
        <tr>
          <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">Karyawan</th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">Departemen</th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">HK</th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">H</th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">I</th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">A</th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">CI AVG</th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">CO AVG</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $dt)
            <tr>
              <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ $dt->kode }}</td>
              <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ $dt->nama_lengkap }}</td>
              <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ $dt->dept }}</td>
              <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ @json_decode($dt->absen)->work_days_in_month }}</td>
              <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ @json_decode($dt->absen)->work_present }}</td>
              <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ @json_decode($dt->absen)->cuti_terpakai }}</td>
              <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ @json_decode($dt->absen)->work_not_present }}</td>
              <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ $dt->checkin_avg }}</td>
              <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ $dt->checkout_avg }}</td>
            </tr>
        @endforeach
      </tbody>
    </table>
  @endif
