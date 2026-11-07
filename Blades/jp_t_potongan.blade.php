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
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
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
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Transaksi Potongan</h1>
        <p class="text-gray-100">Hasil Transaksi Potongan</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" type="text" :value="values.nomor" class="w-full mt-3"
        @input="v=>values.nomor=v" :check="false" placeholder="Masukan Nomor" label="Nomor" />
    </div>
    <div>
          <FieldPopup :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.m_kary_id"
            @input="(v)=>values.m_kary_id=v" :errorText="formErrors.m_kary_id?'failed':''" :hints="formErrors.m_kary_id"
            valueField="id" displayField="nama_depan" @update:valueFull="(objVal)=>{
          values.m_divisi_lama_id = objVal['m_divisi.id']
          values.m_dept_lama_id = objVal['m_dept.id']
          values.m_posisi_lama_id = objVal['m_posisi.id']
          values.m_standart_posisi_id = objVal['m_standart_gaji.gaji_pokok']
        }" :api="{
          url: `${store.server.url_backend}/operation/m_kary`,
          headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          params: {
            simplest:true,
            searchfield:'nik, nama_depan, m_dir.nama, m_divisi.nama, id'
          }
        }"  placeholder="Pilih Karyawan" label="Karyawan" :check="false" :columns="[
          {
            headerName: 'No',
            valueGetter: (p) => p.node.rowIndex + 1,
            width: 60,
            sortable: false, 
            resizable: false, 
            filter: false,
            cellClass: ['justify-center', 'bg-gray-50']
          },
          {
            flex: 1,
            field: 'nik',
            headerName: 'NIK',
            sortable: false, 
            resizable: true, 
            filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
          {
            flex: 1,
            field: 'nama_depan',
            headerName: 'Nama',
            sortable: false, 
            resizable: true, 
            filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
          {
            flex: 1,
            field: 'm_zona.nama',
            headerName: 'Zona',
            sortable: false, 
            resizable: true, 
            filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },              
          {
            flex: 1,
            field: 'm_dir.nama',
            headerName: 'Direktorat',
            sortable: false, 
            resizable: true, 
            filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
          {
            flex: 1,
            field: 'm_divisi.nama',
            headerName: 'Divisi',
            sortable: false, 
            resizable: true, 
            filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },      
          {
            flex: 1,
            field: 'm_dept.nama',
            headerName: 'Departemen',
            sortable: false, 
            resizable: true, 
            filter: 'ColFilter',
            cellClass: ['border-r', '!border-gray-200', 'justify-center']
          },
        
          // Add columns for m_posisi data if needed
          ]" />
    </div>
    <div>
          <FieldSelect
            :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" label=""
            :value="values.jenis_potongan_id" @input="v=>values.jenis_potongan_id=v"
            :errorText="formErrors.jenis_potongan_id?'failed':''" 
            :hints="formErrors.jenis_potongan_id"
            valueField="id" displayField="value"
            :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  transform:false,
                  join:false,
                  where: `this.is_active = true AND this.group = 'JENIS POTONGAN'`
                }
            }"
            placeholder="Pilih Jenis Potongan" label="Jenis Potongan" :check="false"
          />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3" label="" :value="values.jenis_tes"
        :errorText="formErrors.jenis_tes?'failed':''" @input="v=>values.jenis_tes=v" :hints="formErrors.jenis_tes"
        placeholder="Tuliskan Jenis Loker" label="Jenis Loker" :check="false" />
    </div>

    <div>
          <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full mt-3"
            :value="values.date_from" :errorText="formErrors.date_from?'failed':''"
            :hints="formErrors.date_from" 
            :check="false"
            type="date"
            label="Tanggal Awal"
            placeholder="Pilih Tanggal Awal"
            @input="(v)=>{values.date_from=v}"
          />
    </div>

    <div>
          <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full mt-3"
            :value="values.date_to" :errorText="formErrors.date_to?'failed':''"
            :hints="formErrors.date_to" 
            :check="false"
            type="date"
            label="Tanggal Akhir"
            placeholder="Pilih Tanggal Akhir"
            @input="(v)=>{values.date_to=v}"
          />
    </div>

    <div>
          <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3"
            placeholder="Tuliskan Nomor Dokumen"
            :value="values.no_doc" :errorText="formErrors.no_doc?'failed':''"
            @input="v=>values.no_doc=v" :hints="formErrors.no_doc" 
            :check="false"
            label="Nomor Dokumen"
          />
    </div>

    <div>
            <FieldUpload class="w-full mt-3" :bind="{ readonly: !actionText }"
              :value="values.doc" @input="(v)=>values.doc=v" :maxSize="10"
              :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]"
              :api="{
                url: `${store.server.url_backend}/operation/t_potongan/upload`,
                headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
                params: { field: 'doc' },
                onsuccess: response=>response,
                onerror:(error)=>{},
                }"
                :hints="formErrors.doc" label="Berkas" placeholder="Upload Berkas" fa-icon="upload"
                accept="application/pdf" :check="false"  
            />
    </div>
    <div>
          <FieldNumber :bind="{ readonly: !actionText }" class="w-full mt-3"
            placeholder="0.00"
            :value="values.nilai" :errorText="formErrors.nilai?'failed':''"
            @input="v=>values.nilai=v" :hints="formErrors.nilai" 
            :check="false"
            label="Nilai"
          />
    </div>
    <div>
          <FieldNumber :bind="{ readonly: !actionText }" class="w-full mt-3"
            placeholder="1%"
            :value="values.percentage" :errorText="formErrors.percentage?'failed':''"
            @input="v=>values.percentage=v" :hints="formErrors.percentage" 
            :check="false"
            label="Persentase Angsuran"
          />
    </div>

    <div> 
                <FieldX 
          class="w-full mt-3"
           :bind="{ readonly: !actionText  }" type="textarea" :value="values.keterangan"
            @input="v=>values.keterangan=v" 
            placeholder="Tulis Keterangan"
            label="Keterangan"
             fa-icon="" :check="false"
          />
    </div>

            <div class="grid w-full mt-3">
          <label class="">Potongan Seluruh Karyawan <label class="text-red-500 space-x-0 pl-0">*</label></label>
            <div class="">
              <div class="grid w-full mt-3">
                <div class="flex items-center col-span-6">
                  <input type="radio" :value="true" v-model="values.is_all_kary" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                  <label class="ml-2  font-medium text-gray-900 dark:text-gray-300">Iya</label>
                </div>
                <div class="flex items-center col-span-6">
                  <input type="radio" :value="false" v-model="values.is_all_kary" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                  <label class="ml-2  font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                </div>
              </div>
            </div>
        </div>

    <div>
      <FieldX placeholder="Masukan Status" label="Status" :bind="{ readonly: true }" type="text" :value="values.status"
        class="w-full mt-3" @input="v=>values.status=v" :check="false" />
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
    <button v-show="route.query.action?.toLowerCase() === 'verifikasi'" @click="posted" class="bg-orange-500 hover:bg-orange-600 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Posted
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