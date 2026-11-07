@php
  $req = app()->request;
  $tipe_jam_kerja_id = $req->tipe_jam_kerja_id;

  $tipe_jam_kerja_value = \DB::select("select g.code from m_general g where g.id = ?", [$tipe_jam_kerja_id]);
  $tipe_jam_kerja_value = count($tipe_jam_kerja_value) ? $tipe_jam_kerja_value[0]->code : null;

  $jadwal_kerja_posted = \DB::select("select t.keterangan, t.id from t_jadwal_kerja t 
    join m_general g on t.tipe_jam_kerja_id = g.id 
    where g.id = ? and t.status = 'POSTED' order by t.id asc", [$tipe_jam_kerja_id]);

  foreach($jadwal_kerja_posted as $dt){
    if(strtolower($tipe_jam_kerja_value) == 'office'){
      $detail_hari = \DB::select("select h.id, h.day_num, h.day,h.tipe_hari, h.waktu_mulai, h.waktu_akhir  from t_jadwal_kerja t 
        join m_general g on t.tipe_jam_kerja_id = g.id 
        join t_jadwal_kerja_det_hari h on h.t_jadwal_kerja_id = t.id
        where g.id = ? and t.status = 'POSTED' and t.id = ? order by h.day_num asc",  [$tipe_jam_kerja_id,$dt->id]);
    }else{
      $detail_hari = \DB::select("select h.id, h.day_num, h.day,h.tipe_hari, h.waktu_mulai, h.waktu_akhir  from t_jadwal_kerja t 
        join m_general g on t.tipe_jam_kerja_id = g.id 
        join t_jadwal_kerja_det_hari h on h.t_jadwal_kerja_id = t.id
        where g.id = ? and t.status = 'POSTED' and t.id = ? order by h.day_num asc",  [$tipe_jam_kerja_id,$dt->id]);
    }
    $dt->detail = $detail_hari;

    // karyawan 
    $tambahan_where = "";
    if(strtolower($tipe_jam_kerja_value) != 'office'){
      $tambahan_where = " and k.id in(select d.m_kary_id from t_jadwal_kerja_det d where d.t_jadwal_kerja_id = $dt->id)";
    }

    $m_kary = \DB::select("
      select
        k.id, kode, nama_lengkap, d.nama dept 
      from m_kary k
      join default_users u on u.m_kary_id = k.id
      join m_dept d on d.id = k.m_dept_id
          where k.is_active = true 
          and k.m_dept_id IS NOT NULL and k.m_dept_id != 0 $tambahan_where");

    $dt->m_kary = $m_kary;
  }
  
@endphp
<p style="font-weight:bold; font-size: 12pt">Jadwal Kerja Karyawan</p>
@if(!count(@$detail_hari ?? []))
  <i>Tidak ditemukan data jadwal kerja terkait</i>
@else
  @foreach($jadwal_kerja_posted as $t)
    <p style="font-weight: bold; font-size: 10px">{{ $t->keterangan }}</p>
    <table style="width: 100%" cellpadding="2">
      <thead class="bg-[#c6c6c6]">
        <tr style="text-align: center;">
          <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6; text-align: center; width:2%" rowspan="2">
            No
          </th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6; text-align: center; width:7%" rowspan="2">
            NIK
          </th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6; text-align: center; width:15%" rowspan="2">
            Karyawan
          </th>
          <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6; text-align: center; width:15%;" rowspan="2">
            Departemen
          </th>
          @foreach($t->detail as $d)
          <th style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; background-color: #c6c6c6; width:8%;">{{ $d->day }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($t->m_kary as $idx => $d)
        <tr>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; width:2%">{{ $idx+1 }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; width:7%">{{ $d->kode }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; width:15%">{{ $d->nama_lengkap }}</td>
          <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; width:15%">{{ $d->dept }}</td>
          @foreach($t->detail as $dt)
            @php
              $check = \DB::table('t_jadwal_kerja_det')->where('m_kary_id', $d->id)->where('t_jadwal_kerja_det_hari_id', $dt->id)->where('t_jadwal_kerja_id', $t->id)->exists();
            @endphp
            @if(strtolower($tipe_jam_kerja_value) == 'office')
              <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; text-align: center; width:8%">{{ 
                $dt->tipe_hari == 'KERJA' ? ($dt->waktu_mulai.'-'.$dt->waktu_akhir ) : 'OFF'
              }}</td>
            @else
              <td style="border:0.5px solid black; padding: 2px; font-size: 6.5pt; border-collapse: collapse; text-align: center; width:8%">{{ 
                $dt->tipe_hari == 'KERJA' && ($check && strtolower($tipe_jam_kerja_value) != 'office') ? ($dt->waktu_mulai.'-'.$dt->waktu_akhir ) : 'OFF'
              }}</td>
            @endif
          @endforeach
        </tr>
        @endforeach
      </tbody>
    </table>
  @endforeach
@endif
