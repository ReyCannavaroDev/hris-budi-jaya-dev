
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
        <h1 class="text-[24px] mb-[10px] font-bold">
         Form Contract
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="flex gap-5">

        <div class="flex-1">
          <div class="flex flex-col mb-4">
            <label class=" font-semibold " >Kode<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect
              class="mt-1"
              :value="values.dropdown"
              @input="v=>values.dropdown=v" 
              valueField="id" displayField="menu"
              :api="{
                  url: `${store.server.url_backend}/operation/m_menu`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    single: true,
                    join: false,
                  }
              }"
              placeholder="" fa-icon="" :check="false"
            />
          </div>
          <div class="flex flex-col gap-2">
            <div class="flex flex-col mb-6.3">
              <label class=" font-semibold " >Tipe<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldSelect
                class="mt-1"
                :value="values.dropdown"
                @input="v=>values.dropdown=v" 
                valueField="id" displayField="menu"
                :api="{
                    url: `${store.server.url_backend}/operation/m_menu`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      single: true,
                      join: false,
                    }
                }"
                placeholder="" fa-icon="" :check="false"
              /> 
            </div>
          </div>
          <div class="flex flex-col gap-2">
            <div class="flex flex-col mb-6.3">
              <label class=" font-semibold " >File (doc or docx) <span class="text-red-500">*<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldUpload
                class="mt-1"
                :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]"
                :api="{
                  url: `${store.server.url_backend}/operation/m_menu/upload`,
                  headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: { field: 'name' },
                  onsuccess: response=>response,
                  onerror:(error)=>{},
                }" placeholder="" fa-icon=""
                accept="*" :check="false"  
              />
            </div>
          </div>
        </div>
        
        <div class="flex-1">
          <div class="flex flex-col mb-4  ">
              <label class=" font-semibold " >Deskripsi <span class="text-red-500">*<label class="text-red-500 space-x-0 pl-0"></label></label>
              <div class=" grid md:grid-cols-1 grid-cols-12">
              <textarea class="border border-gray-200 rounded-md w-full mt-2 h-28 p-3 text-gray-600 outline-none" placeholder=""></textarea>
            </div>
          </div>
          <div class="flex flex-col mb-6.3">
              <label class=" font-semibold " >Job <label class="text-red-500 space-x-0 pl-0"></label></label>
              <div class=" grid md:grid-cols-1 grid-cols-12">
              <FieldSelect
                class="mt-1"
                :value="values.dropdown"
                @input="v=>values.dropdown=v" 
                valueField="id" displayField="menu"
                :api="{
                    url: `${store.server.url_backend}/operation/m_menu`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      single: true,
                      join: false,
                    }
                }"
                placeholder="" fa-icon="" :check="false"
              />
            </div>
          </div>
          <div class="flex flex-col">
              <label class=" font-semibold " >Note <label class="text-red-500 space-x-0 pl-0"></label></label>
              <div class=" grid md:grid-cols-1 grid-cols-12">
              <textarea class="border border-gray-200 rounded-md w-full mt-1 h-28 p-3 text-gray-600 outline-none" placeholder=""></textarea>
            </div>
          </div>
        </div>
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