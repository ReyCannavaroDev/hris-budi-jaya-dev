@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'" :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
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
      <!-- HEADER START -->
      <div class="flex flex-col items-start mb-2 pb-4">
        <h1 class="text-[24px] mb-[15px] font-bold">
         Form Ganjaran / Surat Penghargaan atau Surat Peringatan (SP)
        </h1>
      </div>
      <!-- HEADER END -->
      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">ID Pengguna<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldPopup
            valueField="id" displayField="menu" 
            class="col-span-12 !mt-0 w-full"
            :api="{
              url: `${store.server.url_backend}/operation/m_menu`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
              }
            }"
            placeholder="Cari NIK karyawwan" fa-icon="Pilih Pop Up" :check="false" 
            :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'menu',
              headerName:  'Nama Menu',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'path',
              headerName:  'Path Menu',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            ]"
          />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldX :bind="{ readonly: true }" class="col-span-12 !mt-0 w-full"
                :value="values.tgl_masuk" :errorText="formErrors.tgl_masuk?'failed':''"
                @input="v=>values.tgl_masuk=v" :hints="formErrors.tgl_masuk" :check="false"
              />         
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tipe<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.dokumen" @input="v=>values.dokumen=v"
              :errorText="formErrors.dokumen?'failed':''" 
              :hints="formErrors.dokumen"
              valueField="id" displayField="key"
              :options="['LEAVE', 'METU']"
              :check="false"
            />  
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Pilih tanggal<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldX :bind="{ readonly: false }" type="date" :value="values.date"
                class="col-span-12 !mt-0 w-full"
              @input="v=>values.date=v" 
            placeholder="Pilih Tanggal" fa-icon="calender" :check="false"
          />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Deskripsi<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldX :bind="{ readonly: false }" 
              type="textarea" 
              :value="values.textarea"
              class="col-span-12 !mt-0 w-full"
              @input="v=>values.textarea=v" 
              placeholder="" fa-icon="edit" :check="false"
              />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Catatan<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldX :bind="{ readonly: false }" 
              type="textarea" 
              :value="values.textarea"
              class="col-span-12 !mt-0 w-full"
              @input="v=>values.textarea=v" 
              placeholder="" fa-icon="edit" :check="false"
              />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">No.Dokumen SP<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldX :bind="{ readonly: false }" class="col-span-12 !mt-0 w-full"
                :value="values.SP" :errorText="formErrors.SP?'failed':''"
                @input="v=>values.SP=v" :hints="formErrors.SP" :check="false"
              />         
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Dokumen SP<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldUpload
             class="col-span-12 !mt-0 w-full
            :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]"
            :api="{
              url: `${store.server.url_backend}/operation/m_menu/upload`,
              headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { field: 'name' },
              onsuccess: response=>response,
              onerror:(error)=>{},
             }" placeholder="Masukan File" fa-icon="upload"
             accept="*" :check="false"  
          />        
          </div>
        </div>
      <!--BUTTON-->
      </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">

            <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] w-32 ">
            Kembali
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] w-32 ">
            Simpan
          </button>
      </div>
      <!-- FORM END -->
    </div>
  </div>
</div>
@endverbatim
@endif