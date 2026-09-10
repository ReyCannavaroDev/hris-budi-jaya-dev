@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="flex justify-between items-center px-2.5 py-1">

    <div class="flex items-center gap-x-4">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Inactive</button>
      </div>
    </div>
    
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))" class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[450px]">
    <!-- <template #header>
    </template> -->
  </TableApi>
</div>
@else

@verbatim

<div class="flex flex-col gap-y-2 scroll-auto max-h-[470px]">
  <div class="flex gap-x-1 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white ">

      <!-- HEADER START -->
      <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
        <div class="flex items-center">
          <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
            @click="onBack" />
          <div>
            <h1 class="text-20px font-bold">Form Karyawan</h1>
            <p class="text-gray-100">Master Karyawan</p>
          </div>
        </div>
      </div>
      <!-- HEADER END -->
      <div class="flex items-stretch w-full text-sm overflow-x-auto">
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:text-yellow-500 hover:text-yellow-500 duration-300"
            :class="{'border-yellow-500 text-yellow-500 font-bold': activeTabIndex === 0}"
            @click="activeTabIndex = 0"
          >
            Informasi
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:text-yellow-500 hover:text-yellow-500 duration-300"
            :class="{'border-yellow-500 text-yellow-500 font-bold': activeTabIndex === 1}"
            @click="activeTabIndex = 1"
          >
            Pendidikan
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 hover:text-yellow-500 hover:text-yellow-500 duration-300 border-gray-100 p-3"
            :class="{'border-yellow-500 text-yellow-500 font-bold': activeTabIndex === 2}"
            @click="activeTabIndex = 2"
          >
            Keluarga
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:text-yellow-500 hover:text-yellow-500 duration-300"
            :class="{'border-yellow-500 text-yellow-500 font-bold': activeTabIndex === 3}"
            @click="activeTabIndex = 3"
          >
            Pelatihan
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:text-yellow-500 hover:text-yellow-500 duration-300"
            :class="{'border-yellow-500 text-yellow-500 font-bold': activeTabIndex === 4}"
            @click="activeTabIndex = 4"
          >
            Prestasi
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:text-yellow-500 hover:text-yellow-500 duration-300"
            :class="{'border-yellow-500 text-yellow-500 font-bold': activeTabIndex === 5}"
            @click="activeTabIndex = 5"
          >
            Organisasi
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:text-yellow-500 hover:text-yellow-500 duration-300"
            :class="{'border-yellow-500 text-yellow-500 font-bold': activeTabIndex === 6}"
            @click="activeTabIndex = 6"
          >
            Bahasa
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:text-yellow-500 hover:text-yellow-500 duration-300"
            :class="{'border-yellow-500 text-yellow-500 font-bold': activeTabIndex === 7}"
            @click="activeTabIndex = 7"
          >
            Pengalaman Kerja
          </button>
      </div>
      <!-- Form Informasi -->
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2" v-if="activeTabIndex === 0">
        <!-- NOT PROFILE -->
        <div v-if="!isProfile">
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.m_divisi_id" @input="v=>values.m_divisi_id=v" :errorText="formErrors.m_divisi_id?'failed':''"
            @update:valueFull="(objVal)=>{
                  values.m_dept_id = null
                }"           label="Divisi" placeholder="Pilih Divisi" :hints="formErrors.m_divisi_id" :api="{
                    url: `${store.server.url_backend}/operation/m_divisi`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      where: `this.is_active = 'true'`
                    }
                }" valueField="id" displayField="nama" :check="false" />

        </div>
        <div v-if="!isProfile">
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" :value="values.m_dept_id"
            @input="v=>values.m_dept_id=v" :errorText="formErrors.m_dept_id?'failed':''" label=""
            placeholder="Pilih Departemen" label="Departemen" :hints="formErrors.m_dept_id" :api="{
                    url: `${store.server.url_backend}/operation/m_dept`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      where: `m_divisi_id=${values.m_divisi_id} AND this.is_active = 'true'`
                    }
                }" valueField="id" displayField="nama" :check="false" />

        </div>
        <div v-if="!isProfile">
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.m_posisi_id" @input="v=>values.m_posisi_id=v" @update:valueFull="(items)=>{
                  values.m_standart_gaji_id = null
                  $log('ikiposisi')
                }" :errorText="formErrors.m_posisi_id?'failed':''" label="Posisi" placeholder="Pilih Posisi"
            :hints="formErrors.m_posisi_id" :api="{
                    url: `${store.server.url_backend}/operation/m_posisi`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                }" valueField="id" displayField="desc_kerja" :check="false" />

        </div>
        <div v-if="!isProfile">
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" :value="values.m_zona_id"
            @input="v=>{
                  values.m_zona_id=v
                  setStandartGaji()
                  }" :errorText="formErrors.m_zona_id?'failed':''" @update:valueFull="(items)=>{
                  values.m_standart_gaji_id=null
                }" label="Zona" placeholder="Pilih Zona" :hints="formErrors.m_zona_id" :api="{
                    url: `${store.server.url_backend}/operation/m_zona`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                }" valueField="id" displayField="nama" :check="false" />

        </div>
        <div v-if="!isProfile">
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" :value="values.grading_id"
            @input="v=>{
                  values.grading_id=v
                  setStandartGaji()
                  }" :errorText="formErrors.grading_id?'failed':''" displayField="value" @update:valueFull="(v)=>{
                  values.m_standart_gaji_id=null
                }" :hints="formErrors.grading_id" :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        where: `this.group = 'GRADING'`,
                        selectfield:'this.id,this.value,this.code'
                      }
                }" valueField="id" :check="false" label="Grading" placeholder="Pilih Grading" />

        </div>
        <div v-if="!isProfile">
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3"
            :value="values.m_standart_gaji_id" @input="v => values.m_standart_gaji_id = v"
            :errorText="formErrors.m_standart_gaji_id ? 'failed' : ''" label="Standart Gaji" placeholder="Pilih Standart Gaji"
            :hints="formErrors.m_standart_gaji_id" :api="{
                      url: `${store.server.url_backend}/operation/m_standart_gaji`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                          simplest: true,
                          where: `this.is_active='true' AND this.m_zona_id = ${values.m_zona_id ?? 0} AND this.grading_id = ${values.grading_id ?? 0}`
                      }
                  }" valueField="id" displayField="kode" :check="false" />

        </div>
        <div v-if="!isProfile">
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3"
            :value="values.tipe_jam_kerja_id" @input="v => values.tipe_jam_kerja_id = v"
            :errorText="formErrors.tipe_jam_kerja_id ? 'failed' : ''" label="Tipe Jam Kerja" @update:valueFull="changeTipeJamKerja"
            placeholder="Pilih Tipe Jam Kerja" :hints="formErrors.tipe_jam_kerja_id" :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        where:`this.group='TIPEJAM' AND this.is_active='true'`,
                        join:true, 
                        selectfield: 'this.id, this.code, this.value, this.is_active'
                      }
                  }" valueField="id" displayField="value" :check="false" />

        </div>
        <div v-if="!isProfile">
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.costcontre_id" @input="v=>values.costcontre_id=v"
            :errorText="formErrors.costcontre_id?'failed':''" label="Costcentre" placeholder="Pilih Costcentre"
            :hints="formErrors.costcontre_id" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      where: `this.group='COSENTRE' AND this.is_active = 'true'`
                    }
                }" valueField="id" displayField="value" :check="false" />

        </div>
        <div v-if="!isProfile">
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" :value="values.is_active"
            @input="v=>values.is_active=v" :errorText="formErrors.is_active?'failed':''" :hints="formErrors.is_active"
            label="Status" placeholder="Pilih Status" :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
            valueField="id" displayField="key" :check="false" />
        </div>
        <div v-if="!isProfile">
          <FieldPopup v-if="values['tipe_jam_kerja.value'] == 'OFFICE'" :bind="{ readonly: true }" class="w-full mt-3"
            :value="values.t_jadwal_kerja_id" @input="(v)=>values.t_jadwal_kerja_id=v"
            :errorText="formErrors.t_jadwal_kerja_id?'failed':''" :hints="formErrors.t_jadwal_kerja_id" valueField="id"
            displayField="nomor" @update:valueFull="(objVal)=>{  
                  values.t_jadwal_kerja_ket = objVal.keterangan
                }" :api="{
                  url: `${store.server.url_backend}/operation/t_jadwal_kerja`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    where: `this.status = 'POSTED'`,
                    searchfield:'this.id, this.nomor, this.keterangan',
                  }
                }" placeholder="Pilih Jadwal Kerja" label="OFFICE" :check="false" :columns="[{
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  field: 'nomor',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-center']
                },
                {
                  flex: 1,
                  field: 'keterangan',
                  sortable: false, resizable: true, filter: 'ColFilter', wrapText: true,
                  cellClass: ['border-r', '!border-gray-200', 'justify-center']
                }
                ]" />
        </div>
      </div>
      <!-- Data Karyawan -->
      <h2 class="font-bold text-[18px] " v-if="activeTabIndex === 0">Data Karyawan</h2>
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2" v-if="activeTabIndex === 0">
        <div>
          <FieldX :bind="{ readonly: !actionText }" type="text" class="w-full mt-3" :value="values.kode" label="Nomer Induk Karyawan"
            placeholder="Masukan Nomor Induk Karyawan" :errorText="formErrors.kode?'failed':''"
            @input="v=>values.kode=v" :hints="formErrors.kode" :check="false" />
        </div>
        <div>
          <FieldPopup :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.atasan_id"
            @input="(v)=>values.atasan_id=v" :errorText="formErrors.atasan_id?'failed':''" :hints="formErrors.atasan_id"
            valueField="id" displayField="nama_lengkap" :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.is_active = true`,
                  searchfield: 'this.nik, this.nama_lengkap, this.nama_depan, this.nama_belakang, m_zona.nama, m_dir.nama, m_divisi.nama, m_dept.nama'
                }
              }" placeholder="Cari Atasan Karyawan" label="Atasan Karyawan" :check="false" :columns="[{
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
          <FieldSelect class="w-full !mt-3" :bind="{ disabled: !actionText, clearable:false }"
            :value="values.presensi_lokasi_default_id" @input="v=>values.presensi_lokasi_default_id=v"
            :errorText="formErrors.presensi_lokasi_default_id?'failed':''"
            :hints="formErrors.presensi_lokasi_default_id" label="Presensi Lokasi" valueField="id" displayField="nama" :api="{
                    url: `${store.server.url_backend}/operation/presensi_lokasi`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      selectfield: 'this.id, this.nama, this.lat, this.long'
                    }
                  }" placeholder="Pilih Master Lokasi" :check="false" />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.nama_depan" label="Nama Depan"
            placeholder="Tuliskan Nama Depan"  :errorText="formErrors.nama_depan?'failed':''"
            @input="v=>values.nama_depan=v" :hints="formErrors.nama_depan" :check="false" />
 <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-4" :value="values.jk_id"
            label="Jenis Kelamin" placeholder="Pilih Jenis Kelamin" @input="v=>values.jk_id=v"
            :errorText="formErrors.jk_id?'failed':''" :hints="formErrors.jk_id" valueField="id" displayField="value"
            :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    where: `this.group='JENIS KELAMIN' AND this.is_active='true'`,
                    join: true,
                    selectfield: 'this.id, this.code, this.value, this.is_active'
                  }
                }" :check="false" />
        </div>
        <div>
                    <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.nama_belakang" label="Nama Belakang"
            placeholder="Tuliskan Nama Belakang" :errorText="formErrors.nama_belakang?'failed':''"
            @input="v=>values.nama_belakang=v" :hints="formErrors.nama_belakang" :check="false" />
        </div>
        <div>
                    <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.nama_panggilan" label="Nama Panggilan"
            placeholder="Tuliskan Nama Panggilan Karyawan" :errorText="formErrors.nama_panggilan?'failed':''"
            @input="v=>values.nama_panggilan=v" :hints="formErrors.nama_panggilan" :check="false" />

         

        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.tempat_lahir" @input="v=>values.tempat_lahir=v"
            :errorText="formErrors.tempat_lahir?'failed':''" :hints="formErrors.tempat_lahir" label="Tempat Lahir"
            placeholder="Tempat Lahir" valueField="value" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      join: true,
                      where: `this.group='KOTA'`,
                      paginate: 1000
                    }
                  }" :check="false" />
          <FieldX :bind="{ readonly: !actionText }" type="date" class="w-full mt-3" :value="values.tgl_lahir" label="Tanggal Lahir"
            placeholder="Pilih Tanggal Lahir" :errorText="formErrors.tgl_lahir?'failed':''"
            @input="v=>values.tgl_lahir=v" :hints="formErrors.tgl_lahir" :check="false" />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" class="w-full mt-3" :value="values.alamat_domisili"
            label="Alamat" placeholder="Tuliskan Alamat" :errorText="formErrors.alamat_domisili?'failed':''"
            @input="v=>values.alamat_domisili=v" :hints="formErrors.alamat_domisili" :check="false" />
        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.provinsi_id" @input="v=>values.provinsi_id=v" :errorText="formErrors.provinsi_id?'failed':''"
            @update:valueFull="(objVal)=>{
                    values.kota_id = '',
                    values.kecamatan_id = '',
                    values.kode_pos = ''
                  }" :hints="formErrors.provinsi_id" label="Provinsi" placeholder="Pilih Provinsi" valueField="id"
            displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      scopes: 'genProvinsi',
                      join: true,
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                  }" :check="false" />

        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" :value="values.kota_id"
            @input="v=>values.kota_id=v" :errorText="formErrors.kota_id?'failed':''" @update:valueFull="(objVal)=>{
                    values.kecamatan_id = '',
                    values.kode_pos = ''
                  }" :hints="formErrors.kota_id" label="Kota" placeholder="Pilih Kota" valueField="id" displayField="value"
            :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      scopes: 'genKota',
                      provinsi_id: values.provinsi_id ?? null,
                      join: true,
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                  }" :check="false" />
        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.kecamatan_id" @input="v=>values.kecamatan_id=v"
            :errorText="formErrors.kecamatan_id?'failed':''" @update:valueFull="(objVal)=>{
                    values.kode_pos = ''
                  }" :hints="formErrors.kecamatan_id" label="Kecamatan" placeholder="Pilih Kecamatan" valueField="id"
            displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      scopes: 'genKecamatan',
                      kota_id: values.kota_id ?? null,
                      join: true,
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                  }" :check="false" />

        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3" :value="values.kode_pos" label="Kode Pos"
            placeholder="Tuliskan Kode Pos" :errorText="formErrors.kode_pos?'failed':''" @input="v=>values.kode_pos=v"
            :hints="formErrors.kode_pos" :check="false" />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3" :value="values.no_tlp" label="Nomer Telepon"
            placeholder="Tuliskan Nomer Telepon" :errorText="formErrors.no_tlp?'failed':''" @input="v=>values.no_tlp=v"
            :hints="formErrors.no_tlp" :check="false" />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3" :value="values.no_tlp_lainnya"
            label="No Telepon Lainya" placeholder="Tuliskan Nomer Telepon Lainnya" :errorText="formErrors.no_tlp_lainnya?'failed':''"
            @input="v=>values.no_tlp_lainnya=v" :hints="formErrors.no_tlp_lainnya" :check="false" />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3" :value="values.no_darurat"
            label="No Telepon Darurat" placeholder="Tuliskan Nomer Telepon Darurat" :errorText="formErrors.no_darurat?'failed':''"
            @input="v=>values.no_darurat=v" :hints="formErrors.no_darurat" :check="false" />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.nama_kontak_darurat" label="Nama Kontak Darurat"
            placeholder="Tuliskan Nama Kontak Darurat" :errorText="formErrors.nama_kontak_darurat?'failed':''"
            @input="v=>values.nama_kontak_darurat=v" :hints="formErrors.nama_kontak_darurat" :check="false" />
        </div>
        <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.hub_dgn_karyawan" label="Hubungan Dengan Karyawan"
            placeholder="Tulis Hubungan Dengan Karyawan" :errorText="formErrors.hub_dgn_karyawan?'failed':''"
            @input="v=>values.hub_dgn_karyawan=v" :hints="formErrors.hub_dgn_karyawan" :check="false" />
        </div>
        <div>

          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" :value="values.agama_id"
            @input="v=>values.agama_id=v" :errorText="formErrors.agama_id?'failed':''" :hints="formErrors.agama_id"
            label="Agama" placeholder="Pilih Agama" valueField="id" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      where: `this.group='AGAMA' AND this.is_active='true'`,
                      join: true,
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                  }" :check="false" />
        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.gol_darah_id" @input="v=>values.gol_darah_id=v"
            :errorText="formErrors.gol_darah_id?'failed':''" :hints="formErrors.gol_darah_id" label="Golongan Darah"
            placeholder="Pilih Golongan Darah" valueField="id" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      where: `this.group='GOLONGAN DARAH' AND this.is_active='true'`,
                      join: true,
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                  }" :check="false" />

        </div>
        <div>

          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.status_nikah_id" @input="v=>values.status_nikah_id=v"
            :errorText="formErrors.status_nikah_id?'failed':''" :hints="formErrors.status_nikah_id" label="Status Pernikahan"
            placeholder="Pilih Status Pernikahan" valueField="id" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      where: `this.group='STATUS NIKAH' AND this.is_active='true'`,
                      join: true,
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                  }" :check="false" />
        </div>
        <div>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.tanggungan_id" @input="v=>values.tanggungan_id=v"
            :errorText="formErrors.tanggungan_id?'failed':''" :hints="formErrors.tanggungan_id" label="Tanggungan"
            placeholder="Pilih Tanggungan" valueField="id" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      where: `this.group='TANGGUNGAN' AND this.is_active='true'`,
                      join: true,
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                  }" :check="false" />
        </div>
        <div>
          <label class="col-span-12">Limit Potong<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" label="Limit Potong" type="number" class="w-full mt-3"
            :value="values.limit_potong" :errorText="formErrors.limit_potong?'failed':''"
            @input="v=>values.limit_potong=v" :hints="formErrors.limit_potong" placeholder="Limit Potong"
            :check="false" />

        </div>
      </div>
      <!-- Sosial Media -->
      <h2 class="font-bold text-[18px] " v-if="activeTabIndex === 0">Media Sosial</h2>
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2" v-if="activeTabIndex === 0">
        <div>

          <FieldX :bind="{ readonly: !actionText }"  class="w-full mt-3" :value="values.email" label="Email"
            placeholder="Email" :errorText="formErrors.email?'failed':''" @input="v=>values.email=v"
            :hints="formErrors.email" :check="false" />

        </div>
        <div>

          <FieldX :bind="{ readonly: !actionText }"  class="w-full mt-3" :value="values.linkedin" label="LinkedIn"
            placeholder="Linked In" :errorText="formErrors.linkedin?'failed':''" @input="v=>values.linkedin=v"
            :hints="formErrors.linkedin" :check="false" />

        </div>
        <div>

          <FieldX :bind="{ readonly: !actionText }"  class="w-full mt-3" :value="values.ig" label="Instagram"
            placeholder="Tuliskan Instagram" :errorText="formErrors.ig?'failed':''"
            @input="v=>values.ig=v" :hints="formErrors.ig" :check="false" />

        </div>
        <div>

          <FieldX :bind="{ readonly: !actionText }"  class="w-full mt-3" :value="values.facebook" label="Facebook"
            placeholder="Tuliskan Facebook" :errorText="formErrors.facebook?'failed':''" @input="v=>values.facebook=v"
            :hints="formErrors.facebook" :check="false" />

        </div>
        <div>

          <FieldX :bind="{ readonly: !actionText }"  class="w-full mt-3" :value="values.x" label="X"
            placeholder="Tuliskan X" :errorText="formErrors.x?'failed':''" @input="v=>values.x=v"
            :hints="formErrors.x" :check="false" />

        </div>
      </div>
      <!-- INFO LAIN -->
      <h2 class="font-bold text-[18px] " v-if="activeTabIndex === 0">Info Lain</h2>
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2" v-if="activeTabIndex === 0">

        <div>
          <div>
            <label >Cuti Reguler<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="w-full mt-3" :value="values.cuti_reguler" label=""
              placeholder="Tuliskan Jatah Cuti Reguler" :errorText="formErrors.cuti_reguler?'failed':''"
              @input="v=>values.cuti_reguler=v" :hints="formErrors.cuti_reguler" :check="false" />
          </div>
          <div>
            <label >Sisa Regular<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="w-full mt-3" :value="values.sisa_cuti_reguler"
              label="" placeholder="Tuliskan Sisa Jatah Cuti Reguler"
              :errorText="formErrors.sisa_cuti_reguler?'failed':''" @input="v=>values.sisa_cuti_reguler=v"
              :hints="formErrors.sisa_cuti_reguler" :check="false" />
          </div>
        </div>

        <div>
          <div>
            <label >Cuti Masa Kerja<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="w-full mt-3" :value="values.cuti_masa_kerja"
              label="" :errorText="formErrors.cuti_masa_kerja?'failed':''" @input="v=>values.cuti_masa_kerja=v"
              :hints="formErrors.cuti_masa_kerja" :check="false" />
          </div>
          <div>
            <label >Sisa Masa Kerja<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="w-full mt-3" :value="values.sisa_cuti_masa_kerja"
              label="" :errorText="formErrors.sisa_cuti_masa_kerja?'failed':''"
              @input="v=>values.sisa_cuti_masa_kerja=v" :hints="formErrors.sisa_cuti_masa_kerja" :check="false" />
          </div>
        </div>
        <div>
          <div>
            <label >Cuti P24<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="w-full mt-3" :value="values.cuti_p24" label=""
              :errorText="formErrors.cuti_p24?'failed':''" @input="v=>values.cuti_p24=v" :hints="formErrors.cuti_p24"
              :check="false" />
          </div>
          <div>
            <label >Sisa P24<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="w-full mt-3" :value="values.cuti_p24_terpakai"
              label="" :errorText="formErrors.cuti_p24_terpakai?'failed':''" @input="v=>values.cuti_p24_terpakai=v"
              :hints="formErrors.cuti_p24_terpakai" :check="false" />
          </div>
        </div>


        <div>
          <label >Tanggal Masuk Kerja<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText, disabled:!actionText }" type="date" class="w-full mt-3"
            :value="values.tgl_masuk" label="" :errorText="formErrors.tgl_masuk?'failed':''"
            @input="v=>values.tgl_masuk=v" :hints="formErrors.tgl_masuk" :check="false" />
        </div>
        <div>
          <label >Tanggal Berhenti<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" type="date" class="w-full mt-3"
            :value="values.tgl_berhenti" label="" :errorText="formErrors.tgl_berhenti?'failed':''"
            @input="v=>values.tgl_berhenti=v" :hints="formErrors.tgl_berhenti" :check="false" />
        </div>



      </div>
      <!-- Berkas -->
      <h2 class="font-bold text-[18px] col-span-8 md:col-span-6" v-if="activeTabIndex === 0" >Berkas Karyawan</h2>
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2" v-if="activeTabIndex === 0">


        <div>
          <label >Foto Karyawan<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <div class="w-full mt-3">
            <input :disabled="!actionText ? true : false" ref="refPasFoto" type="file" accept="image/*" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.pas_foto}" id="inputPasFoto" @change="imageChange">
          </div>
          <img :src="urlPasFoto" class="col-span-12 !mt-0 w-[231px]">
        </div>




        <div>
          <label >Foto KTP<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <div class="w-full mt-3">
            <input :disabled="!actionText ? true : false" type="file" accept="image/*" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.ktp_foto}" id="inputKTPFoto" @change="imageChange">

          </div>
          <img :src="urlKTPFoto" class="col-span-12 !mt-0 w-[231px]">
        </div>


        <div>
          <label >No. KTP<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3" :value="values.ktp_no" label=""
            placeholder="Tuliskan Nomor Kartu Penduduk" :errorText="formErrors.ktp_no?'failed':''"
            @input="v=>values.ktp_no=v" :hints="formErrors.ktp_no" :check="false" />
        </div>


        <div>
          <label >Alamat Sesuai KTP<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" class="w-full mt-3" :value="values.alamat_asli"
            label="" placeholder="Tuliskan Alamat Sesuai KTP" :errorText="formErrors.alamat_asli?'failed':''"
            @input="v=>values.alamat_asli=v" :hints="formErrors.alamat_asli" :check="false" />
        </div>



        <div>
          <label >Foto Kartu Keluarga<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <div class="w-full mt-3">
            <input :disabled="!actionText ? true : false" type="file" accept="image/*" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.kk_foto}" id="inputKKFoto" @change="imageChange">

          </div>
          <img :src="urlKKFoto" class="col-span-12 !mt-0 w-[231px]">
        </div>



        <div>
          <label >No. Kartu Keluarga<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3" :value="values.kk_no" label=""
            placeholder="Tuliskan Nomor Kartu Keluarga" :errorText="formErrors.kk_no?'failed':''"
            @input="v=>values.kk_no=v" :hints="formErrors.kk_no" :check="false" />
        </div>



        <div>
          <label >Foto NPWP<label class="text-red-500 space-x-0 pl-0"></label></label>
          <div class="w-full mt-3">
            <input :disabled="!actionText ? true : false" type="file" accept="image/*" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.npwp_foto}" id="inputNPWPFoto" @change="imageChange">

          </div>
          <img :src="urlNPWPFoto" class="col-span-12 !mt-0 w-[231px]">
        </div>



        <div>
          <label >No. NPWP<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3" :value="values.npwp_no" label=""
            placeholder="Tuliskan Nomor Pokok Wajib Pajak" :errorText="formErrors.npwp_no?'failed':''"
            @input="v=>values.npwp_no=v" :hints="formErrors.npwp_no" :check="false" />
        </div>


        <div>
          <label >Tanggal Berlaku NPWP<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type="date" class="w-full mt-3" :value="values.npwp_tgl_berlaku"
            label="" placeholder="Masukan Tanggal Berlaku NPWP" :errorText="formErrors.npwp_tgl_berlaku?'failed':''"
            @input="v=>values.npwp_tgl_berlaku=v" :hints="formErrors.npwp_tgl_berlaku" :check="false" />
        </div>


        <div>
          <label >No. BPJS Kesehatan<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3" :value="values.bpjs_no_kesehatan"
            label="" placeholder="Tuliskan Nomor BPJS" :errorText="formErrors.bpjs_no_kesehatan?'failed':''"
            @input="v=>values.bpjs_no_kesehatan=v" :hints="formErrors.bpjs_no_kesehatan" :check="false" />
        </div>


        <div>
          <label >No. BPJS Ketenagakerjaan<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3"
            :value="values.bpjs_no_ketenagakerjaan" label="" placeholder="Tuliskan Nomor BPJS"
            :errorText="formErrors.bpjs_no_ketenagakerjaan?'failed':''" @input="v=>values.bpjs_no_ketenagakerjaan=v"
            :hints="formErrors.bpjs_no_ketenagakerjaan" :check="false" />
        </div>


        <div>
          <label >Tipe BPJS<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
            :value="values.bpjs_tipe_id" @input="v=>values.bpjs_tipe_id=v"
            :errorText="formErrors.bpjs_tipe_id?'failed':''" :hints="formErrors.bpjs_tipe_id" label=""
            placeholder="Pilih Tipe BPJS" valueField="id" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      where: `this.group='TIPE BPJS' AND this.is_active='true'`,
                      join: true,
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                  }" :check="false" />
        </div>


        <div>
          <label >Berkas Pendukung Lainnya<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldUpload class="w-full mt-3" :bind="{ readonly: !actionText }" :value="values.berkas_lain"
            @input="(v)=>values.berkas_lain=v" :maxSize="10"
            :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
                  url: `${store.server.url_backend}/operation/m_kary_det_kartu/upload`,
                  headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: { field: 'berkas_lain' },
                  onsuccess: response=>response,
                  onerror:(error)=>{},
                 }" :hints="formErrors.berkas_lain" label="" placeholder="Upload Berkas" fa-icon="upload"
            accept="application/pdf" :check="false" />

        </div>


        <div>
          <label >Keterangan<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" class="w-full mt-3" :value="values.desc_file"
            label="" placeholder="Tuliskan Keterangan" :errorText="formErrors.desc_file?'failed':''"
            @input="v=>values.desc_file=v" :hints="formErrors.desc_file" :check="false" />
        </div>


      </div>
<!-- Ukuran -->
      <h2 class="font-bold text-[18px] " v-if="activeTabIndex === 0" >Ukuran</h2>
      <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2" v-if="activeTabIndex === 0">
          <div >
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
              :value="values.uk_baju" @input="v=>values.uk_baju=v" :errorText="formErrors.uk_baju?'failed':''"
              :hints="formErrors.uk_baju" label="Ukuran Baju" placeholder="Pilih Ukuran Baju" valueField="key" displayField="key"
              :options="['S', 'M', 'L', 'XL', 'XXL', 'XXXL']" :check="false" />
          </div>
          <div >
            <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3"
              :value="values.uk_celana" label="Ukuran Celana" placeholder="Tuliskan Ukuran Celana"
              :errorText="formErrors.uk_celana?'failed':''" @input="v=>values.uk_celana=v" :hints="formErrors.uk_celana"
              :check="false" />
          </div>

          <div>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3"
              :value="values.uk_sepatu" label="Ukuran Sepatu" placeholder="Tuliskan Ukuran Sepatu"
              :errorText="formErrors.uk_sepatu?'failed':''" @input="v=>values.uk_sepatu=v" :hints="formErrors.uk_sepatu"
              :check="false" />
          </div>
      </div>
<!-- pembayaran -->
        <h2 class="font-bold text-[18px] " v-if="activeTabIndex === 0" >Pembayaran</h2>
        <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2" v-if="activeTabIndex === 0">

        <div v-if="!isProfile" >

            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
              :value="values.periode_gaji_id" @input="v=>values.periode_gaji_id=v"
              :errorText="formErrors.periode_gaji_id?'failed':''" :hints="formErrors.periode_gaji_id" label="Perido Gaji"
              placeholder="Pilih Periode Gaji" valueField="id" displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    join: true,
                    where: `this.group='PERIODE GAJI' AND this.is_active='true'`,
                  }
                }" :check="false" />
        </div>
        <div v-if="!isProfile">
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
              :value="values.tipe_id" @input="v=>values.tipe_id=v" :errorText="formErrors.tipe_id?'failed':''"
              :hints="formErrors.tipe_id" label="Tipe Pembayaran" placeholder="Pilih Tipe Pembayaran" valueField="id"
              displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    join: true,
                    where: `this.group='TIPE PEMBAYARAN' AND this.is_active='true'`,
                  }
                }" :check="false" />
        </div>
        <div v-if="!isProfile">
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
              :value="values.metode_id" @input="v=>values.metode_id=v" :errorText="formErrors.metode_id?'failed':''"
              :hints="formErrors.metode_id" label="Pembayaran" placeholder="Pilih Metode Pembayaran" valueField="id"
              displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    join: true,
                    where: `this.group='METODE PEMBAYARAN' AND this.is_active='true'`,
                  }
                }" :check="false" />
        </div>

        <div v-if="!isProfile">
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
              :value="values.bank_id" @input="v=>values.bank_id=v" :errorText="formErrors.bank_id?'failed':''"
              :hints="formErrors.bank_id" label="Bank" placeholder="Pilih Bank" valueField="id" displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    join: true,
                    where: `this.group='BANK' AND this.is_active='true'`,
                  }
                }" :check="false" />

        </div>
        <div >
            <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3"
              :value="values.no_rek" label="Nomer Rekening" placeholder="Tuliskan Nomor Rekening"
              :errorText="formErrors.no_rek?'failed':''" @input="v=>values.no_rek=v" :hints="formErrors.no_rek"
              :check="false" />

        </div>
        <div >

            <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.atas_nama_rek"
              label="Nama Pemilik Rekening" placeholder="Tuliskan Atas Nama Pemilik Rekening"
              :errorText="formErrors.atas_nama_rek?'failed':''" @input="v=>values.atas_nama_rek=v"
              :hints="formErrors.atas_nama_rek" :check="false" />

        </div>
      </div>


      <!-- Form Pendidikan -->
<div class="p-4 " v-if="activeTabIndex === 1">
 <div class="grid <md:grid-cols-1 grid-cols-3 gap-2" > 
    <div>
          <label>Tingkat Pendidikan <label class="text-red-500 space-x-0 pl-0">*</label></label>
    <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full"
      :value="valuesPendidikan.tingkat_id" label="" placeholder="Pilih Tingkat Pendidikan"
      @input="v=>valuesPendidikan.tingkat_id=v" :errorText="formErrorsPend.tingkat_id?'failed':''"
      @update:valueFull="(objVal)=>{
        valuesPendidikan.tingkat = objVal.value
      }" :hints="formErrorsPend.tingkat_id" valueField="id" displayField="value" :api="{
        url: `${store.server.url_backend}/operation/m_general`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest: true,
          transform: false,
          where: `this.group='PENDIDIKAN' AND this.is_active='true'`,
          join: true,
          selectfield: 'this.id, this.code, this.value, this.is_active'
        }
      }" :check="false" />
  </div>
  <div>
        <label>Tahun Masuk <label class="text-red-500 space-x-0 pl-0">*</label></label>
    <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full" label=""
      placeholder="Pilih Tahun Masuk" :value="valuesPendidikan.thn_masuk"
      @input="v=>valuesPendidikan.thn_masuk=v" :options="ArrTahun"
      :errorText="formErrorsPend.thn_masuk?'failed':''" :hints="formErrorsPend.thn_masuk" valueField="key"
      displayField="key" :check="false" />
  </div>
  <div>
        <label>Nama Sekolah <label class="text-red-500 space-x-0 pl-0">*</label></label>
    <FieldX :bind="{ readonly: !actionText }" class="w-full"
      :value="valuesPendidikan.nama_sekolah" label="" placeholder="Tuliskan Nama Sekolah"
      @input="v=>valuesPendidikan.nama_sekolah=v" :check="false"
      :errorText="formErrorsPend.nama_sekolah?'failed':''" :hints="formErrorsPend.nama_sekolah" />
  </div>
  <div>
        <label>Tahun Lulus <label class="text-red-500 space-x-0 pl-0">*</label></label>
    <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full"
      :value="valuesPendidikan.thn_lulus" label="" placeholder="Pilih Tahun Lulus"
      @input="v=>valuesPendidikan.thn_lulus=v" :options="ArrTahun"
      :errorText="formErrorsPend.thn_lulus?'failed':''" :hints="formErrorsPend.thn_lulus" valueField="key"
      displayField="key" :check="false" />
  </div>
  <div>
        <label> Kota <label class="text-red-500 space-x-0 pl-0">*</label></label>
    <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full"
      :value="valuesPendidikan.kota_id" @input="v=>valuesPendidikan.kota_id=v"
      :errorText="formErrorsPend.kota_id?'failed':''" :hints="formErrorsPend.kota_id" label=""
      placeholder="Pilih Kota" valueField="id" displayField="value" :api="{
        url: `${store.server.url_backend}/operation/m_general`,
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        params: {
          simplest: true,
          transform: false,
          join: true,
          where: `this.group='KOTA'`,
          paginate: 1000
        }
      }" :check="false" />
  </div>
  <div>
        <label> Nilai<label class="text-red-500 space-x-0 pl-0">*</label></label>
    <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full"
      :value="valuesPendidikan.nilai" label="" placeholder="Tuliskan Nilai" @input="v=>valuesPendidikan.nilai=v"
      :check="false" :errorText="formErrorsPend.nilai?'failed':''" :hints="formErrorsPend.nilai" />
  </div>
  <div>
        <label> Jurusan <label class="text-red-500 space-x-0 pl-0">*</label></label>
    <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Jurusan"
      class="w-full" :value="valuesPendidikan.jurusan"
      :errorText="formErrorsPend.jurusan?'failed':''" :hints="formErrorsPend.jurusan"
      @input="v=>valuesPendidikan.jurusan=v" :check="false" />
  </div>
  <div>
    <label>Ijasah Terakhir <label class="text-red-500 space-x-0 pl-0">*</label></label>
    <div class="w-full flex items-center">
      <input :disabled="!actionText ? true : false" ref="fileIjz" type="file" accept="application/pdf" class="w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
        :class="{'border-red-500': formErrorsPend.ijazah_foto}" @change="fileIjazah" @input="(v)=>valuesPendidikan.ijazah_foto=v" >
    </div>
  </div>
  <div>
    <label>Catatan <label class="text-red-500 space-x-0 pl-0">*</label></label>
    <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Catatan" type="textarea"
      class="w-full" :value="valuesPendidikan.desc"
      :errorText="formErrorsPend.desc?'failed':''" @input="v=>valuesPendidikan.desc=v"
      :hints="formErrorsPend.desc" :check="false" />
  </div>
    <div>
    <label>Pendidikan Akhir <label class="text-red-500 space-x-0 pl-0">*</label></label>
    <div class="flex items-center space-x-5 ">
      <input :disabled="!actionText ? true : false" type="radio" value="1" v-model="valuesPendidikan.is_pend_terakhir" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
      <label for="aktif_status" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Iya</label>

      <input :disabled="!actionText ? true : false" type="radio" value="0" v-model="valuesPendidikan.is_pend_terakhir" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
      <label for="tidak_aktif_status" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
    </div>
  </div>
 </div>
  <!-- TABLE -->
  <div class="w-full mt-3">
    <TableStatic customClass="h-50vh" ref="detail" :value="detailPendidikan" :columns="[{
        headerName: 'No',
        cellRenderer: !actionText?null:'ButtonGrid',
        valueGetter:p=>p.node.rowIndex + 1,
        cellRendererParams: !actionText?null:{
          showValue: true,
          icon: 'times',
          class: 'btn-text-danger',
          click:(app)=>{
            if (app && app.params) {
              const row = app.params.node.data
              swal.fire({
                icon: 'warning', showDenyButton: true,
                text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
              }).then((res) => {
                if (res.isConfirmed) {
                  detailPendidikan = detailPendidikan.filter((e) => e._id != app.params.node.data._id)
                  app.params.api.applyTransaction({ remove: [app.params.node.data] })
                }
              })
            }
          }
        },
        width: 60,
        sortable: false, resizable: true, filter: false, wrapText: true,
        cellClass: ['justify-center', 'bg-gray-50']
      },
      {
        flex: 1,
        headerName: 'Tingkat',
        field: 'tingkat',
        sortable: false, resizable: true, filter: false, wrapText: true,
        cellClass: ['!border-gray-200', 'justify-center'],
      },
      {
        flex: 1,
        headerName: 'Nama Sekolah',
        field: 'nama_sekolah',
        sortable: false, resizable: true, filter: false, wrapText: true,
        cellClass: ['!border-gray-200', 'justify-center'],
      },
      {
        flex: 1,
        headerName: 'Jurusan',
        field: 'jurusan',
        sortable: false, resizable: true, filter: false, wrapText: true,
        cellClass: ['!border-gray-200', 'justify-center'],
      },
      {
        flex: 1,
        headerName: 'Tahun Masuk',
        field: 'thn_masuk',
        sortable: false, resizable: true, filter: false, wrapText: true,
        cellClass: ['!border-gray-200', 'justify-center'],
      },
      {
        flex: 1,
        headerName: 'Nilai',
        field: 'nilai',
        sortable: false, resizable: true, filter: false, wrapText: true,
        cellClass: ['!border-gray-200', 'justify-center'],
      },
      {
        flex: 1,
        headerName: 'Pendidikan Terakhir',
        cellRenderer: (params)=>{
          if(params.data.is_pend_terakhir === '1'){
            return 'Iya'
          }else{
            return 'Tidak'
          }
        },
        sortable: false, resizable: true, filter: false, wrapText: true,
        cellClass: ['!border-gray-200', 'justify-center'],
      },
      {
        flex: 1,
        headerName: 'Note',
        field: 'desc',
        sortable: false, resizable: true, filter: false, wrapText: true,
        cellClass: ['!border-gray-200', 'justify-center'],
      }
    ]">
      <template #header>
        <button :disabled="!actionText ? true : false" @click="addPendidikan" type="button" class="mr-[15px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
            <icon fa="plus" /> <span>Add to List</span>
          </button>
        <button :disabled="!actionText ? true : false" @click="detailPendidikan = []" type="button" class="bg-[#DD4B39] hover:bg-[#da3c28] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
            <icon fa="trash" /> <span>Remove</span>
          </button>
      </template>
    </TableStatic>
  </div>
  <!-- END TABLE -->
</div>
<!-- KELUARGA -->
<div class="p-4 " v-if="activeTabIndex === 2">  
  <div class="grid <md:grid-cols-1 grid-cols-3 gap-2" >  
          <div>
            <label >Keluarga<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full"
              :value="valuesKeluarga.keluarga_id" label="" placeholder="Pilih Keluarga"
              @input="v=>valuesKeluarga.keluarga_id=v" :errorText="formErrorsKel.keluarga_id?'failed':''"
              :hints="formErrorsKel.keluarga_id" @update:valueFull="(objVal)=>{
                  valuesKeluarga.keluarga = objVal.value
                }" valueField="id" displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    where: `this.group='HUBUNGAN KELUARGA' AND this.is_active='true'`,
                    join: true,
                    selectfield: 'this.id, this.code, this.value, this.is_active'
                  }
                }" :check="false" />
          </div>
          <div>
            <label >Nama<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Nama"
              class="w-full" :value="valuesKeluarga.nama" @input="v=>valuesKeluarga.nama=v"
              :check="false" :errorText="formErrorsKel.nama?'failed':''" :hints="formErrorsKel.nama" />
          </div>
          <div>
            <label >Pendidikan Terakhir<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full"
              :value="valuesKeluarga.pend_terakhir_id" @update:valueFull="(objVal)=>{
                  valuesKeluarga.pendidikan = objVal.value
                }" label="" placeholder="Pilih Pendidikan Terakhir" @input="v=>valuesKeluarga.pend_terakhir_id=v"
              :errorText="formErrorsKel.pend_terakhir_id?'failed':''" :hints="formErrorsKel.pend_terakhir_id"
              valueField="id" displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    where: `this.group='PENDIDIKAN' AND this.is_active='true'`,
                    join: true,
                    selectfield: 'this.id, this.code, this.value, this.is_active'
                  }
                }" :check="false" />
          </div>
          <div>
            <label >Pekerjaan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full"
              :value="valuesKeluarga.pekerjaan_id" @update:valueFull="(objVal)=>{
                  valuesKeluarga.pekerjaan = objVal.value
                }" label="" placeholder="Pilih Pekerjaan" @input="v=>valuesKeluarga.pekerjaan_id=v"
              :errorText="formErrorsKel.pekerjaan_id?'failed':''" :hints="formErrorsKel.pekerjaan_id" valueField="id"
              displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    where: `this.group='PEKERJAAN' AND this.is_active='true'`,
                    join: true,
                    selectfield: 'this.id, this.code, this.value, this.is_active'
                  }
                }" :check="false" />
          </div>
          <div>
            <label >Jenis Kelamin<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full"
              :value="valuesKeluarga.jk_id" @update:valueFull="(objVal)=>{
                  valuesKeluarga.jk = objVal.value
                }" label="" placeholder="Pilih Jenis Kelamin" @input="v=>valuesKeluarga.jk_id=v"
              :errorText="formErrorsKel.jk_id?'failed':''" :hints="formErrorsKel.jk_id" valueField="id"
              displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    where: `this.group='JENIS KELAMIN' AND this.is_active='true'`,
                    join: true,
                    selectfield: 'this.id, this.code, this.value, this.is_active'
                  }
                }" :check="false" />
          </div>
          <div>
            <label >Usia<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Usia" type="number"
              class="w-full" :value="valuesKeluarga.usia" @input="v=>valuesKeluarga.usia=v"
              :check="false" :errorText="formErrorsKel.usia?'failed':''" :hints="formErrorsKel.usia" />
          </div>
          <div>
            <label >Catatan</label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Catatan" type="textarea"
              class="w-full" :value="valuesKeluarga.desc" @input="v=>valuesKeluarga.desc=v"
              :check="false" :errorText="formErrorsKel.desc?'failed':''" :hints="formErrorsKel.desc" />
          </div>
  </div>
  <!-- table -->
         <div class="w-full mt-3">
          <TableStatic customClass="h-50vh" :value="detailKeluarga" :columns="[{
                  headerName: 'No',
                  cellRenderer: !actionText?null:'ButtonGrid',
                  valueGetter:p=>p.node.rowIndex + 1,
                  cellRendererParams: !actionText?null:{
                    showValue: true,
                    icon: 'times',
                    class: 'btn-text-danger',
                    click:(app)=>{
                      if (app && app.params) {
                        const row = app.params.node.data
                        swal.fire({
                          icon: 'warning', showDenyButton: true,
                          text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
                        }).then((res) => {
                          if (res.isConfirmed) {
                            detailKeluarga = detailKeluarga.filter((e) => e._id != app.params.node.data._id)
                            app.params.api.applyTransaction({ remove: [app.params.node.data] })
                          }
                        })
                      }
                    }
                  },
                  width: 60,
                  sortable: false, resizable: true, filter: false, 
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  headerName: 'Keluarga',
                  field: 'keluarga',
                  sortable: false, resizable: true, filter: false, wrapText:true,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  field: 'nama',
                  sortable: false, resizable: true, filter: false, wrapText:true,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Pekerjaan',
                  field: 'pekerjaan',
                  sortable: false, resizable: true, filter: false, wrapText:true,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Pendidikan Terakhir',
                  field: 'pendidikan',
                  sortable: false, resizable: true, filter: false, wrapText:true,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Jenis Kelamin',
                  field: 'jk',
                  sortable: false, resizable: true, filter: false, wrapText:true,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  field: 'usia',
                  sortable: false, resizable: true, filter: false, wrapText:true,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Catatan',
                  field: 'desc',
                  sortable: false, resizable: true, filter: false, wrapText:true,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                ]">
            <template #header>
              <button :disabled="!actionText ? true : false" @click="addKeluarga" type="button" class="mr-[15px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="plus" /> <span>Add to List</span>
                </button>
              <button :disabled="!actionText ? true : false" @click="detailKeluarga = []" type="button" class="bg-[#DD4B39] hover:bg-[#da3c28] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="trash" /> <span>Remove</span>
                </button>
            </template>
          </TableStatic>

        </div>
</div>


      <!-- Form Pelatihan -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[80px] gap-y-[26px] mt-[36px]"
        v-if="activeTabIndex === 3">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama Pelatihan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Nama Pelatihan"
              class="col-span-12 !mt-0 w-full" :value="valuesPelatihan.nama_pel"
              :errorText="formErrorsPel.nama_pel?'failed':''" @input="v=>valuesPelatihan.nama_pel=v"
              :hints="formErrorsPel.nama_pel" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tahun<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" label="" placeholder="Pilih Tahun"
              class="col-span-12 !mt-0 w-full" :value="valuesPelatihan.tahun" @input="v=>valuesPelatihan.tahun=v"
              :options="ArrTahun" :errorText="formErrorsPel.tahun?'failed':''" :hints="formErrorsPel.tahun"
              valueField="key" displayField="key" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama Lembaga<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Nama Lembaga"
              class="col-span-12 !mt-0 w-full" :value="valuesPelatihan.nama_lem"
              :errorText="formErrorsPel.nama_lem?'failed':''" @input="v=>valuesPelatihan.nama_lem=v"
              :hints="formErrorsPel.nama_lem" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Kota<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="valuesPelatihan.kota_id" @input="v=>valuesPelatihan.kota_id=v"
              :errorText="formErrorsPel.kota_id?'failed':''" @update:valueFull="(objVal)=>{
                    valuesPelatihan.kota = objVal.value
                  }" :hints="formErrorsPel.kota_id" label="" placeholder="Pilih Kota" valueField="id"
              displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      join: true,
                      where: `this.group='KOTA'`,
                      paginate: 1000
                    }
                  }" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
          <TableStatic customClass="h-50vh" ref="detail" :value="detailPelatihan" :columns="[{
                  headerName: 'No',
                  cellRenderer: !actionText?null:'ButtonGrid',
                  valueGetter:p=>p.node.rowIndex + 1,
                  cellRendererParams: !actionText?null:{
                    showValue: true,
                    icon: 'times',
                    class: 'btn-text-danger',
                    click:(app)=>{
                      if (app && app.params) {
                        const row = app.params.node.data
                        swal.fire({
                          icon: 'warning', showDenyButton: true,
                          text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
                        }).then((res) => {
                          if (res.isConfirmed) {
                            app.params.api.applyTransaction({ remove: [app.params.node.data] })
                            detailPelatihan = detailPelatihan.filter((e) => e._id != app.params.node.data._id)
                          }
                        })
                      }
                    }
                  },
                  width: 60,
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  headerName: 'Nama Pelatihan',
                  field: 'nama_pel',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Nama Lembaga',
                  field: 'nama_lem',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Kota',
                  field: 'kota',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  field: 'tahun',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                ]">
            <template #header>
              <button :disabled="!actionText ? true : false" @click="addPelatihan" type="button" class="mr-[15px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="plus" /> <span>Add to List</span>
                </button>
              <button :disabled="!actionText ? true : false" @click="detailPelatihan = []" type="button" class="bg-[#DD4B39] hover:bg-[#da3c28] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="trash" /> <span>Remove</span>
                </button>
            </template>
          </TableStatic>

        </div>
      </div>

      <!-- Form Prestasi -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[80px] gap-y-[26px] mt-[36px]"
        v-if="activeTabIndex === 4">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tingkat<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" label="" placeholder="Pilih Tingkat"
              class="col-span-12 !mt-0 w-full" :value="valuesPrestasi.tingkat_pres_id" @update:valueFull="(objVal)=>{
                  valuesPrestasi.tingkat = objVal.value
                }" @input="v=>valuesPrestasi.tingkat_pres_id=v" :errorText="formErrorsPres.tingkat_pres_id?'failed':''"
              :hints="formErrorsPres.tingkat_pres_id" valueField="id" displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    join: true,
                    where: `this.group='PENDIDIKAN' AND this.is_active='true'`,
                    paginate: 1000
                  }
                }" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tahun<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" label="" placeholder="Pilih Tahun"
              class="col-span-12 !mt-0 w-full" :value="valuesPrestasi.tahun" @input="v=>valuesPrestasi.tahun=v"
              :options="ArrTahun" :errorText="formErrorsPres.tahun?'failed':''" :hints="formErrorsPres.tahun"
              valueField="key" displayField="key" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Prestasi / Penghargaan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Prestasi / Penghargaan"
              class="col-span-12 !mt-0 w-full" :value="valuesPrestasi.nama_pres"
              :errorText="formErrorsPres.nama_pres?'failed':''" @input="v=>valuesPrestasi.nama_pres=v"
              :hints="formErrorsPres.nama_pres" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
          <TableStatic customClass="h-50vh" ref="detail" :value="detailPrestasi" :columns="[{
                  headerName: 'No',
                  cellRenderer: !actionText?null:'ButtonGrid',
                  valueGetter:p=>p.node.rowIndex + 1,
                  cellRendererParams: !actionText?null:{
                    showValue: true,
                    icon: 'times',
                    class: 'btn-text-danger',
                    click:(app)=>{
                      if (app && app.params) {
                        const row = app.params.node.data
                        swal.fire({
                          icon: 'warning', showDenyButton: true,
                          text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
                        }).then((res) => {
                          if (res.isConfirmed) {
                            detailPrestasi = detailPrestasi.filter((e) => e._id != app.params.node.data._id)
                            app.params.api.applyTransaction({ remove: [app.params.node.data] })
                          }
                        })
                      }
                    }
                  },
                  width: 60,
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  field: 'tingkat',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  field: 'nama_pres',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  field: 'tahun',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                ]">
            <template #header>
              <button :disabled="!actionText ? true : false" @click="addPrestasi" type="button" class="mr-[15px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="plus" /> <span>Add to List</span>
                </button>
              <button :disabled="!actionText ? true : false" @click="detailPrestasi = []" type="button" class="bg-[#DD4B39] hover:bg-[#da3c28] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="trash" /> <span>Remove</span>
                </button>
            </template>
          </TableStatic>

        </div>
      </div>

      <!-- Form Organisasi -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[80px] gap-y-[26px] mt-[36px]"
        v-if="activeTabIndex === 5">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama Organisasi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Nama Organisasi"
              class="col-span-12 !mt-0 w-full" :value="valuesOrganisasi.nama"
              :errorText="formErrorsOrg.nama?'failed':''" @input="v=>valuesOrganisasi.nama=v"
              :hints="formErrorsOrg.nama" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tahun<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" label="" placeholder="Pilih Tahun"
              class="col-span-12 !mt-0 w-full" :value="valuesOrganisasi.tahun" @input="v=>valuesOrganisasi.tahun=v"
              :options="ArrTahun" :errorText="formErrorsOrg.tahun?'failed':''" :hints="formErrorsOrg.tahun"
              valueField="key" displayField="key" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Jenis Organisasi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" label=""
              placeholder="Pilih Jenis Organisasi" class="col-span-12 !mt-0 w-full"
              :value="valuesOrganisasi.jenis_org_id" @input="v=>valuesOrganisasi.jenis_org_id=v"
              :errorText="formErrorsOrg.jenis_org_id?'failed':''" @update:valueFull="(objVal)=>{
                  valuesOrganisasi.jenis = objVal.value
                }" :hints="formErrorsOrg.jenis_org_id" valueField="id" displayField="value" :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: {
                    'Content-Type': 'Application/json',
                    Authorization: `${store.user.token_type} ${store.user.token}`
                  },
                  params: {
                    simplest: true,
                    transform: false,
                    join: true,
                    where: `this.group='JENIS ORGANISASI' AND this.is_active='true'`,
                    paginate: 1000
                  }
                }" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Kota<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" label="" placeholder="Pilih Tingkat"
              class="col-span-12 !mt-0 w-full" :value="valuesOrganisasi.kota_id" @input="v=>valuesOrganisasi.kota_id=v"
              :errorText="formErrorsOrg.kota_id?'failed':''" :hints="formErrorsOrg.kota_id" @update:valueFull="(objVal)=>{
                    valuesOrganisasi.kota = objVal.value
                  }" valueField="id" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      join: true,
                      where: `this.group='KOTA'`,
                      paginate: 1000
                    }
                  }" :check="false" />
          </div>
        </div>

        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Posisi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full" :value="valuesOrganisasi.posisi"
              label="" placeholder="Tuliskan Posisi" :errorText="formErrorsOrg.posisi?'failed':''"
              @input="v=>valuesOrganisasi.posisi=v" :hints="formErrorsOrg.posisi" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
          <TableStatic customClass="h-50vh" ref="detail" :value="detailOrganisasi" :columns="[{
                  headerName: 'No',
                  cellRenderer: !actionText?null:'ButtonGrid',
                  valueGetter:p=>p.node.rowIndex + 1,
                  cellRendererParams: !actionText?null:{
                    showValue: true,
                    icon: 'times',
                    class: 'btn-text-danger',
                    click:(app)=>{
                      if (app && app.params) {
                        const row = app.params.node.data
                        swal.fire({
                          icon: 'warning', showDenyButton: true,
                          text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
                        }).then((res) => {
                          if (res.isConfirmed) {
                            detailOrganisasi = detailOrganisasi.filter((e) => e._id != app.params.node.data._id)
                            app.params.api.applyTransaction({ remove: [app.params.node.data] })
                          }
                        })
                      }
                    }
                  },
                  width: 60,
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  headerName: 'Nama Organisasi',
                  field: 'nama',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Jenis Organisasi',
                  field: 'jenis',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  field: 'posisi',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  field: 'tahun',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Kota',
                  field: 'kota',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                ]">
            <template #header>
              <button :disabled="!actionText ? true : false" @click="addOrganisasi" type="button" class="mr-[15px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="plus" /> <span>Add to List</span>
                </button>
              <button :disabled="!actionText ? true : false" @click="detailOrganisasi = []" type="button" class="bg-[#DD4B39] hover:bg-[#da3c28] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="trash" /> <span>Remove</span>
                </button>
            </template>
          </TableStatic>

        </div>
      </div>

      <!-- Form Bahasa -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[80px] gap-y-[26px] mt-[36px]"
        v-if="activeTabIndex === 6">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Bahasa yang Dikuasai<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Bahasa Yang Dikuasai"
              class="col-span-12 !mt-0 w-full" :value="valuesBahasa.bhs_dikuasai"
              :errorText="formErrorsBhs.bhs_dikuasai?'failed':''" @input="v=>valuesBahasa.bhs_dikuasai=v"
              :hints="formErrorsBhs.bhs_dikuasai" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nilai Lisan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" label="" placeholder="Contoh: 89"
              class="col-span-12 !mt-0 w-full" :value="valuesBahasa.nilai_lisan"
              :errorText="formErrorsBhs.nilai_lisan?'failed':''" @input="v=>valuesBahasa.nilai_lisan=v"
              :hints="formErrorsBhs.nilai_lisan" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Level Lisan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Contoh: 3-Intermidate"
              class="col-span-12 !mt-0 w-full" :value="valuesBahasa.level_lisan"
              :errorText="formErrorsBhs.level_lisan?'failed':''" @input="v=>valuesBahasa.level_lisan=v"
              :hints="formErrorsBhs.level_lisan" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nilai Tertulis<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" label="" placeholder="Contoh 89"
              class="col-span-12 !mt-0 w-full" :value="valuesBahasa.nilai_tertulis"
              :errorText="formErrorsBhs.nilai_tertulis?'failed':''" @input="v=>valuesBahasa.nilai_tertulis=v"
              :hints="formErrorsBhs.nilai_tertulis" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Level Tertulis<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Contoh: 3-Intermidate"
              class="col-span-12 !mt-0 w-full" :value="valuesBahasa.level_tertulis"
              :errorText="formErrorsBhs.level_tertulis?'failed':''" @input="v=>valuesBahasa.level_tertulis=v"
              :hints="formErrorsBhs.level_tertulis" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
          <TableStatic customClass="h-50vh" ref="detail" :value="detailBahasa" :columns="[{
                  headerName: 'No',
                  cellRenderer: !actionText?null:'ButtonGrid',
                  valueGetter:p=>p.node.rowIndex + 1,
                  cellRendererParams: !actionText?null:{
                    showValue: true,
                    icon: 'times',
                    class: 'btn-text-danger',
                    click:(app)=>{
                      if (app && app.params) {
                        const row = app.params.node.data
                        swal.fire({
                          icon: 'warning', showDenyButton: true,
                          text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
                        }).then((res) => {
                          if (res.isConfirmed) {
                            detailBahasa = detailBahasa.filter((e) => e._id != app.params.node.data._id)
                            app.params.api.applyTransaction({ remove: [app.params.node.data] })
                          }
                        })
                      }
                    }
                  },
                  width: 60,
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  headerName: 'Bahasa',
                  field: 'bhs_dikuasai',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Nilai Lisan',
                  field: 'nilai_lisan',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Level Lisan',
                  field: 'level_lisan',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Nilai Tertuis',
                  field: 'nilai_tertulis',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Level Tertulis',
                  field: 'level_tertulis',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                ]">
            <template #header>
              <button :disabled="!actionText ? true : false" @click="addBahasa" type="button" class="mr-[15px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="plus" /> <span>Add to List</span>
                </button>
              <button :disabled="!actionText ? true : false" @click="detailPengalaman = []" type="button" class="bg-[#DD4B39] hover:bg-[#da3c28] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="trash" /> <span>Remove</span>
                </button>
            </template>
          </TableStatic>

        </div>
      </div>

      <!-- Form Pengalaman Kerja -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[80px] gap-y-[26px] mt-[36px]"
        v-if="activeTabIndex === 7">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama Perusahaan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Nama Perusahaan"
              class="col-span-12 !mt-0 w-full" :value="valuesPengalaman.instansi"
              :errorText="formErrorsPK.instansi?'failed':''" @input="v=>valuesPengalaman.instansi=v"
              :hints="formErrorsPK.instansi" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Bidang Usaha<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Bidang Usaha"
              class="col-span-12 !mt-0 w-full" :value="valuesPengalaman.bidang_usaha"
              :errorText="formErrorsPK.bidang_usaha?'failed':''" @input="v=>valuesPengalaman.bidang_usaha=v"
              :hints="formErrorsPK.bidang_usaha" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">No. Telp<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan No Telp" type="number"
              class="col-span-12 !mt-0 w-full" :value="valuesPengalaman.no_tlp"
              :errorText="formErrorsPK.no_tlp?'failed':''" @input="v=>valuesPengalaman.no_tlp=v"
              :hints="formErrorsPK.no_tlp" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Posisi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Posisi" class="col-span-12 !mt-0 w-full"
              :value="valuesPengalaman.posisi" :errorText="formErrorsPK.posisi?'failed':''"
              @input="v=>valuesPengalaman.posisi=v" :hints="formErrorsPK.posisi" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tahun Masuk<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="valuesPengalaman.thn_masuk" label="" placeholder="Pilih Tahun Masuk"
              @input="v=>valuesPengalaman.thn_masuk=v" :options="ArrTahun"
              :errorText="formErrorsPK.thn_masuk?'failed':''" :hints="formErrorsPK.thn_masuk" valueField="key"
              displayField="key" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tahun Keluar<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="valuesPengalaman.thn_keluar" label="" placeholder="Pilih Tahun Keluar"
              @input="v=>valuesPengalaman.thn_keluar=v" :options="ArrTahun"
              :errorText="formErrorsPK.thn_keluar?'failed':''" :hints="formErrorsPK.thn_keluar" valueField="key"
              displayField="key" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Alamat Kantor<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Alamat Kantor" type="textarea"
              class="col-span-12 !mt-0 w-full" :value="valuesPengalaman.alamat_kantor"
              :errorText="formErrorsPK.alamat_kantor?'failed':''" @input="v=>valuesPengalaman.alamat_kantor=v"
              :hints="formErrorsPK.alamat_kantor" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Kota<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="valuesPengalaman.kota_id" @input="v=>valuesPengalaman.kota_id=v"
              :errorText="formErrorsPK.kota_id?'failed':''" :hints="formErrorsPK.kota_id" label=""
              placeholder="Pilih Kota" valueField="id" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      join: true,
                      where: `this.group='KOTA'`,
                      paginate: 1000
                    }
                  }" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Surat Refrensi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <div class="col-span-12 flex items-center">
              <input :disabled="!actionText ? true : false" ref="fileSurat" type="file" accept="application/pdf" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrorsPK.surat_referensi}" @change="fileSrtRef" @input="(v)=>valuesPengalaman.surat_referensi=v" >

            </div>
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
          <TableStatic customClass="h-50vh" ref="detail" :value="detailPengalaman" :columns="[{
                  headerName: 'No',
                  cellRenderer: !actionText?null:'ButtonGrid',
                  valueGetter:p=>p.node.rowIndex + 1,
                  cellRendererParams: !actionText?null:{
                    showValue: true,
                    icon: 'times',
                    class: 'btn-text-danger',
                    click:(app)=>{
                      if (app && app.params) {
                        const row = app.params.node.data
                        swal.fire({
                          icon: 'warning', showDenyButton: true,
                          text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
                        }).then((res) => {
                          if (res.isConfirmed) {
                            detailPengalaman = detailPengalaman.filter((e) => e._id != app.params.node.data._id)
                            app.params.api.applyTransaction({ remove: [app.params.node.data] })
                          }
                        })
                      }
                    }
                  },
                  width: 60,
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  headerName: 'Nama Instansi',
                  field: 'instansi',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Bidang Usaha',
                  field: 'bidang_usaha',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  field: 'posisi',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Tahun Masuk',
                  field: 'thn_masuk',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Tahun Keluar',
                  field: 'thn_keluar',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                {
                  flex: 1,
                  headerName: 'Alamat Kantor',
                  field: 'alamat_kantor',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['!border-gray-200', 'justify-center'],
                },
                ]">
            <template #header>
              <button :disabled="!actionText ? true : false" @click="addPengalaman" type="button" class="mr-[15px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="plus" /> <span>Add to List</span>
                </button>
              <button :disabled="!actionText ? true : false" @click="detailPengalaman = []" type="button" class="bg-[#DD4B39] hover:bg-[#da3c28] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="trash" /> <span>Remove</span>
                </button>
            </template>
          </TableStatic>

        </div>
      </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
        <button @click="onBack" v-if="!isProfile" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Batal
          </button>
        <button v-show="actionText || isProfile" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
      </div>
      <!-- FORM END -->
    </div>
  </div>
</div>
@endverbatim
@endif