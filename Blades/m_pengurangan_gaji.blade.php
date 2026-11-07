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
        <h1 class="text-[24px] mb-[10px] font-bold">
          {{actionText}} Form pengurangan Gaji
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tipe<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.tipe" @input="v=>values.tipe=v"
              :errorText="formErrors.tipe?'failed':''" 
              :hints="formErrors.tipe"
              valueField="key" displayField="key"
              :options="['Pengurangan Tetap', 'Pengurangan Tidak Tetap']"
              :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.is_active" @input="v=>values.is_active=v"
              :errorText="formErrors.is_active?'failed':''" 
              :hints="formErrors.is_active"
              valueField="id" displayField="key"
               :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
              :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Kisaran Minimum<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.k_minimun" @input="(v)=>values.k_minimun=v"
              :errorText="formErrors.k_minimun?'failed':''" 
              :hints="formErrors.k_minimun" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Kisaran Maksimum<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }"
              :value="values.k_maksimum" @input="(v)=>values.k_maksimum=v"
              :errorText="formErrors.k_maksimum?'failed':''" 
              :hints="formErrors.k_maksimum" class="col-span-12 !mt-0 w-full" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Berdasarkan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.berdasarkan" @input="v=>values.berdasarkan=v"
              :errorText="formErrors.berdasarkan?'failed':''" 
              :hints="formErrors.berdasarkan"
              valueField="key" displayField="key"
              :options="['Percentage', 'Amount']"
              :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Nilai Pengurangan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }"
              :value="values.n_pengurangan" @input="(v)=>values.n_pengurangan=v"
              :errorText="formErrors.n_pengurangan?'failed':''" 
              :hints="formErrors.n_pengurangan" class="col-span-12 !mt-0 w-full" :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Periode<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.periode" @input="v=>values.periode=v"
              :errorText="formErrors.periode?'failed':''" 
              :hints="formErrors.periode"
              valueField="key" displayField="key"
              :options="['Daily Period', 'Salary Period']"
              :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Referensi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.referensi" @input="v=>values.referensi=v"
              :errorText="formErrors.referensi?'failed':''" 
              :hints="formErrors.referensi"
              valueField="id" displayField="key"
              :options="['Qty', 'Absence Qty', 'Late Qty']"
              :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Variabel<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.variabel" :errorText="formErrors.variabel?'failed':''"
              @input="v=>values.variabel=v" :hints="formErrors.variabel" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Note<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="textarea" class="col-span-12 !mt-0 w-full"
              :value="values.deskripsi" :errorText="formErrors.deskripsi?'failed':''"
              @input="v=>values.deskripsi=v" :hints="formErrors.deskripsi" :check="false"
            />
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