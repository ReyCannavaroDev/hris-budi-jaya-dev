@php
  $req = app()->request;
  $tipe = $req->tipe_report;
  $rekap = [];
  $today = \Carbon::today();
  $data = \DB::select("
            SELECT json_agg(json_build_object(
                'm_kary_id', m_kary_id,
                'default_user_id', default_user_id,
                'kode', kode,
                'nama_lengkap', nama_lengkap,
                'dept', dept,
                'absensi', absensi
            )) AS att_report
            FROM get_employee_attendance_report(?,?,?)",[$req->date ?? $today,$req->divisi_id,$req->dept_id]);

  $filteredData = collect(json_decode($data[0]->att_report))->filter(function ($item) {
      return $item->absensi->status === 'NOT ATTEND';
  });
  $dateFormat = \Carbon::parse($req->date)->format('d F Y');
@endphp
<table style="font-size: 10pt; font-weight: bold;">
    <tr>
        <td style="font-size: 10pt;">{{$tipe}}</td>
    </tr>
    <tr>
        <td style="font-size: 9pt;">Tanggal - {{$dateFormat}}</td>
    </tr>
</table>


<table v-else class="table-auto w-full" cellpadding="3">
  <thead class="bg-[#c6c6c6]">
    <tr>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6; width: 25%;">Karyawan</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6; width: 27%;">Departemen</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; background-color: #c6c6c6; width: 27%;">Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($filteredData as $dt)
        <tr>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse;">{{ $dt->kode }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; width: 25%;">{{ $dt->nama_lengkap }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; width: 27%;">{{ $dt->dept }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 9pt; border-collapse: collapse; width: 27%;">{{ $dt->absensi->status }}</td>
        </tr>
    @endforeach
  </tbody>
</table>
