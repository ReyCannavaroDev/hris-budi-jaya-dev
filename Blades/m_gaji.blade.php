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
         Form Gaji
        </h1>
      </div>
      <!-- HEADER END -->
      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Divisi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.divisi" @input="v=>values.divisi=v"
              :errorText="formErrors.divisi?'failed':''" 
              :hints="formErrors.divisi"
              valueField="id" displayField="key"
              :options="['Otaku', 'Wibu']"
              :check="false"
            />            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Departement<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.departement" @input="v=>values.departement=v"
              :errorText="formErrors.departement?'failed':''" 
              :hints="formErrors.departement"
              valueField="id" displayField="key"
              :options="['Active', 'Inactive']"
              :check="false"
            />            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Jabatan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.jabatan" @input="v=>values.jabatan=v"
              :errorText="formErrors.jabatan?'failed':''" 
              :hints="formErrors.jabatan"
              valueField="id" displayField="key"
              :options="['Diriktur Otaku', 'Diriktur Wibu']"
              :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tipe Gaji<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.tipe_gaji" @input="v=>values.tipe_gaji=v"
              :errorText="formErrors.tipe_gaji?'failed':''" 
              :hints="formErrors.tipe_gaji"
              valueField="id" displayField="key"
              :options="['Dikit', 'Banyak']"
              :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Karyawan<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldX :bind="{ readonly: true }" class="col-span-12 !mt-0 w-full"
                :value="values.karyawan" :errorText="formErrors.karywan?'failed':''"
                @input="v=>values.karyawan=v" :hints="formErrors.karyawan" :check="false"
              />
          </div>
        </div>
        <!--DUMMY-->
            <div class="col-span-8 md:col-span-6 mt-5"> 
            </div>
          <!--Button-->
          <div class="col-span-8 md:col-span-6"> 
            <button class="bg-green-500 text-white px-6 py-2 rounded-xl h-13 w-32 hover:bg-green-600">
              Show Data
            </button>
          </div>
        <!--DUMMY-->
            <div class="col-span-8 md:col-span-6 mt-5"> 
            </div>
        <div class="col-span-8 md:col-span-6 mt-[10px]">
          <h2 class="font-bold text-[18px]">Memperbarui Gaji</h2>
        </div>
        <!--DUMMY-->
        <div class="col-span-8 md:col-span-6 mt-[10px]">
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tipe<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.tipe" @input="v=>values.tipe=v"
              :errorText="formErrors.tipe?'failed':''" 
              :hints="formErrors.tipe"
              valueField="id" displayField="key"
              :options="['Dikit', 'Banyak']"
              :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Komponen Gaji<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.komponen_gaji" @input="v=>values.komponen_gaji=v"
              :errorText="formErrors.komponen_gaji?'failed':''" 
              :hints="formErrors.komponen_gaji"
              valueField="id" displayField="key"
              :options="['Dikit', 'Banyak']"
              :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Nilai<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-5 !mt-0 w-full"
              :value="values.komponen_gaji" @input="v=>values.komponen_gaji=v"
              :errorText="formErrors.komponen_gaji?'failed':''" 
              :hints="formErrors.komponen_gaji"
              valueField="id" displayField="key"
              :options="['Dikit', 'Banyak']"
              :check="false"
            />
              <hr class="w-[10px] mx-auto border-black col-span-2">
              <FieldX :bind="{ readonly: !actionText }" class="col-span-5 !mt-0 w-full"
                :value="values.tgl_masuk" :errorText="formErrors.tgl_masuk?'failed':''"
                @input="v=>values.tgl_masuk=v" :hints="formErrors.tgl_masuk"  :check="false"
              />
          </div>
        </div>
        <!--DUMMY-->
            <div class="col-span-8 md:col-span-6 mt-5"> 
            </div>
          <!--Button-->
          <div class="col-span-8 md:col-span-6 space-x-14"> 
            <button class="bg-green-500 text-white px-6 py-2 rounded-xl h-13 w-32 hover:bg-green-600">
              Process
            </button>
            <button class="bg-blue-500 text-white px-6 py-2 rounded-xl h-13 hover:bg-blue-600">
              Recalculative Overtime
            </button>
          </div>
        <!--DUMMY-->
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
          </div>
        </div>
        <div class="col-span-8 md:col-span-6 mt-[10px]">
          <h2 class="font-bold text-[18px]">Memperbarui Gaji</h2>
        </div>
        <div class="col-span-8 md:col-span-6 mt-[10px]">
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tipe Lain<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.komponen_gaji" @input="v=>values.komponen_gaji=v"
              :errorText="formErrors.komponen_gaji?'failed':''" 
              :hints="formErrors.komponen_gaji"
              valueField="id" displayField="key"
              :options="['Dikit', 'Banyak']"
              :check="false"
            />
          </div>
        </div>
      <!--BUTTON-->
      </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <button  v-show="actionText" @click="onPrint" class="bg-yellow-500 hover:bg-yellow-600 text-white px-[36.5px] py-[12px] w-32 rounded-[6px] ">
            Print
          </button>
            <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] w-32 ">
            Kembali
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