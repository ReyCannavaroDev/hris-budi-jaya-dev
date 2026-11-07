@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px] border-t-10 border-gray-500">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
    </template>
  </TableApi>
</div>
@else

@verbatim

<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded-2xl shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <!-- HEADER START -->
  <div class="bg-gray-500 text-white rounded-t-md py-2 px-4 mb-10">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Hasil Test Lamaran Kerja</h1>
        <p class="text-gray-100">Hasil Test Lamaran Kerja</p>
      </div>
    </div>
  </div>
      <!-- HEADER END -->

      <!-- FORM START -->
            <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
        <!-- START COLUMN -->
        <div>
          <label class="font-semibold">Nomor<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" label="" class="w-full py-2 !mt-0" :value="values.nomor"
            :errorText="formErrors.nomor?'failed':''" @input="v=>values.nomor=v" :hints="formErrors.nomor"
            :check="false" label="" placeholder="Nomor" />
        </div>
        <div v-show="adjKary" class="grid grid-cols-2 gap-2">
          <div>
            <label class="col-span-12">Jatah Cuti Tahunan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.cuti_reguler" label="" placeholder="Tuliskan Jatah Cuti Reguler" :errorText="formErrors.cuti_reguler?'failed':''"
              @input="v=>values.cuti_reguler=v" :hints="formErrors.cuti_reguler" :check="false"
            />
          </div>
          <div>
            <label class="col-span-12">Sisa Cuti Tahunan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.sisa_cuti_reguler" label="" placeholder="Tuliskan Sisa Jatah Cuti Reguler" :errorText="formErrors.sisa_cuti_reguler?'failed':''"
              @input="v=>values.sisa_cuti_reguler=v" :hints="formErrors.sisa_cuti_reguler" :check="false"
            />
            <h5 v-show="values.tipe_key == '02'" class="text-red-600 !mt-[-0.5rem] text-right">Hasil Sisa Cuti Tahunan : {{informasiCuti.sisa_cuti_reguler}}</h5>
          </div>
        </div>
        <div v-show="adjKary" class="grid grid-cols-2 gap-2">
          <div>
            <label class="col-span-12">Jatah Cuti Masa Kerja<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.cuti_masa_kerja" label="" :errorText="formErrors.cuti_masa_kerja?'failed':''"
              @input="v=>values.cuti_masa_kerja=v" :hints="formErrors.cuti_masa_kerja" :check="false"
            />
          </div>
          <div>
            <label class="col-span-12">Sisa Cuti Masa Kerja<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.sisa_cuti_masa_kerja" label="" :errorText="formErrors.sisa_cuti_masa_kerja?'failed':''"
              @input="v=>values.sisa_cuti_masa_kerja=v" :hints="formErrors.sisa_cuti_masa_kerja" :check="false"
            />
            <h5 v-show="values.tipe_key == '01'" class="text-red-600 !mt-[-0.5rem] text-right">Hasil Sisa Cuti Masa Kerja : {{informasiCuti.sisa_cuti_masa_kerja}}</h5>
          </div>
        </div>
        <div v-show="adjKary" class="grid grid-cols-2 gap-2">
          <div>
            <label class="col-span-12">Jatah Cuti P24<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.cuti_p24" label="" :errorText="formErrors.cuti_p24?'failed':''"
              @input="v=>values.cuti_p24=v" :hints="formErrors.cuti_p24" :check="false"
            />
          </div>
          <div>
            <label class="col-span-12">Sisa Cuti P24<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.sisa_cuti_p24" label="" :errorText="formErrors.sisa_cuti_p24?'failed':''"
              @input="v=>values.sisa_cuti_p24=v" :hints="formErrors.sisa_cuti_p24" :check="false"
            />
            
            <h5 v-show="values.tipe_key == '03'" class="text-red-600 !mt-[-0.5rem] text-right">Hasil Sisa Cuti P24 : {{informasiCuti.sisa_cuti_p24}}</h5>
          </div>
        </div>
        <div>
          <label class="col-span-12">Tanggal<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: true, disabled:true }" type='date' class="w-full py-2 !mt-0" :value="values.date"
            :errorText="formErrors.date?'failed':''" @input="v=>values.date=v"
            :hints="formErrors.date" :check="false" label="" placeholder="Pilih Tanggal" />
        </div>
        <div>
          <label class="font-semibold">Karyawan<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldPopup :bind="{ readonly: isRead, clearable:false }" class="w-full py-2 !mt-0" :value="values.m_kary_id"
            @input="(v)=>{
              values.m_kary_id=v
              if(v === null){
                adjKary = false
              }else{              
                infoCuti(v)
              }}" 
              @update:valueFull="(v)=>{
                if(v === null){
                  adjKary = false
                }else{              
                  infoCuti(v.id)
                  values.value=null
                }
              }"
              :errorText="formErrors.m_kary_id?'failed':''" :hints="formErrors.m_kary_id"
            valueField="id" displayField="nama_lengkap" :api="{
                  url: `${store.server.url_backend}/operation/m_kary`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    searchfield:'this.id, this.nama_depan, this.nama_belakang, this.nama_lengkap, this.nik, m_divisi.nama, m_zona.nama, m_dir.nama',
                  }
                }" placeholder="Pilih Karyawan" label="" :check="false" :columns="[
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
                field: 'nama_lengkap',
                headerName: 'Nama',
                sortable: false, 
                resizable: true, 
                filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
                field: 'm_zona.nama',
                headerName: 'Zona',
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
              {
                flex: 1,
                field: 'm_dept.nama',
                headerName: 'Departemen',
                sortable: false, 
                resizable: true, 
                filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },                                         
            ]" />
        </div>

        <div>
          <label class="font-semibold">Tipe Cuti<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect :bind="{ disabled: isRead, clearable:false }" class="col-span-12 py-2 !mt-0 w-full"
            :value="values.tipe_cuti_id" @input="v=>{
              values.tipe_cuti_id=v
              if(v !== null){
                if(actionText === undefined){
                readValue = true
              }else{
                readValue = false
              }
              }else{
                readValue = true
              }
              }" 
              @update:valueFull="(v)=>{
                setNullInfoCuti(v.key)
                values.tipe_key = v.key
            }"
            :errorText="formErrors.tipe_cuti_id?'failed':''" :hints="formErrors.tipe_cuti_id" label=""
            placeholder="Tipe Cuti" valueField="id" displayField="value" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
              },
              params: {
              simplest: true,
              transform: false,
              join: true,
              where:`this.group='TIPE CUTI' AND this.is_active='true'`,
              selectfield: 'this.id, this.code, this.value, this.is_active, this.key'
              }
              }" :check="false" />
        </div>
        <div>
          <label class="col-span-12">Value<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: readValue}" type='number' class="w-full py-2 !mt-0" :value="values.value"
            :errorText="formErrors.value?'failed':''" @input="v=>{
              values.value=v
              changeSisaCuti(values.tipe_key,parseInt(v))}"
            :hints="formErrors.value" :check="false" label="" placeholder="Tuliskan Value" />
        </div>
        <div>
          <label class="col-span-12">Keterangan Cuti<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type='textarea' class="w-full py-2 !mt-0" :value="values.keterangan"
            :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v"
            :hints="formErrors.keterangan" :check="false" label="" placeholder="Tuliskan keterangan" />
        </div>
      </div>

      <div class="flex justify-end mb-4 gap-4">
        <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] w-32">
            Batal
        </button>
        <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] w-32">
            Simpan
        </button>
      </div>


      <!-- FORM END -->

    </div>
  </div>

    
</div>

@endverbatim
@endif