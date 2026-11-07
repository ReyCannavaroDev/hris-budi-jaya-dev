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
         Form Gaji Jabatan
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
   <div class="grid grid-cols-2 gap-6">
        <!-- START COLUMN -->
      
        <div>
          <label class="font-semibold">Divisi <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldSelect
            class="col-span-12 !mt-2 w-full"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.divisi" @input="v=>values.divisi=v"
            :errorText="formErrors.divisi?'failed':''" 
            :hints="formErrors.divisi"
            valueField="id" displayField="key"
            :options="['divisi 1', 'divisi 2']"
            :check="false"
            
          />
          
        </div>
       <div>
          <label class="font-semibold">Departemen <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldSelect
            class="col-span-12 !mt-2 w-full"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.departemen" @input="v=>values.departemen=v"
            :errorText="formErrors.departemen?'failed':''" 
            :hints="formErrors.departemen"
            valueField="id" displayField="key"
            :options="['departemen 1', 'departemen 2']"
            :check="false"
            
          />
          
        </div>
  
         <div>
          <label class="font-semibold">Jabatan <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldSelect
            class="col-span-12 !mt-2 w-full"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.jabatan" @input="v=>values.jabatan=v"
            :errorText="formErrors.jabatan?'failed':''" 
            :hints="formErrors.jabatan"
            valueField="id" displayField="key"
            :options="['jabatan 1', 'jabatan 2']"
            :check="false"
            
          />
          
        </div>
        <div>
          <label class="font-semibold">kode <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.kode" :errorname="formErrors.kode?'failed':''"
              @input="v=>values.kode=v" :hints="formErrors.kode" 
              :check="false"
          />
        </div>
        <div>
          <label class="font-semibold">Deskripsi Singkat <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.['deskripsi_singkat']" :errorname="formErrors.['deskripsi_singkat']?'failed':''"
              @input="v=>values.['deskripsi_singkat']=v" :hints="formErrors.['deskripsi_singkat']" 
              :check="false"
          />

          <div class="mt-3">
            <label class="font-semibold">Status Karyawan <label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
            class="col-span-12 !mt-2 w-full"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.['status_karyawan']" @input="v=>values.['status_karyawan']=v"
            :errorText="formErrors.['status_karyawan']?'failed':''" 
            :hints="formErrors.['status_karyawan']"
            valueField="id" displayField="key"
            :options="['status 1', 'status 2']"
            :check="false"
            
           />
          
          </div>
        </div>
        
        <div>
          <label class="font-semibold">Deskripsi Lengkap <label class="text-red-500 space-x-0 pl-0 block"></label></label>
          <textarea class="border border-gray-200 rounded-md w-full mt-2 h-24 p-3 text-gray-600 outline-none" placeholder="Masukkan Deskripsi"></textarea>
        </div>
         <div class="mt-3">
          <label class="font-semibold">Note<label class="text-red-500 space-x-0 pl-0 block"></label></label>
          <textarea class="border border-gray-200 rounded-md w-full mt-2 h-24 p-3 text-gray-600 outline-none" placeholder="Masukkan Deskripsi"></textarea>
        </div>
         <div class="mt-3">
            <label class="font-semibold">Status <label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
            class="col-span-12 !mt-2 w-full"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.status" @input="v=>values.status=v"
            :errorText="formErrors.status?'failed':''" 
            :hints="formErrors.status"
            valueField="id" displayField="key"
            :options="['status 1', 'status 2']"
            :check="false"
            
           />
          
          </div>

        <!-- gaji pokok -->
        <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6" v-model="checkGaji"/>
            <label class="font-semibold text-[18px]">Gaji Pokok</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldNumber
              :bind="{ disabled: !actionText, clearable:false }"
              :value="detailValues.besaran" @input="v=>detailValues.besaran=v"
              :errorText="formErrors.besaran?'failed':''" 
              :hints="formErrors.besaran"
              :check="false"
              class="!mt-0"
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="detailValues.periode" @input="v=>detailValues.periode=v"
              :errorText="formErrors.periode?'failed':''" 
              :hints="formErrors.periode"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='["Bulanan", "Mingguan", "Harian"]'
            />
          </div> 
          </div>
        </div>
        <!-- tunjangan tetap -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6" v-model="checkTunjangan"/>
            <label class="font-semibold text-[18px]">Tunjangan Tetap</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldNumber
              :bind="{ disabled: !actionText, clearable:false }"
              :value="detailValues.besaran" @input="v=>detailValues.besaran=v"
              :errorText="formErrors.besaran?'failed':''" 
              :hints="formErrors.besaran"
              :check="false"
              class="!mt-0"
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="detailValues.periode" @input="v=>detailValues.periode=v"
              :errorText="formErrors.periode?'failed':''" 
              :hints="formErrors.periode"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='["Bulanan", "Mingguan", "Harian"]'
            />
          </div> 
          </div>
        </div>
         <!-- Uang Saku -->
        <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Uang Saku</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

         <!-- Tunjangan Masa Kerja -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Tunjangan Masa Kerja</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

         <!-- Tunjangan Profesi -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Tunjangan Profesi</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Tunjangan Komunikasi -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Tunjangan Komunikasi</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Honorarium -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Honorarium</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Tunjangan Transportasi -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Tunjangan Transportasi</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Tunjangan Jabatan -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Tunjangan Jabatan</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- P1 -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">P1</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Premi shift Pagi -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Premi shift Pagi</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Lembur -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Lembur</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Premi Shift Siang -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Premi Shift Siang</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Lembur Piket Hari Istirahat -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Lembur Piket Hari Istirahat</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

           <!-- Premi Shift Malam -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Premi Shift Malam</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Lembur Pekerjaan Waktu Lebih -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Lembur Pekerjaan Waktu Lebih</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>

            <!-- Uang Makan -->
         <div class="mt-3">
          <div class="flex items-center gap-2">
            <input type="checkbox" class="w-6 h-6"/>
            <label class="font-semibold text-[18px]">Uang Makan</label>
          </div>
          <div class="flex items-center gap-2 pt-3">
          <div class="flex-1 ">
            <label>Besaran</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div>  
          <div class="flex-1 ">
            <label>Periode</label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }"
              :value="values.name" @input="v=>values.name=v"
              :errorText="formErrors.name?'failed':''" 
              :hints="formErrors.name"
              valueField="id" displayField="key"
              :check="false"
              class="!mt-0"
              :options='[1,2]'
            />
          </div> 
          </div>
        </div>
        <!-- END COLUMN -->
        <!-- ACTION BUTTON START -->
        <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Kembali
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
        </div>
      </div>
      <!-- FORM END -->
    </div>
  </div>
</div>
@endverbatim
@endif