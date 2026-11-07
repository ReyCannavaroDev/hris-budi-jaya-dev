@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'"
        :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
        Tambah
        <icon fa="plus" />
      </RouterLink>
    </template>
  </TableApi>
</div>
@else

@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Form Transaksi Mutasi
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
        <!-- START COLUMN -->

        <div>
          <label class="font-semibold">Nomor</label>
          <FieldX :bind="{ readonly: true }" label=""  class="w-full py-2 !mt-0"
              :value="values.nomor" :errorText="formErrors.nomor?'failed':''"
              @input="v=>values.nomor=v" :hints="formErrors.nomor" 
              :check="false"
              label=""
              placeholder="Nomer"
          />
        </div>
        <div>
          <label class="font-semibold">Direktorat<span class="text-red-500 space-x-0 pl-0">*</span></label>
            <FieldSelect
              :bind="{ disabled: true, clearable:false }" class="w-full py-2 !mt-0"
              :value="values.direktorat" @input="v=>values.direktorat=v"
              
              :errorText="formErrors.direktorat?'failed':''" 
              @update:valueFull="(objVal)=>{
                  values.m_divisi_id = null
                }"
              label="" placeholder=""
              :hints="formErrors.direktorat"
              :api="{
                  url: `${store.server.url_backend}/operation/m_dir`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  
              }"
              valueField="id" displayField="nama" :check="false"
            />
        </div>

        <div>
          <label class="font-semibold">Karyawan<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldPopup :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.m_kary_id"
            @input="(v)=>values.m_kary_id=v" :errorText="formErrors.m_kary_id?'failed':''" :hints="formErrors.m_kary_id"
            valueField="id" displayField="nama_lengkap" @update:valueFull="(objVal)=>{
    values.m_divisi_lama_id = objVal['m_divisi.id']
    values.m_dept_lama_id = objVal['m_dept.id']
    values.m_posisi_lama_id = objVal['m_posisi.id']
    values.m_standart_posisi_id = objVal['m_standart_gaji.id']
  }" :api="{
    url: `${store.server.url_backend}/operation/m_kary`,
    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
    params: {
      simplest:true,
      searchfield:'nik, nama_depan, m_dir.nama, m_divisi.nama, id'
    }
  }"  placeholder="Pilih Karyawan" label="" :check="false" :columns="[
    {
      headerName: 'No',
      valueGetter: (p) => p.node.rowIndex + 1,
      width: 60,
      sortable: false, 
      resizable: false, 
      filter: false,
      cellClass: ['justify-center', 'bg-gray-50']
    },
    {
      flex: 1,
      field: 'nik',
      headerName: 'NIK',
      sortable: false, 
      resizable: true, 
      filter: 'ColFilter',
      cellClass: ['border-r', '!border-gray-200', 'justify-center']
    },
    {
      flex: 1,
      field: 'nama_depan',
      headerName: 'Nama',
      sortable: false, 
      resizable: true, 
      filter: 'ColFilter',
      cellClass: ['border-r', '!border-gray-200', 'justify-center']
    },
    {
      flex: 1,
      field: 'm_dir.nama',
      headerName: 'Direktorat',
      sortable: false, 
      resizable: true, 
      filter: 'ColFilter',
      cellClass: ['border-r', '!border-gray-200', 'justify-center']
    },
    {
      flex: 1,
      field: 'm_divisi.nama',
      headerName: 'Divisi',
      sortable: false, 
      resizable: true, 
      filter: 'ColFilter',
      cellClass: ['border-r', '!border-gray-200', 'justify-center']
    },
    // Add columns for m_posisi data if needed
  ]" />
        </div>

        <div>
          <label class="font-semibold">Tanggal Tranfer<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type="date" class="w-full py-2 !mt-0" :value="values.tgl" label=""
            placeholder="Pilih Tanggal" :errorText="formErrors.tgl?'failed':''" @input="v=>values.tgl=v"
            :hints="formErrors.tgl" :check="false" />
        </div>

        <div>
          <label class="font-semibold">Status Karyawan<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect class="w-full py-2 !mt-0" :value="values.status_kary_id" @input="v=>values.status_kary_id=v"
            valueField="id" displayField="nama" :options="status_kary" placeholder="Pilih Status" label=""
            :check="false" />
        </div>

        <div>
          <label class="font-semibold">Tipe Mutasi<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect class="w-full py-2 !mt-0" :value="values.tipe_mutasi" @input="v => values.tipe_mutasi=v"
            placeholder="Pilih Tipe Mutasi" label="" :check="false" :options="['Antar Divisi', 'Antar Departement']" />
        </div>



        <div>
          <label class="font-semibold">Divisi Lama<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect :bind="{ disabled: true }" label="" class="w-full py-2 !mt-0" :value="values.m_divisi_lama_id"
            :errorText="formErrors.m_divisi_lama_id?'failed':''" @input="v=>values.m_divisi_lama_id=v"
            :hints="formErrors.m_divisi_lama_id" :check="false" label="" placeholder=""
                        valueField="id" displayField="nama" :api="{
                url: `${store.server.url_backend}/operation/m_divisi`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }" placeholder="Pilih Departemen" label="" :check="false" />
            
        </div>
        <div>
          <label class="font-semibold">Departemen Lama<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect :bind="{ disabled: true }" label="" class="w-full py-2 !mt-0" :value="values.m_dept_lama_id"
            :errorText="formErrors.m_dept_lama_id?'failed':''" @input="v=>values.m_dept_lama_id=v"
            :hints="formErrors.m_dept_lama_id" :check="false" label="" placeholder="" 
            valueField="id" displayField="nama" :api="{
                url: `${store.server.url_backend}/operation/m_dept`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }" placeholder="Pilih Departemen" label="" :check="false" />
        </div>
        <div>
          <label class="font-semibold">Posisi Lama<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect :bind="{ disabled: true }" label="" class="w-full py-2 !mt-0" :value="values.m_posisi_lama_id"
            :errorText="formErrors.m_posisi_lama_id?'failed':''" @input="v=>values.m_posisi_lama_id=v"
            :hints="formErrors.m_posisi_lama_id" :check="false" label="" placeholder="" valueField="id"
            displayField="desc_kerja" :api="{
                url: `${store.server.url_backend}/operation/m_posisi`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }" placeholder="Pilih Posisi" label="" :check="false" />
        </div>
<!-- <div>
    <label class="font-semibold">Standar Gaji Lama<span class="text-red-500 space-x-0 pl-0">*</span></label>
    <FieldSelect :bind="{ disabled: true }" label="" class="w-full py-2 !mt-0"
        :value="values.m_standart_posisi_id" :errorText="formErrors.m_standart_posisi_id?'failed':''"
        @input="v=>values.m_standart_posisi_id=v" :hints="formErrors.m_standart_posisi_id" :check="false" label=""
        placeholder="" valueField="id" displayField="kode" :api="{
            url: `${store.server.url_backend}/operation/m_standart_gaji`,
            headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
            params: {
              simplest:true,
              single:true,
              where:`this.is_active='true'`,
              transform:false,
            }
        }" placeholder="Pilih gaji baru" label="" :check="false" />
</div> -->

        <div>
          <label class="font-semibold">Divisi Baru<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect class="w-full py-2 !mt-0" :value="values.m_devisi_baru_id" @input="v=>values.m_devisi_baru_id=v"
            valueField="id" displayField="nama" :api="{
                url: `${store.server.url_backend}/operation/m_divisi`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }" placeholder="Pilih Divisi" label="" :check="false" />
        </div>
        <div>
          <label class="font-semibold">Departemen Baru<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect :bind="{ readonly: !actionText }" label="" class="w-full py-2 !mt-0" :value="values.m_dept_baru_id"
            :errorText="formErrors.m_dept_baru_id?'failed':''" @input="v=>values.m_dept_baru_id=v"
            :hints="formErrors.m_dept_baru_id" :check="false" label="" placeholder="" 
            valueField="id" displayField="nama" :api="{
                url: `${store.server.url_backend}/operation/m_dept`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }" placeholder="Pilih Departemen" label="" :check="false" />
        </div>
        <div>
          <label class="font-semibold">Posisi Baru<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect :bind="{ readonly: !actionText }"  class="w-full py-2 !mt-0" :value="values.m_posisi_baru_id" @input="v=>values.m_posisi_baru_id=v"
            valueField="id" displayField="desc_kerja" :api="{
                url: `${store.server.url_backend}/operation/m_posisi`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }" placeholder="Pilih Posisi" label="" :check="false" />
        </div>
        
        <!-- <div>
          <label class="font-semibold">Standar Gaji Baru<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect class="w-full py-2 !mt-0" :value="values.m_standart_baru_id"
            @input="v=>values.m_standart_baru_id=v" valueField="id" displayField="kode" :api="{
                url: `${store.server.url_backend}/operation/m_standart_gaji`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }" placeholder="Pilih gaji baru" label="" :check="false" />
        </div> -->

        <div>
          <label class="font-semibold">Nomer Dokumen<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldX :bind="{ readonly: !actionText }" label="" class="w-full py-2 !mt-0" :value="values.no_dokumen"
            :errorText="formErrors.no_dokumen?'failed':''" @input="v=>values.no_dokumen=v"
            :hints="formErrors.no_dokumen" :check="false" label="" placeholder="Tulis Nomer Dokumen" />
        </div>

        <div>
          <label class="font-semibold">Upload Dokumen<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldUpload class="col-span-12 !mt-0 w-full" :bind="{ readonly: !actionText }" :value="values.file_dokumen"
            @input="(v)=>values.file_dokumen=v" :maxSize="10"
            :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
                  url: `${store.server.url_backend}/operation/t_mutasi/upload`,
                  headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: { field: 'file_dokumen' },
                  onsuccess: response=>response,
                  onerror:(error)=>{},
                 }" :hints="formErrors.file_dokumen" label="" placeholder="Upload Berkas" fa-icon="upload"
            accept="application/pdf" :check="false" />
        </div>
        <div>
          <label class="font-semibold">Keterangan<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX class="w-full py-2 !mt-0" :bind="{ readonly: false }" type="textarea" :value="values.keterangan"
            @input="v=>values.keterangan=v" placeholder="Tulis Keterangan" label=""  :check="false" />
        </div>

       <div>
          <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.status" :errorText="formErrors.status?'failed':''"
              @input="v=>values.status=v" :hints="formErrors.status" :check="false"
              label="" placeholder=""
            />
        </div>

        <!-- END COLUMN -->
      </div>
      <!-- ACTION BUTTON START -->
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
        <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Batal
          </button>
          <button v-show="route.query.action?.toLowerCase() === 'verifikasi'" @click="posted" class="bg-orange-500 hover:bg-orange-600 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Posted
          </button>
        <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
      </div>
    </div>
  </div>
</div>
@endverbatim
@endif