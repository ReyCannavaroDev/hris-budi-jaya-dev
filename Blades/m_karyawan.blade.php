@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'"
        :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
        <icon fa="plus" />
        Tambah Data
      </RouterLink>
    </template>
  </TableApi>
</div>
@else

@verbatim

<div class="flex flex-col gap-y-2 scroll-auto max-h-[470px]">
  <div class="flex gap-x-1 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white ">

      <!-- HEADER START -->
      <div class="flex flex-col items-start mb-2">
        <h1 class="text-[24px] mb-[10px] font-bold">
          Form Karyawan
        </h1>
      </div>
      <!-- HEADER END -->
      <div class="flex items-stretch w-full text-sm overflow-x-auto">
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 0}"
            @click="activeTabIndex = 0"
          >
            Informasi
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 1}"
            @click="activeTabIndex = 1"
          >
            Pendidikan
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 hover:border-blue-600 hover:text-blue-600 duration-300 border-gray-100 p-3"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 2}"
            @click="activeTabIndex = 2"
          >
            Keluarga
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 3}"
            @click="activeTabIndex = 3"
          >
            Pelatihan
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 4}"
            @click="activeTabIndex = 4"
          >
            Prestasi
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 5}"
            @click="activeTabIndex = 5"
          >
            Organisasi
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 6}"
            @click="activeTabIndex = 6"
          >
            Bahasa
          </button>
        <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 7}"
            @click="activeTabIndex = 7"
          >
            Pengalaman Kerja
          </button>
      </div>

      <!-- Form Informasi -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[80px] gap-y-[26px] mt-[36px]"
        v-if="activeTabIndex === 0">
        <!-- NOT PROFILE -->
        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Direktorat<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_dir_id" @input="v=>values.m_dir_id=v" :errorText="formErrors.m_dir_id?'failed':''"
              @update:valueFull="(objVal)=>{
                  values.m_divisi_id = null
                  values.m_dept_id = null
                }" label="" placeholder="Pilih Direktorat" :hints="formErrors.m_dir_id" :api="{
                    url: `${store.server.url_backend}/operation/m_dir`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      where: `this.is_active = 'true'`
                    }
                }" valueField="id" displayField="nama" :check="false" />
          </div>
        </div>
        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Divisi<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_divisi_id" @input="v=>values.m_divisi_id=v"
              :errorText="formErrors.m_divisi_id?'failed':''" @update:valueFull="(objVal)=>{
                  values.m_dept_id = null
                }" label="" placeholder="Pilih Divisi" :hints="formErrors.m_divisi_id" :api="{
                    url: `${store.server.url_backend}/operation/m_divisi`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      where: `this.is_active = 'true'`
                    }
                }" valueField="id" displayField="nama" :check="false" />
          </div>
        </div>
        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Departemen<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_dept_id" @input="v=>values.m_dept_id=v" :errorText="formErrors.m_dept_id?'failed':''"
              label="" placeholder="Pilih Departemen" :hints="formErrors.m_dept_id" :api="{
                    url: `${store.server.url_backend}/operation/m_dept`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      where: `m_divisi_id=${values.m_divisi_id} AND this.is_active = 'true'`
                    }
                }" valueField="id" displayField="nama" :check="false" />
          </div>
        </div>
        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Posisi<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_posisi_id" @input="v=>values.m_posisi_id=v" @update:valueFull="(items)=>{
                  values.m_standart_gaji_id = null
                  $log('ikiposisi')
                }" :errorText="formErrors.m_posisi_id?'failed':''" label="" placeholder="Pilih Posisi"
              :hints="formErrors.m_posisi_id" :api="{
                    url: `${store.server.url_backend}/operation/m_posisi`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                }" valueField="id" displayField="desc_kerja" :check="false" />
          </div>
        </div>
        <!-- <div v-if="!isProfile" class="col-span-8 md:col-span-6">
            <div class="grid grid-cols-12 items-center">
              <label class="col-span-12">Zona<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldSelect
                :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
                :value="values.m_zona_id" @input="v=>{
                  values.m_zona_id=v
                  setStandartGaji()
                  }"
                :errorText="formErrors.m_zona_id?'failed':''" 
                @update:valueFull="(items)=>{
                  values.m_standart_gaji_id=null
                }"
                label="" placeholder="Pilih Zona"
                :hints="formErrors.m_zona_id"
                :api="{
                    url: `${store.server.url_backend}/operation/m_zona`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                }"
                valueField="id" displayField="nama" :check="false"
              />
            </div>
          </div> -->

              <div v-if="!isProfile" class="col-span-8 md:col-span-6">
                <div class="grid grid-cols-12 items-center">
                  <label class="col-span-12">
            Grading
            <span class="text-red-500 space-x-0 pl-0"></span>
          </label>
                  <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="col-span-12 !mt-0 w-full"
                    :value="values.grading_id" @input="v => {
              if (v) {
                
                values.grading_id = v;
              } else {
                values.tipe_grade = null;
              }
            }" @update:valueFull="obj => {
              if (obj) {
                values.grading_id = obj.id;
                values.tipe_grade = obj.id;
              } else {
                values.grading_id = null;
              }
            }" :errorText="formErrors.grading_id ? 'failed' : ''" displayField="value" :hints="formErrors.grading_id" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 
                'Content-Type': 'Application/json', 
                Authorization: `${store.user.token_type} ${store.user.token}`
              },
              params: {
                where: `this.group = 'GRADING' AND this.is_active = 'true'`,
                selectfield: 'this.id, this.value, this.code'
              }
            }" valueField="id" :check="false" label="" placeholder="Pilih Grading" />
                </div>
              </div>

                <div v-if="!isProfile" class="col-span-8 md:col-span-6">
                <div class="grid grid-cols-12 items-center">
                  <label class="col-span-12">
            Tipe Grading
            <span class="text-red-500 space-x-0 pl-0"></span>
          </label>
                  <FieldSelect :bind="{ disabled: true, clearable: false }" class="col-span-12 !mt-0 w-full"
                    :value="values.tipe_grade" @input="v => {
              if (v) {
                values.tipe_grade = v;
              } else {
                values.tipe_grade = null;
              }
            }" @update:valueFull="obj => {
              if (obj) {
                values.tipe_grade = obj.id;
              } else {
                values.tipe_grade = null;
              }
            }" :errorText="formErrors.tipe_grade ? 'failed' : ''" displayField="value_2" :hints="formErrors.tipe_grade" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: { 
                'Content-Type': 'Application/json', 
                Authorization: `${store.user.token_type} ${store.user.token}`
              },
              params: {
                join:true
              }
            }" valueField="id" :check="false" label="" placeholder="Pilih Grading" />
                </div>






          <!-- div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Grading<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.tipe_grade" @input="v=>{
                      if (v) {
                          values.tipe_grade=v       
                        } else {
                          values.tipe_grade = null,
                        }
                    }" @update:valueFull="obj => {
                        if (obj) {
                          values.tipe_grade=obj.id,
                        } else {
                          values.tipe_grade=null;
                        }
                      }" :errorText="formErrors.tipe_grade?'failed':''" displayField="value2"
              :hints="formErrors.tipe_grade" :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        where: `this.group = 'GRADING' AND this.is_active='true'`,
                        selectfield:'this.id,this.value,this.code'
                      }
                }" valueField="id" :check="false" label="" placeholder="Pilih Grading" />
          </div> -->

        </div>

        <!-- <div v-if="!isProfile" class="col-span-8 md:col-span-6">
            <div class="grid grid-cols-12 items-center">
              <label class="col-span-12">Standart Gaji<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldSelect
                  :bind="{ disabled: !actionText, clearable:false }" 
                  class="col-span-12 !mt-0 w-full"
                  :value="values.m_standart_gaji_id" 
                  @input="v => values.m_standart_gaji_id = v"
                  :errorText="formErrors.m_standart_gaji_id ? 'failed' : ''" 
                  label="" 
                  placeholder="Pilih Standart Gaji"
                  :hints="formErrors.m_standart_gaji_id"
                  :api="{
                      url: `${store.server.url_backend}/operation/m_standart_gaji`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                          simplest: true,
                          where: `this.is_active='true' `
                          //AND this.m_zona_id = ${values.m_zona_id ?? 0} AND this.grading_id = ${values.grading_id ?? 0}
                      }
                  }"
                  valueField="id" 
                  displayField="kode" 
                  :check="false"
              />
            </div>
          </div> -->

        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tipe Jam Kerja<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.tipe_jam_kerja_id" @input="v => values.tipe_jam_kerja_id = v"
              :errorText="formErrors.tipe_jam_kerja_id ? 'failed' : ''" label="" @update:valueFull="changeTipeJamKerja"
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
        </div>

        <div v-if="!isProfile && values['tipe_jam_kerja.value'] == 'OFFICE'" class="col-span-8 md:col-span-6">
          <div v-if="values['tipe_jam_kerja.value'] == 'OFFICE'" class="grid grid-cols-12 items-center">
            <label class="col-span-12">Jadwal Kerja<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldPopup :bind="{ readonly: true }" class="col-span-12 !mt-0 w-full" :value="values.t_jadwal_kerja_id"
              @input="(v)=>values.t_jadwal_kerja_id=v" :errorText="formErrors.t_jadwal_kerja_id?'failed':''"
              :hints="formErrors.t_jadwal_kerja_id" valueField="id" displayField="nomor" @update:valueFull="(objVal)=>{  
                  values.t_jadwal_kerja_ket = objVal.keterangan
                }" :api="{
                  url: `${store.server.url_backend}/operation/t_jadwal_kerja`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    where: `this.status = 'POSTED'`,
                    searchfield:'this.id, this.nomor, this.keterangan',
                  }
                }" placeholder="Pilih Jadwal Kerja" label="" :check="false" :columns="[{
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
          <i class="mt-2">{{values.t_jadwal_kerja_ket}}</i>
        </div>

        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.is_active" @input="v=>values.is_active=v" :errorText="formErrors.is_active?'failed':''"
              :hints="formErrors.is_active" label="" placeholder="Pilih Status"
              :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]" valueField="id"
              displayField="key" :check="false" />
          </div>
        </div>

        <Div> </Div>

        <!-- <div class="col-span-8 md:col-span-6">
          </div> -->
        <!-- NOT PROFILE -->
        <h2 class="font-bold text-[18px] col-span-8 md:col-span-6 col-start-1">Data Karyawan</h2>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">NIK<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true}" type="text" class="col-span-12 !mt-0 w-full" :value="values.kode" label=""
              placeholder="Generated By System" :errorText="formErrors.kode?'failed':''" @input="v=>values.kode=v"
              :hints="formErrors.kode" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Atasan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldPopup :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full" :value="values.atasan_id"
              @input="(v)=>values.atasan_id=v" :errorText="formErrors.atasan_id?'failed':''"
              :hints="formErrors.atasan_id" valueField="id" displayField="nama_lengkap" :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.is_active = true`,
                  searchfield: 'this.nik, this.nama_lengkap, this.nama_depan, this.nama_belakang, m_zona.nama, m_dir.nama, m_divisi.nama, m_dept.nama'
                }
              }" placeholder="Cari Nomor Induk Karyawan" label="" :check="false" :columns="[{
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Pilih Lokasi</span></label>
            <FieldSelect class="w-full !mt-0 col-span-12" :bind="{ disabled: !actionText, clearable:false }"
              :value="values.presensi_lokasi_default_id" @input="v=>values.presensi_lokasi_default_id=v"
              :errorText="formErrors.presensi_lokasi_default_id?'failed':''"
              :hints="formErrors.presensi_lokasi_default_id" label="" valueField="id" displayField="nama" :api="{
                    url: `${store.server.url_backend}/operation/presensi_lokasi`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      selectfield: 'this.id, this.nama, this.lat, this.long'
                    }
                  }" placeholder="Pilih Master Lokasi" :check="false" />
          </div>
        </div>
        <!-- <div class="col-span-8 md:col-span-6">
            <div class="grid grid-cols-12 items-center">
            </div>
          </div> -->
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama Karyawan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-6 !mt-0 w-full" :value="values.nama_depan"
              label="" placeholder="Tuliskan Nama Depan" :errorText="formErrors.nama_depan?'failed':''"
              @input="v=>values.nama_depan=v" :hints="formErrors.nama_depan" :check="false" />
            <FieldX :bind="{ readonly: !actionText }" class="col-span-6 !mt-0 w-full" :value="values.nama_belakang"
              label="" placeholder="Tuliskan Nama Belakang" :errorText="formErrors.nama_belakang?'failed':''"
              @input="v=>values.nama_belakang=v" :hints="formErrors.nama_belakang" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama Panggilan Karyawan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full" :value="values.nama_panggilan"
              label="" placeholder="Tuliskan Nama Panggilan Karyawan" :errorText="formErrors.nama_panggilan?'failed':''"
              @input="v=>values.nama_panggilan=v" :hints="formErrors.nama_panggilan" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Jenis Kelamin<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.jk_id" label="" placeholder="Pilih Jenis Kelamin" @input="v=>values.jk_id=v"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tempat, Tanggal Lahir<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-6 !mt-0 w-full"
              :value="values.tempat_lahir" @input="v=>values.tempat_lahir=v"
              :errorText="formErrors.tempat_lahir?'failed':''" :hints="formErrors.tempat_lahir" label=""
              placeholder="Pilih Kota" valueField="value" displayField="value" :api="{
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
            <FieldX :bind="{ readonly: !actionText }" type="date" class="col-span-6 !mt-0 w-full"
              :value="values.tgl_lahir" label="" placeholder="Pilih Tanggal"
              :errorText="formErrors.tgl_lahir?'failed':''" @input="v=>values.tgl_lahir=v" :hints="formErrors.tgl_lahir"
              :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Alamat Tinggal<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="textarea" class="col-span-12 !mt-0 w-full"
              :value="values.alamat_domisili" label="" placeholder="Tuliskan Alamat"
              :errorText="formErrors.alamat_domisili?'failed':''" @input="v=>values.alamat_domisili=v"
              :hints="formErrors.alamat_domisili" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Provinsi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.provinsi_id" @input="v=>values.provinsi_id=v"
              :errorText="formErrors.provinsi_id?'failed':''" @update:valueFull="(objVal)=>{
                    values.kota_id = '',
                    values.kecamatan_id = '',
                    values.kode_pos = ''
                  }" :hints="formErrors.provinsi_id" label="" placeholder="Pilih Provinsi" valueField="id"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Kota<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.kota_id" @input="v=>values.kota_id=v" :errorText="formErrors.kota_id?'failed':''"
              @update:valueFull="(objVal)=>{
                    values.kecamatan_id = '',
                    values.kode_pos = ''
                  }" :hints="formErrors.kota_id" label="" placeholder="Pilih Kota" valueField="id" displayField="value"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Kecamatan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.kecamatan_id" @input="v=>values.kecamatan_id=v"
              :errorText="formErrors.kecamatan_id?'failed':''" @update:valueFull="(objVal)=>{
                    values.kode_pos = ''
                  }" :hints="formErrors.kecamatan_id" label="" placeholder="Pilih Kecamatan" valueField="id"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Kode Pos<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.kode_pos" label="" placeholder="Tuliskan Kode Pos"
              :errorText="formErrors.kode_pos?'failed':''" @input="v=>values.kode_pos=v" :hints="formErrors.kode_pos"
              :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">No. Telepon<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.no_tlp" label="" placeholder="Tuliskan Nomer Telepon"
              :errorText="formErrors.no_tlp?'failed':''" @input="v=>values.no_tlp=v" :hints="formErrors.no_tlp"
              :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">No. Telepon Lainnya</label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.no_tlp_lainnya" label="" placeholder="Tuliskan Nomer Telepon Lainnya"
              :errorText="formErrors.no_tlp_lainnya?'failed':''" @input="v=>values.no_tlp_lainnya=v"
              :hints="formErrors.no_tlp_lainnya" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">No. Telepon Darurat<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.no_darurat" label="" placeholder="Tuliskan Nomer Telepon Darurat"
              :errorText="formErrors.no_darurat?'failed':''" @input="v=>values.no_darurat=v"
              :hints="formErrors.no_darurat" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama Kontak Darurat<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.nama_kontak_darurat" label="" placeholder="Tuliskan Nama Kontak Darurat"
              :errorText="formErrors.nama_kontak_darurat?'failed':''" @input="v=>values.nama_kontak_darurat=v"
              :hints="formErrors.nama_kontak_darurat" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Hubungan Dengan Karyawan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full" :value="values.hub_dgn_karyawan"
              label="" placeholder="Tulis Hubungan Dengan Karyawan" :errorText="formErrors.hub_dgn_karyawan?'failed':''"
              @input="v=>values.hub_dgn_karyawan=v" :hints="formErrors.hub_dgn_karyawan" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Agama<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.agama_id" @input="v=>values.agama_id=v" :errorText="formErrors.agama_id?'failed':''"
              :hints="formErrors.agama_id" label="" placeholder="Pilih Agama" valueField="id" displayField="value" :api="{
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Golongan Darah<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.gol_darah_id" @input="v=>values.gol_darah_id=v"
              :errorText="formErrors.gol_darah_id?'failed':''" :hints="formErrors.gol_darah_id" label=""
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Status Pernikahan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.status_nikah_id" @input="v=>values.status_nikah_id=v"
              :errorText="formErrors.status_nikah_id?'failed':''" :hints="formErrors.status_nikah_id" label=""
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Jumlah Tanggungan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.tanggungan_id" @input="v=>values.tanggungan_id=v"
              :errorText="formErrors.tanggungan_id?'failed':''" :hints="formErrors.tanggungan_id" label=""
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Limit Potong<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.limit_potong" :errorText="formErrors.limit_potong?'failed':''"
              @input="v=>values.limit_potong=v" :hints="formErrors.limit_potong" placeholder="Limit Potong"
              :check="false" />
          </div>
        </div>
        <h2 class="font-bold text-[18px] col-span-8 md:col-span-6">Info Lain</h2>
        <div class="col-span-8 md:col-span-6">
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-1 ">
            <div class="grid grid-cols-12 col-span-6">
              <label class="col-span-12">Jatah Cuti<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 !mt-0 w-full"
                :value="values.cuti_satu_hari" label="" :errorText="formErrors.cuti_satu_hari?'failed':''"
                @input="v=>values.cuti_satu_hari=v" :hints="formErrors.cuti_satu_hari" :check="false" />
            </div>
            <div class="grid grid-cols-12 col-span-6">
              <label class="col-span-12">Sisa Cuti<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 !mt-0 w-full"
                :value="values.sisa_cuti_satu_hari" label="" :errorText="formErrors.sisa_cuti_satu_hari?'failed':''"
                @input="v=>values.sisa_cuti_satu_hari=v" :hints="formErrors.sisa_cuti_satu_hari" :check="false" />
            </div>
          </div>
        </div>

        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-1 ">
            <div class="grid grid-cols-12 col-span-6">
              <label class="col-span-12">Tanggal Masuk Kerja<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldX :bind="{ readonly: !actionText, disabled:!actionText }" type="date"
                class="col-span-12 !mt-0 w-full" :value="values.tgl_masuk" label=""
                :errorText="formErrors.tgl_masuk?'failed':''" @input="v=>values.tgl_masuk=v"
                :hints="formErrors.tgl_masuk" :check="false" />
            </div>
            <div class="grid grid-cols-12 col-span-6">
              <label class="col-span-12">Tanggal Berhenti Kerja<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" type="date"
                class="col-span-12 !mt-0 w-full" :value="values.tgl_berhenti" label=""
                :errorText="formErrors.tgl_berhenti?'failed':''" @input="v=>values.tgl_berhenti=v"
                :hints="formErrors.tgl_berhenti" :check="false" />
            </div>
          </div>
        </div>

        <!-- <div class="col-span-8 md:col-span-6">
            <div class="grid grid-cols-12 items-center">
              <label class="col-span-12">Expired Cuti<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldX :bind="{ readonly: !actionText, disabled: !actionText }" type="date" class="col-span-12 !mt-0 w-full"
                :value="values.exp_date_cuti" label="" :errorText="formErrors.exp_date_cuti?'failed':''"
                @input="v=>values.exp_date_cuti=v" :hints="formErrors.exp_date_cuti" :check="false"
              />
            </div>
          </div>
          <div class="col-span-8 md:col-span-6">
          </div> -->
        <h2 class="font-bold text-[18px] col-span-8 md:col-span-6">Berkas Karyawan</h2>
        <div class="col-span-8 md:col-span-6">
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Foto Karyawan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <div class="col-span-12 flex items-center">
              <input :disabled="!actionText ? true : false" ref="refPasFoto" type="file" accept="image/*" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.pas_foto}" id="inputPasFoto" @change="imageChange">

            </div>
            <img :src="urlPasFoto" class="col-span-12 !mt-0 w-[231px]">
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Foto KTP<label class="text-red-500 space-x-0 pl-0"></label></label>
            <div class="col-span-12 flex items-center">
              <input :disabled="!actionText ? true : false" type="file" accept="image/*" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.ktp_foto}" id="inputKTPFoto" @change="imageChange">

            </div>
            <img :src="urlKTPFoto" class="col-span-12 !mt-0 w-[231px]">
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">No. KTP<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.ktp_no" label="" placeholder="Tuliskan Nomor Kartu Penduduk"
              :errorText="formErrors.ktp_no?'failed':''" @input="v=>values.ktp_no=v" :hints="formErrors.ktp_no"
              :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Alamat Sesuai KTP<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="textarea" class="col-span-12 !mt-0 w-full"
              :value="values.alamat_asli" label="" placeholder="Tuliskan Alamat Sesuai KTP"
              :errorText="formErrors.alamat_asli?'failed':''" @input="v=>values.alamat_asli=v"
              :hints="formErrors.alamat_asli" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Foto Kartu Keluarga<label class="text-red-500 space-x-0 pl-0"></label></label>
            <div class="col-span-12 flex items-center">
              <input :disabled="!actionText ? true : false" type="file" accept="image/*" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.kk_foto}" id="inputKKFoto" @change="imageChange">

            </div>
            <img :src="urlKKFoto" class="col-span-12 !mt-0 w-[231px]">
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">No. Kartu Keluarga<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.kk_no" label="" placeholder="Tuliskan Nomor Kartu Keluarga"
              :errorText="formErrors.kk_no?'failed':''" @input="v=>values.kk_no=v" :hints="formErrors.kk_no"
              :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Foto NPWP<label class="text-red-500 space-x-0 pl-0"></label></label>
            <div class="col-span-12 flex items-center">
              <input :disabled="!actionText ? true : false" type="file" accept="image/*" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.npwp_foto}" id="inputNPWPFoto" @change="imageChange">

            </div>
            <img :src="urlNPWPFoto" class="col-span-12 !mt-0 w-[231px]">
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">No. NPWP<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.npwp_no" label="" placeholder="Tuliskan Nomor Pokok Wajib Pajak"
              :errorText="formErrors.npwp_no?'failed':''" @input="v=>values.npwp_no=v" :hints="formErrors.npwp_no"
              :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tanggal Berlaku NPWP<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="date" class="col-span-12 !mt-0 w-full"
              :value="values.npwp_tgl_berlaku" label="" placeholder="Masukan Tanggal Berlaku NPWP"
              :errorText="formErrors.npwp_tgl_berlaku?'failed':''" @input="v=>values.npwp_tgl_berlaku=v"
              :hints="formErrors.npwp_tgl_berlaku" :check="false" />
          </div>
        </div>
        <!-- <div class="col-span-8 md:col-span-6">
            <div class="grid grid-cols-12 items-center">
              <label class="col-span-12">Foto BPJS<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <div class="col-span-12 flex items-center">
                <input :disabled="!actionText ? true : false" type="file" accept="image/*" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.bpjs_foto}" id="inputBPJSFoto" @change="imageChange">
                <svg v-show="formErrors.bpjs_foto" class="svg-inline--fa fa-circle-exclamation fa-fw page-length-selector fa-md absolute right-2 fa-sm fa-fw text-red-400" aria-labelledby="svg-inline--fa-title-TuHui35w8qVB" data-prefix="fas" data-icon="circle-exclamation" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <title class="" id="svg-inline--fa-title-TuHui35w8qVB">failed</title>
                  <path class="" fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"></path>
                </svg>
              </div>
              <img :src="urlBPJSFoto" class="col-span-12 !mt-0 w-[231px]">
            </div>
          </div> -->
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">No. BPJS Kesehatan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.bpjs_no_kesehatan" label="" placeholder="Tuliskan Nomor BPJS"
              :errorText="formErrors.bpjs_no_kesehatan?'failed':''" @input="v=>values.bpjs_no_kesehatan=v"
              :hints="formErrors.bpjs_no_kesehatan" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">No. BPJS Ketenagakerjaan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.bpjs_no_ketenagakerjaan" label="" placeholder="Tuliskan Nomor BPJS"
              :errorText="formErrors.bpjs_no_ketenagakerjaan?'failed':''" @input="v=>values.bpjs_no_ketenagakerjaan=v"
              :hints="formErrors.bpjs_no_ketenagakerjaan" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tipe BPJS<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Berkas Pendukung Lainnya<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldUpload class="col-span-12 !mt-0 w-full" :bind="{ readonly: !actionText }" :value="values.berkas_lain"
              @input="(v)=>values.berkas_lain=v" :maxSize="10"
              :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
                  url: `${store.server.url_backend}/operation/m_kary_det_kartu/upload`,
                  headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: { field: 'berkas_lain' },
                  onsuccess: response=>response,
                  onerror:(error)=>{},
                 }" :hints="formErrors.berkas_lain" label="" placeholder="Upload Berkas" fa-icon="upload"
              accept="application/pdf" :check="false" />

            <!-- <div class="col-span-12 flex items-center">
                <input type="file" accept="application/pdf" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrors.berkas_lain}" id="inputBerkasLainFoto" @change="imageChange">
                <svg v-show="formErrors.berkas_lain" class="svg-inline--fa fa-circle-exclamation fa-fw page-length-selector fa-md absolute right-2 fa-sm fa-fw text-red-400" aria-labelledby="svg-inline--fa-title-TuHui35w8qVB" data-prefix="fas" data-icon="circle-exclamation" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                  <title class="" id="svg-inline--fa-title-TuHui35w8qVB">failed</title>
                  <path class="" fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"></path>
                </svg>
              </div> -->


          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Keterangan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="textarea" class="col-span-12 !mt-0 w-full"
              :value="values.desc_file" label="" placeholder="Tuliskan Keterangan"
              :errorText="formErrors.desc_file?'failed':''" @input="v=>values.desc_file=v" :hints="formErrors.desc_file"
              :check="false" />
          </div>
        </div>
        <h2 class="font-bold text-[18px] col-span-8 md:col-span-6">Ukuran</h2>
        <div class="col-span-8 md:col-span-6">
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Ukuran Baju<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.uk_baju" @input="v=>values.uk_baju=v" :errorText="formErrors.uk_baju?'failed':''"
              :hints="formErrors.uk_baju" label="" placeholder="Pilih Ukuran Baju" valueField="key" displayField="key"
              :options="['S', 'M', 'L', 'XL', 'XXL', 'XXXL']" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Ukuran Celana<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.uk_celana" label="" placeholder="Tuliskan Ukuran Celana"
              :errorText="formErrors.uk_celana?'failed':''" @input="v=>values.uk_celana=v" :hints="formErrors.uk_celana"
              :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Ukuran Sepatu<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.uk_sepatu" label="" placeholder="Tuliskan Ukuran Sepatu"
              :errorText="formErrors.uk_sepatu?'failed':''" @input="v=>values.uk_sepatu=v" :hints="formErrors.uk_sepatu"
              :check="false" />
          </div>
        </div>
                <div class="col-span-8 md:col-span-6">
        </div>
        <h2 class="font-bold text-[18px] col-span-8 md:col-span-6">Pembayaran</h2>
                <div class="col-span-8 md:col-span-6">
        </div>

        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Periode Gaji<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.periode_gaji_id" @input="v=>values.periode_gaji_id=v"
              :errorText="formErrors.periode_gaji_id?'failed':''" :hints="formErrors.periode_gaji_id" label=""
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
        </div>
        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tipe Pembayaran<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.tipe_id" @input="v=>values.tipe_id=v" :errorText="formErrors.tipe_id?'failed':''"
              :hints="formErrors.tipe_id" label="" placeholder="Pilih Tipe Pembayaran" valueField="id"
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
        </div>
        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Metode Pembayaran<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.metode_id" @input="v=>values.metode_id=v" :errorText="formErrors.metode_id?'failed':''"
              :hints="formErrors.metode_id" label="" placeholder="Pilih Metode Pembayaran" valueField="id"
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
        </div>
        <div v-if="!isProfile" class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama Bank<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.bank_id" @input="v=>values.bank_id=v" :errorText="formErrors.bank_id?'failed':''"
              :hints="formErrors.bank_id" label="" placeholder="Pilih Bank" valueField="id" displayField="value" :api="{
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nomor Rekening<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.no_rek" label="" placeholder="Tuliskan Nomor Rekening"
              :errorText="formErrors.no_rek?'failed':''" @input="v=>values.no_rek=v" :hints="formErrors.no_rek"
              :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Atas Nama<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full" :value="values.atas_nama_rek"
              label="" placeholder="Tuliskan Atas Nama Pemilik Rekening"
              :errorText="formErrors.atas_nama_rek?'failed':''" @input="v=>values.atas_nama_rek=v"
              :hints="formErrors.atas_nama_rek" :check="false" />
          </div>
        </div>
      </div>


      <!-- Form Pendidikan -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[80px] gap-y-[26px] mt-[36px]"
        v-if="activeTabIndex === 1">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tingkat<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="valuesPendidikan.tingkat_id" label="" placeholder="Pilih Tingkat"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tahun Masuk<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full" label=""
              placeholder="Pilih Tahun Masuk" :value="valuesPendidikan.thn_masuk"
              @input="v=>valuesPendidikan.thn_masuk=v" :options="ArrTahun"
              :errorText="formErrorsPend.thn_masuk?'failed':''" :hints="formErrorsPend.thn_masuk" valueField="key"
              displayField="key" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama Sekolah<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="valuesPendidikan.nama_sekolah" label="" placeholder="Tuliskan Nama Sekolah"
              @input="v=>valuesPendidikan.nama_sekolah=v" :check="false"
              :errorText="formErrorsPend.nama_sekolah?'failed':''" :hints="formErrorsPend.nama_sekolah" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Tahun Lulus<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="valuesPendidikan.thn_lulus" label="" placeholder="Pilih Tahun Lulus"
              @input="v=>valuesPendidikan.thn_lulus=v" :options="ArrTahun"
              :errorText="formErrorsPend.thn_lulus?'failed':''" :hints="formErrorsPend.thn_lulus" valueField="key"
              displayField="key" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Kota<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nilai<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="valuesPendidikan.nilai" label="" placeholder="Tuliskan Nilai" @input="v=>valuesPendidikan.nilai=v"
              :check="false" :errorText="formErrorsPend.nilai?'failed':''" :hints="formErrorsPend.nilai" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Jurusan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Jurusan"
              class="col-span-12 !mt-0 w-full" :value="valuesPendidikan.jurusan"
              :errorText="formErrorsPend.jurusan?'failed':''" :hints="formErrorsPend.jurusan"
              @input="v=>valuesPendidikan.jurusan=v" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Pendidikan Terakhir<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <div class="col-span-12">
              <div class="grid grid-cols-12">
                <div class="flex items-center col-span-6">
                  <input :disabled="!actionText ? true : false" type="radio" value="1" v-model="valuesPendidikan.is_pend_terakhir" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                  <label for="aktif_status" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Iya</label>
                </div>
                <div class="flex items-center col-span-6">
                  <input :disabled="!actionText ? true : false" type="radio" value="0" v-model="valuesPendidikan.is_pend_terakhir" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                  <label for="tidak_aktif_status" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Ijazah Terakhir<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <div class="col-span-12 flex items-center">
              <input :disabled="!actionText ? true : false" ref="fileIjz" type="file" accept="application/pdf" class="col-span-12 !mt-0 w-full border rounded-[0.25rem] text-[12px] py-[10px] px-[20px]"
                :class="{'border-red-500': formErrorsPend.ijazah_foto}" @change="fileIjazah" @input="(v)=>valuesPendidikan.ijazah_foto=v" >

            </div>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Catatan</label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Catatan" type="textarea"
              class="col-span-12 !mt-0 w-full" :value="valuesPendidikan.desc"
              :errorText="formErrorsPend.desc?'failed':''" @input="v=>valuesPendidikan.desc=v"
              :hints="formErrorsPend.desc" :check="false" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
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
      </div>

      <!-- Form Keluarga -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[80px] gap-y-[26px] mt-[36px]"
        v-if="activeTabIndex === 2">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Keluarga<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Nama<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Nama"
              class="col-span-12 !mt-0 w-full" :value="valuesKeluarga.nama" @input="v=>valuesKeluarga.nama=v"
              :check="false" :errorText="formErrorsKel.nama?'failed':''" :hints="formErrorsKel.nama" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Pendidikan Terakhir<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Pekerjaan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
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
        </div>

        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Jenis Kelamin<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
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
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Usia<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Usia" type="number"
              class="col-span-12 !mt-0 w-full" :value="valuesKeluarga.usia" @input="v=>valuesKeluarga.usia=v"
              :check="false" :errorText="formErrorsKel.usia?'failed':''" :hints="formErrorsKel.usia" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center">
            <label class="col-span-12">Catatan</label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Catatan" type="textarea"
              class="col-span-12 !mt-0 w-full" :value="valuesKeluarga.desc" @input="v=>valuesKeluarga.desc=v"
              :check="false" :errorText="formErrorsKel.desc?'failed':''" :hints="formErrorsKel.desc" />
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
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