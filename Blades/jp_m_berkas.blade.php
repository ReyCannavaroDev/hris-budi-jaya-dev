<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
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

<!-- CONTENT -->
@verbatim
  <div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
    <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Berkas</h1>
          <p class="text-gray-100">Master Berkas</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX
          class="w-full !mt-3"
          :bind="{ readonly: !actionText }"
          :value="values.nama" :errorText="formErrors.nama?'failed':''" label="Nama" placeholder="Tuliskan Nama"
          @input="v=>values.nama=v"
          :hints="formErrors.nama"
          :check="false" />
      </div>
      <div>   
        <FieldSelect
          class="w-full !mt-3"
          :bind="{ disabled: !actionText, clearable:false }"
          :value="values.kategori" :errorText="formErrors.kategori?'failed':''"
          @input="v=>values.kategori=v"
          :hints="formErrors.kategori"
          :options="['SOP','BERKAS']"
          placeholder="Pilih Kategori" label="Kategori" :check="false" />
      </div>
      <div>
        <FieldUpload
          class="w-full !mt-3"
          :reducerDisplay="(val)=>!val?null:val.split(':::')[val.split(':::').length-1]"
          :api="{
              url: `${store.server.url_backend}/operation/m_berkas/upload`,
              headers: {Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { field: 'url' },
              onsuccess: function(response){
                return response
              },
              onerror:(error)=>{
                $log(error)
              }
           }"
           accept="*"
          :value="values.url" @input="(v)=>values.url=v" :maxSize="25"
          :hints="formErrors.url" placeholder="Upload File" label="File"
          :check="false" />
      </div>
      <div>
        <FieldX  
            class="w-full !mt-3"
            :bind="{ readonly: !actionText }"
            :value="values.desc" :errorText="formErrors.desc?'failed':''"
            @input="v=>values.desc=v"
            type="textarea"
            :hints="formErrors.desc"
            label="Keterangan"
            placeholder="Tuliskan Keterangan"
            :check="false" />
      </div>
      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
    </div>
      <hr>
    <div class="flex flex-row items-center justify-end space-x-2 p-2">
      <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
      <button 
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText" 
        @click="onReset(true)" 
      >
        <icon fa="times" />
        Reset
      </button>
      <button 
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText" 
        @click="onSave" 
      >
        <icon fa="save" />
        Simpan
      </button>
    </div>
  </div>
@endverbatim
@endif