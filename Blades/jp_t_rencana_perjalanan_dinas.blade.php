@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px] border-t-10 border-gray-500">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </template>
  </TableApi>
</div>
@else

@verbatim

<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <!-- HEADER START -->
      <div class="bg-gray-500 text-white rounded-t-md py-2 px-4  p-4">
        <div class="flex items-center">
          <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
            @click="onBack" />
          <div>
            <h2 v-if="!is_approval" class="font-sans text-xl flex justify-left font-bold">
              {{actionText==='Edit'?'Ubah':actionText}} Rencana Perjalanan Dinas
            </h2>
            <h2 v-else class="font-sans text-xl flex justify-left font-bold">
              Notifikasi Approval Rencana Perjalanan Dinas
            </h2>
          </div>
        </div>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
        <!-- START COLUMN -->
        <div>
          <FieldX :bind="{ readonly: true }" type="text" :value="values.nomor" class="w-full mt-3"
            @input="v=>values.nomor=v" :check="false" placeholder="Masukan Nomor" label="Nomor" />
        </div>
        <div>
          <FieldPopup :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.m_spd_id"
            @input="(v)=>values.m_spd_id=v" :errorText="formErrors.m_spd_id?'failed':''" :hints="formErrors.m_spd_id"
            @update:valueFull="(objVal)=>{
                if(objVal){
                  values.m_dir_id = objVal.m_dir_id
                  values.m_divisi_id = objVal.m_divisi_id
                  values.m_dept_id = objVal.m_dept_id
                  values.m_posisi_id = objVal.m_posisi_id
                  values.m_zona_asal_id = objVal.m_zona_id
                  values.m_zona_tujuan_id = objVal.m_zona_id
                  getDetailSPD()
                }else if(objVal === null){
                  values.m_dir_id = null
                  values.m_divisi_id = null
                  values.m_dept_id = null
                  values.m_posisi_id = null
                  values.m_zona_asal_id = null
                  values.total_biaya= null
                  values.m_zona_tujuan_id = null
                  detailArr = []
                }
                }" valueField="id" displayField="kode" :api="{
                url: `${store.server.url_backend}/operation/m_spd`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  join:true,
                  searchfield: `this.id, this.kode,m_dir.nama,m_divisi.nama,m_dept.nama,m_posisi.desc_kerja,desc`,
                }
              }" placeholder="Pilih template SPD" label="Template SPD" :check="false" :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'kode',
                wrapText: true,
                headerName: 'Nomor',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_dir.nama',
                headerName: 'Direktorat',
                wrapText: true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_divisi.nama',
                headerName: 'Divisi',
                wrapText: true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_dept.nama',
                wrapText: true,
                headerName: 'Departemen',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_posisi.desc_kerja',
                headerName: 'Posisi', wrapText: true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'desc',
                headerName: 'Keterangan',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              ]" />
        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.m_divisi_id" @input="v=>values.m_divisi_id=v" :errorText="formErrors.m_divisi_id?'failed':''"
            @update:valueFull="(objVal)=>{
                  values.m_dept_id = null
                }" label="Divisi" placeholder="Pilih Divisi" :hints="formErrors.m_divisi_id" :api="{
                  url: `${store.server.url_backend}/operation/m_divisi`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    where: `this.is_active='true'`
                  }
              }" valueField="id" displayField="nama" :check="false" />
        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" :value="values.m_dept_id"
            @input="v=>values.m_dept_id=v" :errorText="formErrors.m_dept_id?'failed':''" label="Departemen"
            placeholder="Pilih Departemen" :hints="formErrors.m_dept_id" :api="{
                  url: `${store.server.url_backend}/operation/m_dept`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    where: `m_divisi_id=${values.m_divisi_id ?? 0} AND this.is_active='true'` 
                  }
              }" valueField="id" displayField="nama" :check="false" />
        </div>

        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.m_posisi_id" @input="v=>values.m_posisi_id=v" label="Posisi" placeholder="Pilih Posisi"
            :errorText="formErrors.m_posisi_id?'failed':''" :hints="formErrors.m_posisi_id" :api="{
                  url: `${store.server.url_backend}/operation/m_posisi`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    where: `this.is_active='true'`,
                  }
              }" valueField="id" displayField="desc_kerja" :check="false" />
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" type="date" class="w-full mt-3"
            :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v"
            :hints="formErrors.tanggal" :check="false" label="Tanggal" placeholder="Tanggal Pengajuan" />
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" type="date" @update="hitungHari()"
            class="w-full mt-3" :value="values.tgl_acara_awal"
            :errorText="formErrors.tgl_acara_awal?'failed':''" @input="v=>{
                values.tgl_acara_awal=v
                hitungHari()
              }" :hints="formErrors.tgl_acara_awal" :check="false" label="Tanggal acara awal"
            placeholder="Pilih tanggal acara awal" />
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" type="date" class="w-full mt-3"
            :value="values.tgl_acara_akhir" :errorText="formErrors.tgl_acara_akhir?'failed':''" @input="v=>{
                values.tgl_acara_akhir=v
                hitungHari()
                }" :hints="formErrors.tgl_acara_akhir" :check="false" label="Tanggal akhir acara"
            placeholder=" Pilih tanggal akhir acara" />
        </div>

        <div>
          <FieldX :bind="{ readonly: true }" class="w-full mt-3" :value="values.jml_hari"
            :errorText="formErrors.jml_hari?'failed':''" @input="v=>values.jml_hari=v" :hints="formErrors.jml_hari"
            :check="false" label="Jumlah Hari" placeholder="Masukan Jumlah Hari" />
        </div>

        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.jenis_spd_id" @input="v=>values.jenis_spd_id=v" label="Jenis SPD"
            placeholder="Pilih Jenis SPD" :errorText="formErrors.jenis_spd_id?'failed':''"
            :hints="formErrors.jenis_spd_id" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    where: `this.group='JENIS SPD' AND this.is_active='true'`,
                  }
              }" valueField="id" displayField="value" :check="false" />
        </div>

        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.m_zona_asal_id" @input="v=>values.m_zona_asal_id=v" label="Zona Asal"
            placeholder="Pilih Zona Asal" :errorText="formErrors.m_zona_asal_id?'failed':''"
            :hints="formErrors.m_zona_asal_id" :api="{
                  url: `${store.server.url_backend}/operation/m_zona`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    where: `m_zona.is_active='true'`,
                    single:true
                  }
              }" valueField="id" displayField="nama" :check="false" />
        </div>

        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.m_zona_tujuan_id" @input="v=>values.m_zona_tujuan_id=v" label="Zona Tujuan"
            placeholder="Pilih Zona Tujuan" :errorText="formErrors.m_zona_tujuan_id?'failed':''"
            :hints="formErrors.m_zona_tujuan_id" :api="{
                  url: `${store.server.url_backend}/operation/m_zona`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    where: `m_zona.is_active='true'`,
                    single:true
                  }
              }" valueField="id" displayField="nama" :check="false" />
        </div>

        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.m_lokasi_tujuan_id" @input="v=>values.m_lokasi_tujuan_id=v" label="Zona Tujuan"
            placeholder="Pilih Zona Tujuan" :errorText="formErrors.m_lokasi_tujuan_id?'failed':''"
            :hints="formErrors.m_lokasi_tujuan_id" :api="{
                  url: `${store.server.url_backend}/operation/m_lokasi`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    where: `this.is_active='true'`,
                  }
              }" valueField="id" displayField="nama" :check="false" />
        </div>

        <div>
          <FieldPopup :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.m_kary_id"
            @input="(v)=>values.m_kary_id=v" :errorText="formErrors.m_kary_id?'failed':''" :hints="formErrors.m_kary_id"
            @update:valueFull="(objVal)=>{
                if(objVal){
                  values.pic_id = objVal.id
                }else{
                  values.pic_id = null
                }
                }" valueField="id" displayField="nik" :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.m_posisi_id=${values.m_posisi_id ?? 0}`,
                  searchfield: 'this.nik, this.nama_lengkap, this.nama_depan, this.nama_belakang, m_zona.nama, m_dir.nama, m_divisi.nama, m_dept.nama'
                }
              }" placeholder="Cari Nomor Induk Karyawan" label="NIK" :check="false" :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'nik',
                wrapText:true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-end']
              },
              {
                flex: 1,
                field: 'nama_lengkap',
                wrapText:true,
                headerName: 'Nama Karyawan',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_zona.nama',
                wrapText:true,
                headerName: 'Zona',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'm_dir.nama',
                headerName: 'Direktorat',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'm_divisi.nama',
                headerName: 'Divisi',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'm_dept.nama',
                headerName: 'Departemen',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              ]" />
        </div>

        <div>
          <FieldPopup :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.pic_id"
            @input="(v)=>values.pic_id=v" :errorText="formErrors.pic_id?'failed':''" :hints="formErrors.pic_id"
            @update:valueFull="(objVal)=>{
                }" valueField="id" displayField="nama_lengkap" :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.m_posisi_id=${values.m_posisi_id ?? 0}`,
                  searchfield: 'this.nik, this.nama_lengkap, this.nama_depan, this.nama_belakang, m_zona.nama, m_dir.nama, m_divisi.nama, m_dept.nama'
                }
              }" placeholder="Cari Nama Karyawan" label="Nama Karyawan" :check="false" :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'nik',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-end']
              },
              {
                flex: 1,
                field: 'nama_lengkap',
                wrapText:true,
                headerName: 'Nama Karyawan',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_zona.nama',
                wrapText:true,
                headerName: 'Zona',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_dir.nama',
                wrapText:true,
                headerName: 'Direktorat',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_divisi.nama',
                wrapText:true,
                headerName: 'Divisi',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_dept.nama',
                wrapText:true,
                headerName: 'Departemen',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              ]" />
        </div>

        <div>
          <FieldNumber :bind="{ readonly: true }" class="w-full mt-3" :value="values.total_biaya"
            @input="(v)=>values.total_biaya=v" :errorText="formErrors.total_biaya?'failed':''"
            :hints="formErrors.total_biaya" placeholder="Masukan Total Biaya" label="Total Biaya" :check="false" />
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" class="w-full mt-3"
            :value="values.kegiatan" :errorText="formErrors.kegiatan?'failed':''" @input="v=>values.kegiatan=v"
            :hints="formErrors.kegiatan" :check="false" label="Kegiatan" placeholder="Tuliskan Kegiatan" />
        </div>

        <div>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" class="w-full mt-3"
            :value="values.keterangan" :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v"
            :hints="formErrors.keterangan" :check="false" label="Keterangan" placeholder="Tuliskan Keterangan" />
        </div>

        <div>
          <FieldX placeholder="Masukan Status" label="Status" :bind="{ readonly: true }" type="text"
            :value="values.status" class="w-full mt-3" @input="v=>values.status=v" :check="false" />
        </div>
        <!-- END COLUMN -->
        <!-- ACTION BUTTON START -->
      </div>

      <div class="col-span-8 md:col-span-12">
        <button :disabled="!actionText" @click="addDetail" type="button" class="bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
              <icon fa="plus" /> <span>Add to List</span></button>
        <div class="mt-4">
          <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
            <thead>
              <tr class="border">
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                  No.</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[20%] border bg-[#f8f8f8] border-[#CACACA]">
                  Tipe</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Biaya</td>
                <!-- <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Kendaraan Dinas</td> -->
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[20%] bg-[#f8f8f8] border-[#CACACA]">
                  Keterangan</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[5%] bg-[#f8f8f8] border-[#CACACA]">
                  Aksi</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
                <td class="p-2 text-center border border-[#CACACA]">
                  {{ i + 1 }}.
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="!mt-0 w-full"
                    :value="item.tipe_spd_id" @input="v=>item.tipe_spd_id=v"
                    :errorText="formErrors.tipe_spd_id?'failed':''" label="" placeholder="Pilih Tipe SPD"
                    :hints="formErrors.tipe_spd_id" :api="{
                        url: `${store.server.url_backend}/operation/m_general`,
                        headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                        params: {
                          where: `this.group='TIPE SPD' AND this.is_active='true'`
                        }
                    }" valueField="id" displayField="value" :check="false" />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldNumber class="!mt-0 w-full" :bind="{ readonly: !actionText }" :value="item.biaya" @input="(v)=>{
                      item.biaya=v
                      countBiaya()}" :errorText="formErrors.biaya?'failed':''" :hints="formErrors.biaya"
                    placeholder="Tuliskan Biaya" label="" :check="false" />
                </td>
                <!-- <td class="p-2 border border-[#CACACA]">
                  <div class="flex justify-center items-center" :class="{'space-x-4':item.is_kendaraan_dinas}">
                    <input
                      class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-gray-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                      :class="{'after:bg-blue-500': item.is_kendaraan_dinas === true}"
                      type="checkbox"
                      role="switch"
                      id="is_active"
                      :disabled="!actionText"
                      v-model="item.is_kendaraan_dinas" />
                      <FieldPopup class="!mt-0 w-full" v-if="item.is_kendaraan_dinas"
                        :bind="{ readonly: !actionText }"
                        :value="item.m_knd_dinas_id" @input="(v)=>item.m_knd_dinas_id=v"
                        :errorText="formErrors.m_knd_dinas_id?'failed':''" 
                        :hints="formErrors.m_knd_dinas_id" 
                        valueField="id" displayField="nama"
                        :api="{
                          url:  `${store.server.url_backend}/operation/m_knd_dinas`,
                          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                          params: {
                              where: `this.is_active='true'`
                            }
                        }"
                        placeholder="Pilih Kendaraan Dinas" label="" :check="false" 
                        :columns="[{
                          headerName: 'No',
                          valueGetter:(p)=>p.node.rowIndex + 1,
                          width: 60,
                          sortable: false, resizable: false, filter: false,
                          cellClass: ['justify-center', 'bg-gray-50']
                        },
                        {
                          flex: 1,
                          field: 'nama',
                          headerName:  'Kendaraan',
                          sortable: false, resizable: true, filter: 'ColFilter',
                          cellClass: ['border-r', '!border-gray-200', 'justify-center']
                        },
                        {
                          flex: 1,
                          field: 'nopol',
                          headerName:  'Plat Nomer',
                          sortable: false, resizable: true, filter: 'ColFilter',
                          cellClass: ['border-r', '!border-gray-200', 'justify-center']
                        }
                        ]"
                      />
                  </div>
                  
                </td> -->
                <td class="p-2 border border-[#CACACA]">
                  <FieldX :bind="{ readonly: !actionText }" class="!mt-0" type="textarea" :value="item.keterangan"
                    @input="v=>item.keterangan=v" :errorText="formErrors.keterangan?'failed':''"
                    :hints="formErrors.keterangan" label="" placeholder="Tuliskan Keterangan" :check="false" />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <div class="flex justify-center">
                    <button type="button" @click="removeDetail(item)" :disabled="!actionText">
                    <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                    </svg>
                  </button>
                  </div>

                </td>
              </tr>
              <tr v-else class="text-center">
                <td colspan="7" class="py-[20px]">
                  No data to show
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="grid grid-cols-2 mt-6">
          <div v-show="route.query.is_approval">
            <table class=" w-[100%] my-3 border">
              <tr class="border">
                <td class="border px-2 py-1 font-semibold">Nomor</td>
                <td class="border px-2 py-1">{{ values.approval?.nomor ?? '-' }}</td>
              </tr>
              <tr class="border">
                <td class="border px-2 py-1 font-semibold">Tanggal</td>
                <td class="border px-2 py-1">{{ values.approval?.created_at ?? '-' }}</td>
              </tr>
              <tr class="border">
                <td class="border px-2 py-1 font-semibold">Pemohon</td>
                <td class="border px-2 py-1">{{ values.approval?.creator ?? '-' }}</td>
              </tr>
              <tr class="border">
                <td class="border px-2 py-1 font-semibold">Status</td>
                <td class="border px-2 py-1">{{ values.approval?.status ?? '-' }}</td>
              </tr>
            </table>
          </div>
          <div class="">
            <table class=" w-[100%] my-3 ">
              <tr>
                <td class=" px-2 py-1">
                  <button
                  v-show="route.query.is_approval"
                  @click="openModal(values?.trx?.id ?? 0)"
                  class="hover:text-blue-500">
                  <icon fa="table" size="sm"/>
                  Log Approval
                </button>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <div v-show="modalOpen" class="fixed inset-0 flex items-center justify-center z-50">
          <div class="modal-overlay fixed inset-0 bg-black opacity-50">
          </div>
          <div class="modal-container bg-white  w-[70%] mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
              <!-- Modal Header -->
              <div class="modal-header flex items-center justify-between flex-wrap">
                <div class="flex items-center">
                  <h3 class="text-xl font-semibold ml-2">Log Approval
                    <span v-if="!dataLog.items.length" class="!text-red-600"> | Belum ada log approval</span>
                  </h3>
                </div>
              </div>
              <!-- Modal Body -->
              <div v-if="dataLog.items.length" class="modal-body">
                <table class="w-[100%] my-3 border">
                  <thead>
                    <tr class="border">
                      <td class="border px-2 py-1 font-medium ">Urutan</td>
                      <td class="border px-2 py-1 font-medium ">Nomor Transaksi</td>
                      <td class="border px-2 py-1 font-medium ">Tipe Aksi</td>
                      <td class="border px-2 py-1 font-medium ">Tanggal Aksi </td>
                      <td class="border px-2 py-1 font-medium ">User Aksi</td>
                      <td class="border px-2 py-1 font-medium ">Catatan</td>
                    </tr>
                  </thead>
                  <tr class="border" v-for="d,i in dataLog.items" :key="i">
                    <td class="border px-2 py-1">{{ i+1 }}</td>
                    <td class="border px-2 py-1">{{ d.trx_nomor ?? '-' }}</td>
                    <td class="border px-2 py-1">{{ d.action_type ?? '-' }}</td>
                    <td class="border px-2 py-1">{{ d.action_at ?? '-' }}</td>
                    <td class="border px-2 py-1">{{ d.action_user ?? '-' }}</td>
                    <td class="border px-2 py-1">{{ d.action_note ?? '-' }}</td>
                  </tr>
                </table>
              </div>
              <!-- Modal Footer -->
              <div class="modal-footer flex justify-end mt-2">
                <button @click="closeModal" class="modal-button bg-yellow-500 hover:bg-yellow-600 text-white font-semibold ml-2 px-2 py-1 rounded-sm">
                Tutup
              </button>
              </div>
            </div>
          </div>
        </div>
        <div v-show="route.query.is_approval" class="w-1/2 mt-6">
          <label class="col-span-12 font-semibold">Catatan Approval<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: false }" class="w-full py-2 !mt-0" :value="values.catatan"
            :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
            :check="false" label="" placeholder="Tuliskan catatan" />
        </div>
        <div class="flex flex-row justify-end space-x-[20px] mt-[1em]">
          <button v-show="route.query.is_approval" class="mx-1 bg-green-500 text-white hover:bg-green-600 rounded-lg py-[10px] px-[28px] " @click="onProcess('approve')">
              Approve
            </button>
          <button v-show="route.query.is_approval" class="mx-1 bg-rose-500 text-white hover:bg-rose-600 rounded-lg py-[10px] px-[28px] " @click="onProcess('reject')">
              Reject
            </button>
          <button v-show="route.query.is_approval" class="mx-1 bg-amber-500 text-white hover:bg-amber-600 rounded-lg py-[10px] px-[28px] " @click="onProcess('revise')">
              Revise
            </button>
          <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
              Kembali
            </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
              Simpan
            </button>
        </div>
        <!-- FORM END -->
      </div>
    </div>
  </div>
  @endverbatim
  @endif