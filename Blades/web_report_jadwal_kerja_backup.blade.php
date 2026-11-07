@php
  $req = app()->request;
  $tipe = $req->tipe_report;

  $detail_hari = \DB::select("select h.day_num, h.day,h.tipe_hari, h.waktu_mulai, h.waktu_akhir  from t_jadwal_kerja t 
    join m_general g on t.tipe_jam_kerja_id = g.id 
    join t_jadwal_kerja_det_hari h on h.t_jadwal_kerja_id = t.id
    where lower(g.value) = 'office' and t.status = 'POSTED' order by h.day_num asc");
  
@endphp
<span style="font-weight:bold; font-size: 10pt">{{$tipe}}</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> HK : Hari Kerja</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> H : Masuk Kerja</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> I : Ijin</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> A : Tidak Masuk Kerja / Tanpa Keterangan</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> CI AVG : Rata-rata Jam Checkin</span><br/>
<span style="font-weight:bold; font-style: italic; font-size: 8pt"> CO AVG : Rata-rata Jam Checkout</span><br/>
<br/>
<table v-else class="table-auto w-full" cellpadding="3">
  <thead class="bg-[#c6c6c6]">
    <tr style="text-align: center;">
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6; text-align: center; width: 3%;" rowspan="2">
        <div style="vertical-align: middle;">No
        </div>
        </th>
      @foreach($detail_hari as $d)
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;" colspan="2">Senin 08:00 - 17.00</th>
      @endforeach
    </tr>
    <tr style="text-align: center;">
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">Nama</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">Nama</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">Nama</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">Nama</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">Nama</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">Nama</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">NIK</th>
      <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6;">Nama</th>
    </tr>
  </thead>
  <tbody>
        <tr>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; width: 3%;">1</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">3578133394923</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">Arjuna Radja Samudra Hindia</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">3578133394923</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">Arjuna Radja Samudra Hindia</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">3578133394923</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">Arjuna Radja Samudra Hindia</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">3578133394923</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">Arjuna Radja Samudra Hindia</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">3578133394923</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">Arjuna Radja Samudra Hindia</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">3578133394923</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">Arjuna Radja Samudra Hindia</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">3578133394923</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse;">Arjuna Radja Samudra Hindia</td>
        </tr>
  </tbody>
</table>
