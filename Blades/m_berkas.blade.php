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
          Form Berkas
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
     <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row gap-x-4 mb-5">
        <FieldX  
          :bind="{ readonly: !actionText }"
          :value="values.nama" :errorText="formErrors.nama?'failed':''" placeholder="Nama" fa-icon="edit"
          @input="v=>values.nama=v"
          :hints="formErrors.nama"
          :check="false" />

        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }"
          :value="values.kategori" :errorText="formErrors.kategori?'failed':''"
          @input="v=>values.kategori=v"
          :hints="formErrors.kategori"
          :options="['SOP','BERKAS']"
          placeholder="kategori" fa-icon="external-link-square-alt" :check="false" />

        <FieldUpload
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
          :hints="formErrors.url" placeholder="File" fa-icon="upload"
          :check="false" />

        <FieldX type="textarea"  
          :bind="{ readonly: !actionText }"  @input="(v)=>values.desc=v"
          :errorText="formErrors.desc?'failed':''" 
          :hints="formErrors.desc"
          :value="values.desc" @input="(v)=>values.desc=v" placeholder="Catatan" fa-icon="edit" :check="false" />
      
      </div>
      
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
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