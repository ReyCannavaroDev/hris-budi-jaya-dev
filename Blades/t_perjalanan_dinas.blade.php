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
          {{actionText}} Form Perjalanan Dinas
        </h1>
        <div class="border border-[#DEDEDE] bg-[#F8F8F8] rounded-[15px] w-full px-[17px] py-[10px]">
          <h3>Notifikasi</h3>
          <ul class="list-disc pl-[18px]">
            <li>Tipe A = Keberangkatan sebelum hari H</li>
            <li>Tipe B = Keberangkatan pada hari H</li>
          </ul>
        </div>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">No Draft<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" class="col-span-12 !mt-0 w-full"
              :value="values.nama" :errorText="formErrors.nama?'failed':''"
              @input="v=>values.nama=v" :hints="formErrors.nama" :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" class="col-span-12 !mt-0 w-full"
              :value="values.nama" :errorText="formErrors.nama?'failed':''"
              @input="v=>values.nama=v" :hints="formErrors.nama" :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Pegawai<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldPopup
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.pegawai_id" @input="(v)=>values.pegawai_id=v"
              :errorText="formErrors.pegawai_id?'failed':''" 
              :hints="formErrors.pegawai_id" 
              valueField="id" displayField="key"
              :api="{
                url: `${store.server.url_backend}/operation/m_menu`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                }
              }" :check="false" 
              :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'columnname',
                headerName:  'Label Header Name',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              }]"
            />
            
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <!-- kosong -->
        </div>
        <div class="col-span-8 md:col-span-6">
          <label class="col-span-12">SPPD A.</label>
        </div>
          <div class="col-span-8 md:col-span-6">
          <!-- kosong -->
        </div>
        <div class="col-span-8 md:col-span-6">
           <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tipe<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldPopup
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.pegawai_id" @input="(v)=>values.pegawai_id=v"
              :errorText="formErrors.pegawai_id?'failed':''" 
              :hints="formErrors.pegawai_id" 
              valueField="id" displayField="key"
              :api="{
                url: `${store.server.url_backend}/operation/m_menu`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                }
              }" :check="false" 
              :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'columnname',
                headerName:  'Label Header Name',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              }]"
            />
            
            
          </div>
         
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="w-full flex items-center gap-2">
          <div class="flex-1 flex flex-col gap-1.5">
            <label class="col-span-12">Tanggal acara<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <input class="py-2 px-3 border border-gray-200 rounded-md" type="date" />
          </div>
            <div class="flex-1 flex flex-col gap-1.5 pt-5">
            <input class="py-2 px-3 border border-gray-200 rounded-md" type="date" />
          </div>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
           <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Darl<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldPopup
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.pegawai_id" @input="(v)=>values.pegawai_id=v"
              :errorText="formErrors.pegawai_id?'failed':''" 
              :hints="formErrors.pegawai_id" 
              valueField="id" displayField="key"
              :api="{
                url: `${store.server.url_backend}/operation/m_menu`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                }
              }" :check="false" 
              :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'columnname',
                headerName:  'Label Header Name',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              }]"
            />
            
            
          </div>
         
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tujuan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldPopup
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.pegawai_id" @input="(v)=>values.pegawai_id=v"
              :errorText="formErrors.pegawai_id?'failed':''" 
              :hints="formErrors.pegawai_id" 
              valueField="id" displayField="key"
              :api="{
                url: `${store.server.url_backend}/operation/m_menu`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                }
              }" :check="false" 
              :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'columnname',
                headerName:  'Label Header Name',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              }]"
            /> 
         </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">SPPD Lanjutan</label>
           <div class="flex items-center gap-4 mt-4">
              <div class="flex items-center gap-2">
                <input type="checkbox" class="w-5 h-5 rounded-full"/>
                <label>Iya</label>
              </div>
                <div class="flex items-center gap-2">
                <input type="checkbox" class="w-5 h-5 rounded-full"/>
                <label>Tidak</label>
              </div>
           </div>
         </div>
        </div>
        <div class="col-span-8 md:col-span-6">
         <!-- kosong -->
        </div>
        <div class="col-span-8 md:col-span-6">
           <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Jumlah Hari</label></label>
            <FieldX :bind="{ readonly: true }" class="col-span-12 !mt-0 w-full"
              :value="values.nama" :errorText="formErrors.nama?'failed':''"
              @input="v=>values.nama=v" :hints="formErrors.nama" :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
           <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Total Biaya</label>
            <FieldX :bind="{ readonly: true }" class="col-span-12 !mt-0 w-full"
              :value="values.nama" :errorText="formErrors.nama?'failed':''"
              @input="v=>values.nama=v" :hints="formErrors.nama" :check="false"
            />
            
          </div>
        </div>
         <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tipe</label>
           <div class="flex items-center gap-4 mt-4">
              <div class="flex items-center gap-2">
                <input type="checkbox" class="w-5 h-5 rounded-full"/>
                <label class="col-span-12">Tipe<label class="ml-2">A</label></label>
              </div>
                <div class="flex items-center gap-2">
                <input type="checkbox" class="w-5 h-5 rounded-full"/>
                 <label class="col-span-12">Tipe<label class="ml-2">B</label></label>
              </div>
           </div>
         </div>
        </div>
        <div class="col-span-8 md:col-span-6">
         <!-- kosong -->
        </div>
          <div class="col-span-8 md:col-span-6">
            <div class="w-full flex flex-col gap-4">
                <div class="flex items-center gap-2">
                <input type="radio" class="w-5 h-5"/>
                 <label class="col-span-12">Tiket (Biaya Angkutan Transportasi)</label>
              </div>
               <div class="flex items-center gap-2">
                <input type="radio" class="w-5 h-5"/>
                 <label class="col-span-12">Mobil Pribadi</label>
              </div>
                <div class="flex items-center gap-2">
                <input type="radio" class="w-5 h-5"/>
                 <label class="col-span-12">Kendaraan Dinas</label>
              </div>
                <div class="flex items-center gap-2">
                <input type="radio" class="w-5 h-5"/>
                 <label class="col-span-12">Biaya tidak ditanggung Perusahaan</label>
              </div>
                 <div class="flex items-center gap-2">
                <input type="radio" class="w-5 h-5"/>
                 <label class="col-span-12">Pelaksana Harian</label>
              </div>
            </div>
        </div>
        <div class="col-span-8 md:col-span-6">
           <div class="col-span-8 md:col-span-6">
           <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Total Biaya</label>
            <FieldX :bind="{ readonly: !actionText }" 
              :value="values.name" :errorText="formErrors.name?'failed':''"
              @input="v=>values.name=v" :hints="formErrors.name" 
            :check="false"
               class="col-span-12 !mt-0 w-full"
            />
            
            
          </div>
        </div>
         <div class="col-span-8 md:col-span-6 mt-6">
           <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Total Biaya</label>
            <FieldX :bind="{ readonly: !actionText }" 
              :value="values.name" :errorText="formErrors.name?'failed':''"
              @input="v=>values.name=v" :hints="formErrors.name" 
            :check="false"
               class="col-span-12 !mt-0 w-full"
            />
            
            
          </div>
        </div>
        </div>

         <div class="col-span-8 md:col-span-6 mt-6">
           <div class="flex flex-col gap-2">
            <label class="col-span-12">Total Biaya</label>
            <textarea class="border border-gray-200 rounded-md flex-1 p-2 outline-none text-gray-600" rows="10"></textarea>
          </div>
        </div>
         <div class="col-span-8 md:col-span-6 mt-6">
           <div class="flex flex-col gap-2">
            <label class="col-span-12">Catatan</label>
            <textarea class="border border-gray-200 rounded-md flex-1 p-2 outline-none text-gray-600" rows="10"></textarea>
          </div>
        </div>
         <div class="col-span-8 md:col-span-6 mt-6">
             <button  class="bg-[#10B981] hover:bg-[#10B981] text-white px-[36.5px] py-[12px] rounded-[6px] ">
           Auto Generate
          </button>
        </div>
         <div class="col-span-8 md:col-span-6 mt-6">
         <!-- kosong -->
        </div>
      </div>
     <div class="mt-5">
      <!-- table -->
    <table class="w-full">
      <thead>
        <tr>
          <th class="border border-gray-400 bg-[#CACACA] text-[#8F8F8F] p-2 ">No</th>
          <th class="border border-gray-400 bg-[#CACACA] text-[#8F8F8F] p-2 ">Komponen</th>
          <th class="border border-gray-400 bg-[#CACACA] text-[#8F8F8F] p-2 ">Total Biaya</th>
          <th class="border border-gray-400 bg-[#CACACA] text-[#8F8F8F] p-2 ">Catatan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="border border-gray-400 bg-white  text-center text-black p-2 ">data</td>
          <td class="border border-gray-400 bg-white  text-center text-black p-2 ">data</td>
          <td class="border border-gray-400 bg-white  text-center text-black p-2 ">data Biaya</td>
          <td class="border border-gray-400 bg-white  text-center text-black p-2 ">data</td>
      </tr>
      </tbody>
    </table>
      
     </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
            <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white p-[12px] rounded-[6px] ">
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