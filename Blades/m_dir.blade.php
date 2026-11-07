@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
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
    <div class="flex flex-col border rounded-2xl shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <!-- HEADER START -->
      <div class="flex items-center justify-between mb-2 pb-4">
        <h2 class="font-sans text-xl flex justify-left font-bold">
          Form Direktorat
        </h2>
      
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid <md:grid-cols-1 grid-cols-2 grid-flow-row gap-x-4 gap-y-4 mb-5">
        <div class="grid grid-cols-12 items-center gap-y-2">
          <label class="col-span-12">Instansi</label>
          <FieldX  
            class="col-span-12 !mt-0"
            :bind="{ disabled:true, readonly:true }"
            :value="values.company" :errorText="formErrors.company?'failed':''"
            @input="v=>values.company=v"
            :hints="formErrors.company"
            :check="false" />
        </div>
        <div class="grid grid-cols-12 items-center gap-y-2">
          <label class="col-span-12">Nama <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX  
            class="col-span-12 !mt-0"
            :bind="{ readonly: !actionText }"
            :value="values.nama" :errorText="formErrors.nama?'failed':''"
            @input="v=>values.nama=v"
            :hints="formErrors.nama"
            label=""
            placeholder="Masukan Nama"
            :check="false" />
        </div>
        <div class="grid grid-cols-12 items-center gap-y-2">
          <label class="col-span-12">Keterangan <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX  
            class="col-span-12 !mt-0"
            :bind="{ readonly: !actionText }"
            :value="values.desc" :errorText="formErrors.desc?'failed':''"
            @input="v=>values.desc=v"
            type="textarea"
            label=""
            placeholder="Tuliskan Keterangan"
            :hints="formErrors.desc"
            :check="false" />
        </div>
        <div class="grid grid-cols-12 items-start gap-y-2">
          <label class="col-span-12">Status <label class="text-red-500 space-x-0 pl-0">*</label></label>
           <input
              class="mr-2 h-3.5 !-mt-4 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
              type="checkbox"
              role="switch"
              id="is_active"
              :disabled="!actionText"
              v-model="values.is_active" />
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