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
    <div class="flex flex-col border rounded-2xl shadow-sm px-6 py-6 <md:w-full w-full bg-white w-[50%]">

      <!-- HEADER START -->
      <div class="flex items-center justify-between mb-2 pb-4">
        <h2 class="font-sans text-xl flex justify-left font-bold">
          Form Prefix Nomor
        </h2>
      
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid <md:grid-cols-1 gap-2">
        <!-- <div>
          <label class="font-semibold">Direktorat</label>
          <FieldX  
            class="col-span-12 !mt-0"
            :bind="{ disabled:true, readonly:true }"
            :value="values.direktorat" :errorText="formErrors.direktorat?'failed':''"
            @input="v=>values.direktorat=v"
            :hints="formErrors.direktorat"
            :check="false" />
        </div> -->

        <div>
          <label class="font-semibold">Nama <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX  
            class="col-span-12 !mt-0"
            :bind="{ readonly: !actionText }"
            :value="values.nama" :errorText="formErrors.nama?'failed':''"
            @input="v=>values.nama=v"
            :hints="formErrors.nama"
            label=""
            placeholder="ex: PREFIX LOKASI"
            :check="false" />
        </div>
        
        <div>
          <label class="font-semibold">Tipe <label class="text-red-500 space-x-0 pl-0">*</label></label>
          ‍‍<FieldSelect
             class="col-span-12 !mt-0"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.ref_type" :errorText="formErrors.ref_type?'failed':''"
            @input="v=>values.ref_type=v"
            :hints="formErrors.ref_type"
            :options="['text','seq','day','month','year']"
            placeholder="Pilih Tipe Prefix" fa-icon="external-link-square-alt" :check="false"
            label=""
        />
        </div>
        <div>
          <label class="font-semibold">Value <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX  
            class="col-span-12 !mt-0"
            :bind="{ readonly: !actionText }"
            :value="values.value" :errorText="formErrors.value?'failed':''"
            @input="v=>values.value=v"
            :hints="formErrors.value"
            label=""
            placeholder="ex: LOK"
            :check="false" />
        </div>
         <div class="flex flex-col gap-2">
          <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="is_active"
            >Status :</label
          >
          <div class="flex w-40">
            <div class="flex-auto">
              <i class="text-red-500">InActive</i>
            </div>
            <div class="flex-auto">
              <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                :class="{'after:bg-gray-500': values.is_active === false}"
                type="checkbox"
                role="switch"
                id="is_active"
                :disabled="!actionText"
                v-model="values.is_active" />
            </div>
            <div class="flex-auto">
              <i class="text-green-500">Active</i>
            </div>
          </div>
        </div>
      </div>
      <div class="flex justify-end gap-4 mt-4">
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