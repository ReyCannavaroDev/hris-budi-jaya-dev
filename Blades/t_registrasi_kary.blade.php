@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'" :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
        <icon fa="plus" />
        Tambah Data
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
      <div class="flex flex-col items-start mb-2 pb-4">
        <h1 class="text-[24px] mb-[15px] font-bold">
          Form Registrasi Karyawan
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <!-- <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Direktorat<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" label="" placeholder="Autofill"  class="col-span-12 !mt-0 w-full"
              :value="values.direktorat" :errorText="formErrors.direktorat?'failed':''"
              @input="v=>values.direktorat=v" :hints="formErrors.direktorat" :check="false"
            />
          </div>
        </div> -->
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Pelamar<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldPopup
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.t_pelamar_id" @input="(v)=>values.t_pelamar_id=v"
              :errorText="formErrors.t_pelamar_id?'failed':''" 
              :hints="formErrors.t_pelamar_id" 
              @update:valueFull="(items)=>{
                if(items){
                  values.m_dir_id = items.m_dir_id
                  values.m_divisi_id = items.m_divisi_id
                  values.m_dept_id = items.m_dept_id
                  values.m_posisi_id = items.m_posisi_id
                  values.tempat_lahir = items.tempat_lahir
                  values.tgl_lahir = items.tgl_lahir
                  values.nama_pelamar = items.nama_pelamar
                  values.tgl_lahir = items.tgl_lahir
                  values.ref = items.ref
                  getNilai()
                }else if(items === null){
                  values.m_dir_id = null 
                  values.m_divisi_id = null 
                  values.m_dept_id = null 
                  values.m_posisi_id = null 
                  values.tempat_lahir = null 
                  values.tgl_lahir = null 
                  values.nama_pelamar = null 
                  values.tgl_lahir = null 
                  values.ref = null 
                  values.nilai_tes = null
                }
                
              }"
              valueField="id" displayField="nama_pelamar"
              :api="{
                url: `${store.server.url_backend}/operation/t_pelamar`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  searchfield: 'this.nama_pelamar, m_dir.nama, m_divisi.nama, m_dept.nama, m_posisi.desc_kerja'
                }
              }"
              placeholder="Pilih Pelamar" label="" :check="false" 
              :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'nama_pelamar',
                wrapText: true,
                headerName:  'Nama',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_dir.nama',
                wrapText: true,
                headerName:  'Direktorat',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_divisi.nama',
                wrapText: true,
                headerName:  'Divisi',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_dept.nama',
                wrapText: true,
                headerName:  'Departemen',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_posisi.desc_kerja',
                wrapText: true,
                headerName:  'Posisi',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              ]"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Direktorat<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_dir_id" @input="v=>values.m_dir_id=v"
              :errorText="formErrors.m_dir_id?'failed':''" 
              label="" placeholder="Pilih Direktorat"
              :hints="formErrors.m_dir_id"
              :api="{
                  url: `${store.server.url_backend}/operation/m_dir`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    where: `this.is_active = 'true'`
                  }
              }"
              valueField="id" displayField="nama" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Divisi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_divisi_id" @input="v=>values.m_divisi_id=v"
              :errorText="formErrors.m_divisi_id?'failed':''" 
              @update:valueFull="(objVal)=>{
                values.m_dept_id = null
              }"
              label="" placeholder="Pilih Divisi"
              :hints="formErrors.m_divisi_id"
              :api="{
                  url: `${store.server.url_backend}/operation/m_divisi`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    where: `this.is_active = 'true'`
                  }
              }"
              valueField="id" displayField="nama" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Departemen<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_dept_id" @input="v=>values.m_dept_id=v"
              :errorText="formErrors.m_dept_id?'failed':''" 
              label="" placeholder="Pilih Departemen"
              :hints="formErrors.m_dept_id"
              :api="{
                  url: `${store.server.url_backend}/operation/m_dept`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    where: `m_divisi_id=${values.m_divisi_id} AND this.is_active = 'true'`
                  }
              }"
              valueField="id" displayField="nama" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Posisi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_posisi_id" @input="v=>values.m_posisi_id=v"
              :errorText="formErrors.m_posisi_id?'failed':''" 
              label="" placeholder="Pilih Departemen"
              :hints="formErrors.m_posisi_id"
              :api="{
                  url: `${store.server.url_backend}/operation/m_posisi`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    where: `this.is_active = 'true'`
                  }
              }"
              valueField="id" displayField="desc_kerja" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Nama</label>
            <FieldX :bind="{ readonly: true }" label="" placeholder="Autofield" class="col-span-12 !mt-0 w-full"
              :value="values.nama_pelamar" :errorText="formErrors.nama_pelamar?'failed':''"
              @input="v=>values.nama_pelamar=v" :hints="formErrors.nama_pelamar" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
            <div class="grid grid-cols-12 items-center gap-y-2 gap-x-2">
              <label class="col-span-12">Tempat, Tanggal Lahir<label class="text-red-500 space-x-0 pl-0">*</label></label>
                <FieldSelect
                  :bind="{ disabled: true, clearable:false }" class="col-span-6 !mt-0 w-full"
                  :value="values.tempat_lahir" @input="v=>values.tempat_lahir=v"
                  :errorText="formErrors.tempat_lahir?'failed':''" 
                  :hints="formErrors.tempat_lahir" label="" placeholder="Pilih Kota"
                  valueField="value" displayField="value"
                  :api="{
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
                  }"
                  :check="false"
                />
              <FieldX :bind="{ readonly: true }" type="date" class="col-span-6 !mt-0 w-full"
                :value="values.tgl_lahir" label="" placeholder="Pilih Tanggal" :errorText="formErrors.tgl_lahir?'failed':''"
                @input="v=>values.tgl_lahir=v" :hints="formErrors.tgl_lahir"  :check="false"
              />
            </div>
          </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Referensi</label>
            <FieldX :bind="{ readonly: true }" label="" placeholder="Autofield" class="col-span-12 !mt-0 w-full"
              :value="values.ref" :errorText="formErrors.ref?'failed':''"
              @input="v=>values.ref=v" :hints="formErrors.ref" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Nilai Tes</label>
            <FieldX :bind="{ readonly: true }" label="" placeholder="Autofield" class="col-span-12 !mt-0 w-full"
              :value="values.nilai_tes" :errorText="formErrors.nilai_tes?'failed':''"
              @input="v=>values.nilai_tes=v" :hints="formErrors.nilai_tes" :check="false"
            />
          </div>
        </div>
        
      </div>
      
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
        <button @click="onBack" class="bg-gray-400 hover:bg-gray-500 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Kembali
          </button>
            <button @click="onPostedKary('tolak')" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Tolak
          </button>
          <button @click="onPostedKary('konfirmasi')" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Konfirmasi
          </button>
      </div>
      <!-- FORM END -->
    </div>
  </div>
</div>
@endverbatim
@endif