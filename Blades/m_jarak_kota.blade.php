
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
         Form Jarak Kota
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="flex gap-5">
        <div class="flex-1">
          <div class="flex flex-col mb-4">
            <label class="font-semibold">Dari <span class="text-red-500">*<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-5 !mt-1 w-full"
              :value="values.dari" @input="v=>values.dari=v"
              :errorText="formErrors.dari ? 'failed' : ''" 
              :hints="formErrors.dari"
              valueField="dari" displayField="dari"
              :check="false"
              :api="{
                    url: `https://backend.qqltech.com/kodepos/region/kota`,
                    headers: {
                      //'Content-Type': 'Application/json'
                    },
                    params: {
                      search:'',
                      searchfield:'dari',
                      selectfield: 'dari.id',
                      paginate:25
                    },
                    onsuccess:function(responseJson){
                      return{
                        data: responseJson,
                        page:1,
                        hasNext:false
                      }
                    }
                }"
               :check="false"
            />
          </div>

          <div class="flex flex-col gap-2">
            <div class="flex flex-col mb-6.3">
              <label class=" font-semibold " >Ke <span class="text-red-500">*<label class="text-red-500 space-x-0 pl-0"></label></label>
              <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-5 !mt-1 w-full"
              :value="values.ke" @input="v=>values.ke=v"
              :errorText="formErrors.ke ? 'failed' : ''" 
              :hints="formErrors.ke"
              valueField="ke" displayField="ke"
              :check="false"
              :api="{
                    url: `https://backend.qqltech.com/kodepos/region/kota`,
                    headers: {
                      //'Content-Type': 'Application/json'
                    },
                    params: {
                      search:'',
                      searchfield:'ke',
                      selectfield: 'ke.id',
                      paginate:25
                    },
                    onsuccess:function(responseJson){
                      return{
                        data: responseJson,
                        page:1,
                        hasNext:false
                      }
                    }
                }"
               :check="false"
            />
            </div>
          </div>
        </div>
        
        <div class="flex-1">
          <div class="flex flex-col mb-4  ">
              <label class=" font-semibold " >Status <label class="text-red-500 space-x-0 pl-0"></label></label>
              <div class=" grid md:grid-cols-1 grid-cols-12">
                <FieldSelect
                  :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-1 w-full"
                  :value="values.is_active" @input="v=>values.is_active=v"
                  :errorText="formErrors.is_active?'failed':''" 
                  :hints="formErrors.is_active"
                  valueField="id" displayField="key"
                  :options="[{'id' : true , 'key' : 'Active'},{'id': false, 'key' : 'InActive'}]"
                  :check="false"
                />
              </div>
            </div>

            <div class="flex flex-col">
              <label class=" font-semibold " >Jarak <span class="text-red-500">*<label class="text-red-500 space-x-0 pl-0"></label></label>
              <div class=" grid md:grid-cols-1 grid-cols-12">
              <FieldX class="mt-1" :bind="{ readonly: false }" :value="values.username" @input="v=>values.username=v" fa-icon="user" :check="false"/>
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