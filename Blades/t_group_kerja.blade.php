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
         Form Grup Kerja
        </h1>
      </div>
      <!-- HEADER END -->
      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Nomor<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" 
              type="text" 
              :value="values.nomor"
              class="col-span-12 !mt-0 w-full"
              @input="v=>values.nomor=v" 
              :check="false"
              /> 
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Direktorat<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable: false }"
              class="col-span-12 !mt-0 w-full"
              :value="values.m_dir_id"
              @input="v => (values.m_dir_id = v)"
              :errorText="formErrors.m_dir_id ? 'failed' : ''"
              :hints="formErrors.m_dir_id"
                @update:valueFull="(objVal)=>{
                  values.m_dir_id = null
                }"
               displayField="nama"
              :api="{
                    url: `${store.server.url_backend}/operation/m_dir`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    where: `this.is_active = 'true'`
              }"
              valueField="id"
              :check="false"
            />    
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Divisi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_divisi_id" @input="v=>values.m_divisi_id=v"
              :errorText="formErrors.m_divisi_id?'failed':''" 
              :hints="formErrors.m_divisi_id"
              @update:valueFull="(objVal)=>{
                  values.m_divisi_id = null
                }"
               displayField="nama"
              :api="{
                  url: `${store.server.url_backend}/operation/m_divisi`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    where: `m_divisi.m_dir_id=${values.m_dir_id ?? 0} AND this.is_active = 'true'`
                  }
              }"
              valueField="id"
              :check="false"
            />  
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Departemen<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.m_dept_id" @input="v=>values.m_dept_id=v"
              :errorText="formErrors.m_dept_id?'failed':''" 
              :hints="formErrors.m_dept_id"
               @update:valueFull="(objVal)=>{
                  values.m_dept_id = null
                }"
               displayField="nama"
              :api="{
                    url: `${store.server.url_backend}/operation/m_dept`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      simplest:true,
                      where: `m_dept.m_divisi_id=${values.m_divisi_id ?? 0} AND this.is_active = 'true'`
                    }
              }"
              valueField="id"
              :check="false"
            />  
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tanggal Mulai<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldX :bind="{ readonly: false }" type="date" :value="values.date_from"
                class="col-span-12 !mt-0 w-full"
              @input="v=>values.date_from=v" 
              fa-icon="calender" :check="false"
          />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tanggal Berakhir<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldX :bind="{ readonly: false }" type="date" :value="values.date_to"
                class="col-span-12 !mt-0 w-full"
               @input="v=>values.date_to=v" 
               fa-icon="calender" :check="false"
          /> 
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Keterangan<label class="text-red-500 space-x-0 pl-0">*</label></label>
           <FieldX :bind="{ readonly: false }" 
              type="textarea" 
              :value="values.keterangan"
              class="col-span-12 !mt-0 w-full"
              @input="v=>values.keterangan=v" 
              :check="false"
              />        
          </div>
        </div>

        <div class="col-span-8 md:col-span-6">
          <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.status" :errorText="formErrors.status?'failed':''"
              @input="v=>values.status=v" :hints="formErrors.status" :check="false"
              label="" 
            />
        </div>
         <!-- <div class="flex flex-col gap-2">
          <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer"
            for="is_active"
            >Status</label
          >
          <div class="flex w-40 items-center">
            <div class="flex-auto">
              <i class="text-red-500">InActive</i>
            </div>
            <div class="flex-auto">
              <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="is_active"
                :disabled="!actionText"
                v-model="values.status" />
            </div>
            <div class="flex-auto">
              <i class="text-green-500">Active</i>
            </div>
          </div>
        </div> -->

      <!--BUTTON-->
      </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">

            <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] w-32 ">
            Kembali
          </button>
          <button v-show="route.query.action?.toLowerCase() === 'verifikasi'" @click="posted" class="bg-orange-500 hover:bg-orange-600 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Posted
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