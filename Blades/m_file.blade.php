@if(!$req->has('id'))
<h2 class="mb-4 mx-1 font-sans text-2xl flex justify-left font-bold text-center">
  Data File
</h2>
<div class="rounded-2xl">
  <TableApi class="rounded-2xl" ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-hex-1469AE text-white hover:bg-hex-0F4876 rounded-lg py-1 px-2">
        <icon fa="plus" />
        Tambah File
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
      <div class="flex items-center justify-between mb-2 border-b pb-4">
        <h2 class="font-sans text-xl flex justify-left font-bold">
          {{actionText}} File
        </h2>
        
        <div class="flex text-center gap-x-4 items-center">
          <button class="py-1 px-2 rounded transition-all duration-300 hover:!text-white hover:!bg-red-600" @click="onBack">
            <icon fa="arrow-left" size="sm"/>
            Back
          </button>
          <button v-show="actionText" class="py-1 px-2 rounded transition-all duration-300 hover:bg-red-600 hover:text-white" @click="onReset">
              <icon fa="sync" size="sm"/>
              Reset
            </button>
          <button v-show="actionText" class="bg-hex-2DA96D text-white hover:bg-hex-1F6D48 rounded-lg py-1 px-2" @click="onSave">
              <icon fa="save" size="sm"/>
              Save
            </button>
        </div>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid <md:grid-cols-1 grid-cols-3 grid-flow-row gap-x-4 mb-5">
        <FieldX  
          :bind="{ readonly: !actionText }"
          :value="values.name" :errorText="formErrors.name?'failed':''" placeholder="Nama" fa-icon="edit"
          @input="v=>values.name=v"
          :hints="formErrors.name"
          :check="false" />

        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }"
          :value="values.type" :errorText="formErrors.type?'failed':''"
          @input="v=>values.type=v"
          :hints="formErrors.type"
          :options="['DOKUMEN','ARSIP','MEDIA']"
          placeholder="Tipe" fa-icon="external-link-square-alt" :check="false" />
        <FieldUpload
          :reducerDisplay="(val)=>!val?null:val.split(':::')[val.split(':::').length-1]"
          :api="{
              url: `${store.server.url_backend}/operation/m_file/upload`,
              headers: {Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { field: 'filename' },
              onsuccess: function(response){
                return response
              },
              onerror:(error)=>{
                $log(error)
              }
           }"
           accept="*"
          :value="values.filename" @input="(v)=>values.filename=v" :maxSize="25"
          :hints="formErrors.filename" placeholder="File" fa-icon="upload"
          :check="false" />

        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }"
          :value="values.tags" :errorText="formErrors.tags?'failed':''"
          @input="v=>values.tags=v"
          :hints="formErrors.tags"
          :options="['ACTION','GAMBAR','ENTERTAINMENT', 'DLL']"
          placeholder="Tag" fa-icon="external-link-square-alt" :check="false" /> 

        <FieldX type="textarea"  
          :bind="{ readonly: !actionText }"  @input="(v)=>values.note=v"
          :errorText="formErrors.note?'failed':''" 
          :hints="formErrors.note"
          :value="values.note" @input="(v)=>values.note=v" placeholder="Catatan" fa-icon="edit" :check="false" />
      
      </div>

      <!-- FORM END -->

    </div>
  </div>

    
</div>

@endverbatim
@endif