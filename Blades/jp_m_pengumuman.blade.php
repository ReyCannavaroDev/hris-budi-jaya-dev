<!-- LANDING -->
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

<!-- CONTENT -->
@verbatim
  <div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
    <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
      <div class="flex items-center">
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Pengumuman</h1>
          <p class="text-gray-100">Master Pengumuman</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX  
            class="w-full !mt-3"
            :bind="{ readonly: !actionText }"
            :value="values.judul" :errorText="formErrors.judul?'failed':''"
            @input="v=>values.judul=v"
            :hints="formErrors.judul"
            label="Judul"
            placeholder="Tuliskan Judul Pengumuman"
            :check="false" />
      </div>
      <div>   
        <FieldUpload
            class="w-full !mt-3"
            :bind="{ readonly: !actionText }"
            :value="values.thumb" @input="(v)=>values.thumb=v" :maxSize="10"
            :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]"
            label=""
            :api="{
              url: `${store.server.url_backend}/operation/t_pengumuman/upload`,
              headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { field: 'thumb' },
              onsuccess: response=>response,
              onerror:(error)=>{},
             }"
             :hints="formErrors.thumb" label="Gambar Thumbnail" placeholder="Upload Gambar Thumbnail" fa-icon="upload"
             accept=".png,.jpg,.jpeg" :check="false"  
          />
      </div>
      <div>
        <FieldX  
            class="w-full !mt-3"
            :bind="{ readonly: !actionText }"
            :value="values.tag" :errorText="formErrors.tag?'failed':''"
            @input="v=>values.tag=v"
            :hints="formErrors.tag"
            label="Tag Berita"
            placeholder="Tuliskan Tag Berita"
            :check="false" />
      </div>
      <div>
        <FieldX  
            class="w-full !mt-3"
            type="textarea"
            :bind="{ readonly: !actionText }"
            :value="values.content" :errorText="formErrors.content?'failed':''"
            @input="v=>values.content=v"
            :hints="formErrors.content"
            label="Content Berita"
            placeholder="Tuliskan Content Berita"
            :check="false" /> 
      </div>
      <div>   
        <FieldUpload
            class="w-full !mt-3"
            :bind="{ readonly: !actionText }"
            :value="values.lampiran" @input="(v)=>values.lampiran=v" :maxSize="10"
            :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]"
            label=""
            :api="{
              url: `${store.server.url_backend}/operation/t_pengumuman/upload`,
              headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { field: 'lampiran' },
              onsuccess: response=>response,
              onerror:(error)=>{},
             }"
             :hints="formErrors.lampiran" label="Lampiran" placeholder="Upload Lampiran" fa-icon="upload"
             accept=".png,.jpg,.jpeg" :check="false"  
          />
      </div>
      <div>
        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3"
          :value="values.is_active" @input="v=>values.is_active=v"
          :errorText="formErrors.is_active?'failed':''" 
          :hints="formErrors.is_active"
          valueField="id" displayField="key"
          :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
          placeholder="Pilih Status" label="Status" :check="false"
        />
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