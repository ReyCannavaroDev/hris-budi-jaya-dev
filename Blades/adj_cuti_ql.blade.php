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
    <div class="flex flex-col border rounded-2xl shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <!-- HEADER START -->
      <div class="flex items-center justify-between mb-2 pb-4">
        <h2 class="font-sans text-xl flex justify-left font-bold">
          Adjusment Cuti
        </h2>
      
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
        <div>
          <label class="col-span-12">Tanggal<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: true, disabled:true }" type='date' class="w-full py-2 !mt-0" :value="values.date"
            :errorText="formErrors.date?'failed':''" @input="v=>values.date=v"
            :hints="formErrors.date" :check="false" label="" placeholder="Pilih Tanggal" />
        </div>
        <div v-show="adjKary" class="grid grid-cols-2 gap-2">
          <div>
            <label class="col-span-12">Jatah Cuti Tahunan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.cuti_satu_hari" label="" placeholder="Tuliskan Jatah Cuti satu_hari" :errorText="formErrors.cuti_satu_hari?'failed':''"
              @input="v=>values.cuti_satu_hari=v" :hints="formErrors.cuti_satu_hari" :check="false"
            />
          </div>
          <div>
            <label class="col-span-12">Sisa Satu Hari<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.sisa_cuti_satu_hari" label="" placeholder="Tuliskan Sisa Jatah Cuti satu_hari" :errorText="formErrors.sisa_cuti_satu_hari?'failed':''"
              @input="v=>values.sisa_cuti_satu_hari=v" :hints="formErrors.sisa_cuti_satu_hari" :check="false"
            />
            <h5 v-show="values.tipe_key == '01'" class="text-red-600 !mt-[-0.5rem] text-right">Hasil Sisa Cuti Tahunan : {{informasiCuti.sisa_cuti_satu_hari}}</h5>
          </div>
        </div>
        <div v-show="adjKary" class="grid grid-cols-2 gap-2">
          <div>
            <label class="col-span-12">Jatah Cuti Setengah Hari<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.cuti_setengah_hari" label="" :errorText="formErrors.cuti_setengah_hari?'failed':''"
              @input="v=>values.cuti_setengah_hari=v" :hints="formErrors.cuti_setengah_hari" :check="false"
            />
          </div>
          <div>
            <label class="col-span-12">Sisa Cuti Setengah Hari<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" type="number" class="col-span-12 py-2 !mt-0 w-full"
              :value="values.sisa_cuti_setengah_hari" label="" :errorText="formErrors.sisa_cuti_setengah_hari?'failed':''"
              @input="v=>values.sisa_cuti_setengah_hari=v" :hints="formErrors.sisa_cuti_setengah_hari" :check="false"
            />
            <h5 v-show="values.tipe_key == '02'" class="text-red-600 !mt-[-0.5rem] text-right">Hasil Sisa Cuti Setengah Hari : {{informasiCuti.sisa_cuti_setengah_hari}}</h5>
          </div>
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
                field: 'kode',
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