@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <div>
        <!-- BUTTON MODAL DISINI -->
        <button @click="openModal" class="bg-blue-500 text-white hover:bg-blue-600 font-semibold rounded-[4px] py-1 px-[10px]">
          Absensi Lembur
        </button>
      </div>

      <!-- Modal Component -->
      <div v-show="modalOpen" class="fixed z-10 inset-0 overflow-y-auto duration-200 ">
        <div class="flex items-center justify-center min-h-screen bg-gray-900/50 ">
          <div
            class="bg-white rounded-lg p-6 shadow-xl bg-clip-border p-6 border-2 border-black border-dashed h-auto w-[120vh]">
            <h2 class="text-xl font-bold mb-4">List Absensi Lembur</h2>
            <div class="w-full p-2">
            <TableApi  class="h-[50vh]" ref='modalTable' :api="modal.api" :columns="modal.columns" 
               :actions="modal.actions"> </TableApi>
            </div>
            <div class="flex justify-end  w-full mt-10">
              <button @click="closeModal" class="bg-red-500 text-white hover:bg-red-600 font-semibold rounded-[4px] py-1 px-[10px]">Close</button>
            </div>
          </div>
        </div>
      </div>

      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="bg-green-500 text-white hover:bg-green-600  font-semibold rounded-[4px] py-1 px-[10px]">
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
      <div class="flex items-center mb-2 border-b pb-4">
        <button class="py-1 px-2 rounded transition-all text-blue-900 bg-white border border-blue-900 duration-300 hover:text-white hover:bg-blue-600" @click="onBack">
            <icon fa="arrow-left" size="sm"/>
            
          </button>
        <div v-show="is_to_upload">
          <h2 v-if="!is_approval" class="mx-4 font-sans text-xl flex justify-left font-bold">
            Upload Data
          </h2>
        </div>
        <div v-show="!is_to_upload">
          <h2 v-if="!is_approval" class="mx-4 font-sans text-xl flex justify-left font-bold">
            {{actionText==='Edit'?'Ubah':actionText}} Lembur
          </h2>
          <h2 v-else class="mx-4 font-sans text-xl flex justify-left font-bold">
            Notifikasi Approval Lembur
          </h2>
        </div>
      </div>
      <!-- HEADER END -->




      <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
        <!-- START COLUMN -->
        <div>
          <label class="font-semibold">Nomor<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldX :bind="{ readonly: true }" label="" class="w-full py-2 !mt-0" :value="values.nomor"
            :errorText="formErrors.nomor?'failed':''" @input="v=>values.nomor=v" :hints="formErrors.nomor"
            :check="false" label="" placeholder="Nomor" />
        </div>
        <div>
          <label class="font-semibold">Karyawan<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldPopup :bind="{ readonly: !actionText || !store.user.data?.is_superadmin }" class="w-full py-2 !mt-0"
            :value="values.m_kary_id" @input="(v)=>values.m_kary_id=v" :errorText="formErrors.m_kary_id?'failed':''"
            :hints="formErrors.m_kary_id" valueField="id" displayField="nama_depan" :api="{
                  url: `${store.server.url_backend}/operation/m_kary`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    simplest:true,
                    searchfield:'id, nama_depan, nik, m_divisi.nama, m_zona.nama, m_dir.nama',
                    where:`this.is_active=true`
                  }
                }" placeholder="Pilih Karyawan" label="" :check="false" :columns="[
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
            ]" />
        </div>
        <div>
          <label class="font-semibold">Tanggal Lembur<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type="date" class="w-full py-2 !mt-0" :value="values.tanggal"
            label="" placeholder="Pilih Tanggal" :errorText="formErrors.tanggal?'failed':''"
            @input="v=>values.tanggal=v" :hints="formErrors.tanggal" :check="false" />
        </div>

        <!-- DUMMY -->
        <div>
        </div>

        <div class="col-span-1 md:col-span-1">
          <label class="font-semibold">Jam Mulai<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText   }" class="py-2 !mt-0 w-full" type="time" fa-icon="clock"
            :value="values.jam_mulai" :errorText="formErrors.jam_mulai ? 'failed' : ''"
            @input="v => values.jam_mulai =v" :hints="formErrors.jam_mulai" :check="false" />
        </div>
        <div class="col-span-1 md:col-span-1">
          <label class="font-semibold">Jam Selesai<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" class="py-2 !mt-0 w-full" type="time" fa-icon="clock"
            :value="values.jam_selesai" :errorText="formErrors.jam_selesai ? 'failed' : ''"
            @input="v => values.jam_selesai =v" :hints="formErrors.jam_selesai" :check="false" />
        </div>
        
        <div>
          <label class="font-semibold">Tipe Lembur<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
            :value="values.tipe_lembur_id" @input="v=>values.tipe_lembur_id=v"
            :errorText="formErrors.tipe_lembur_id?'failed':''" :hints="formErrors.tipe_lembur_id" label=""
            placeholder="Pilih lembur" valueField="id" displayField="value" :api="{
            url: `${store.server.url_backend}/operation/m_general`,
            headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
            },
            params: {
            simplest: true,
            transform: false,
            join: true,
            where:`this.group='TIPE LEMBUR' AND this.is_active='true'`,
            selectfield: 'this.id, this.code, this.value, this.is_active'
            }
            }" :check="false" />
        </div>
        <div>
          <label class="font-semibold">Alasan<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
            :value="values.alasan_id" @input="v=>values.alasan_id=v" :errorText="formErrors.alasan_id?'failed':''"
            :hints="formErrors.alasan_id" label="" placeholder="Alasan" valueField="id" displayField="value" :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: {
              'Content-Type': 'Application/json',
              Authorization: `${store.user.token_type} ${store.user.token}`
              },
              params: {
              simplest: true,
              transform: false,
              join: true,
              where:`this.group='ALASAN LEMBUR' AND this.is_active='true'`,
              selectfield: 'this.id, this.code, this.value, this.is_active'
              }
              }" :check="false" />
        </div>
        <div>
          <label class="font-semibold">No.Dokumen<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.no_doc"
            :errorText="formErrors.no_doc?'failed':''" @input="v=>values.no_doc=v" :hints="formErrors.no_doc"
            :check="false" label="" />
        </div>
        <div>
          <label class="font-semibold">Upload dokumen<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldUpload class="w-full py-2 !mt-0" :bind="{ readonly: !actionText }" :value="values.doc"
            @input="(v)=>values.doc=v" :maxSize="10"
            :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]" :api="{
                  url: `${store.server.url_backend}/operation/t_lembur/upload`,
                  headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: { field: 'doc' },
                  onsuccess: response=>response,
                  onerror:(error)=>{},
                 }" :hints="formErrors.doc" label="" placeholder="Upload Berkas" fa-icon="upload"
            accept="application/pdf" :check="false" />
        </div>

        <div>
          <label class="col-span-12">Keterangan<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: !actionText }" type='textarea' class="w-full py-2 !mt-0" :value="values.keterangan"
            :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v"
            :hints="formErrors.keterangan" :check="false" label="" placeholder="Tuliskan keterangan" />
        </div>

         <div class="hidden">
          <label class="col-span-12">REF_ID<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: !actionText }"  class="w-full py-2 !mt-0" :value="values.ref_id"
            :errorText="formErrors.ref_id?'failed':''" @input="v=>values.ref_id=v"
            :hints="formErrors.ref_id" :check="false" label="" placeholder="Tuliskan ref_id" />
        </div>

        <div class="hidden">
          <label class="col-span-12">REF_table<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: !actionText }"  class="w-full py-2 !mt-0" :value="values.ref_table"
            :errorText="formErrors.ref_table?'failed':''" @input="v=>values.ref_table=v"
            :hints="formErrors.ref_table" :check="false" label="" placeholder="Tuliskan ref_id" />
        </div>


        <div>
          <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0" :value="values.status"
            :errorText="formErrors.status?'failed':''" @input="v=>values.status=v" :hints="formErrors.status"
            :check="false" label="" placeholder="" />
        </div>

        <div v-show="route.query.is_approval">
          <table class=" w-[100%] my-3 border">
            <tr class="border">
              <td class="border px-2 py-1 font-semibold">Nomor</td>
              <td class="border px-2 py-1">{{ values.approval?.nomor ?? '-' }}</td>
            </tr>
            <tr class="border">
              <td class="border px-2 py-1 font-semibold">Tanggal</td>
              <td class="border px-2 py-1">{{ values.approval?.created_at ?? '-' }}</td>
            </tr>
            <tr class="border">
              <td class="border px-2 py-1 font-semibold">Pemohon</td>
              <td class="border px-2 py-1">{{ values.approval?.creator ?? '-' }}</td>
            </tr>
            <tr class="border">
              <td class="border px-2 py-1 font-semibold">Status</td>
              <td class="border px-2 py-1">{{ values.approval?.status ?? '-' }}</td>
            </tr>
          </table>
        </div>

        <div class="">
          <table class=" w-[100%] my-3 ">
            <tr>
              <td class=" px-2 py-1">
                <button
                    v-show="route.query.is_approval"
                    @click="openModal(values?.trx?.id ?? 0)"
                    class="hover:text-blue-500">
                    <icon fa="table" size="sm"/>
                    Log Approval
                  </button>
              </td>
            </tr>
            <!-- <tr v-show="isFinish || isApproved">
                <td class=" px-2 py-1">
                  <button
                    @click="downloadDoc()" 
                    class="hover:text-blue-500">
                    <icon fa="download" size="sm"/>
                    Download .docx
                  </button>
                </td>
              </tr> -->
          </table>
        </div>
        <!-- END COLUMN -->
      </div>

      <!-- Modal Content -->
      <div v-show="modalOpen" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white  w-[70%] mx-auto rounded shadow-lg z-50 overflow-y-auto">
          <div class="modal-content py-4 text-left px-6">
            <!-- Modal Header -->
            <div class="modal-header flex items-center justify-between flex-wrap">
              <div class="flex items-center">
                <h3 class="text-xl font-semibold ml-2">Log Approval
                  <span v-if="!dataLog.items.length" class="!text-red-600"> | Belum ada log approval</span>
                </h3>
              </div>
            </div>

            <!-- Modal Body -->
            <div v-if="dataLog.items.length" class="modal-body">
              <table class="w-[100%] my-3 border">
                <thead>
                  <tr class="border">
                    <td class="border px-2 py-1 font-medium ">Urutan</td>
                    <td class="border px-2 py-1 font-medium ">Nomor Transaksi</td>
                    <td class="border px-2 py-1 font-medium ">Tipe Aksi</td>
                    <td class="border px-2 py-1 font-medium ">Tanggal Aksi </td>
                    <td class="border px-2 py-1 font-medium ">User Aksi</td>
                    <td class="border px-2 py-1 font-medium ">Catatan</td>
                  </tr>
                </thead>
                <tr class="border" v-for="d,i in dataLog.items" :key="i">
                  <td class="border px-2 py-1">{{ i+1 }}</td>
                  <td class="border px-2 py-1">{{ d.trx_nomor ?? '-' }}</td>
                  <td class="border px-2 py-1">{{ d.action_type ?? '-' }}</td>
                  <td class="border px-2 py-1">{{ d.action_at ?? '-' }}</td>
                  <td class="border px-2 py-1">{{ d.action_user ?? '-' }}</td>
                  <td class="border px-2 py-1">{{ d.action_note ?? '-' }}</td>
                </tr>
              </table>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer flex justify-end mt-2">
              <button @click="closeModal" class="modal-button bg-yellow-500 hover:bg-yellow-600 text-white font-semibold ml-2 px-2 py-1 rounded-sm">
          Tutup
        </button>
            </div>
          </div>
        </div>
      </div>
      <div v-show="route.query.is_approval" class="w-1/2 mt-6">
        <label class="col-span-12 font-semibold">Catatan Approval<label class="text-red-500 space-x-0 pl-0"></label></label>
        <FieldX :bind="{ readonly: false }" class="w-full py-2 !mt-0" :value="values.catatan"
          :errorText="formErrors.catatan?'failed':''" @input="v=>values.catatan=v" :hints="formErrors.catatan"
          :check="false" label="" placeholder="Tuliskan catatan" />
      </div>




      <!-- ACTION BUTTON START -->
      <div class="flex flex-row justify-end space-x-[20px] mt-[1em]">
        <button v-show="route.query.is_approval" class="mx-1 bg-green-500 text-white hover:bg-green-600 rounded-[4px] px-[36.5px] py-[5px]" @click="onProcess('approve')">
              Approve
            </button>
        <button v-show="route.query.is_approval" class="mx-1 bg-rose-500 text-white hover:bg-rose-600 rounded-[4px] px-[36.5px] py-[5px]" @click="onProcess('reject')">
              Reject
            </button>
        <button v-show="route.query.is_approval" class="mx-1 bg-amber-500 text-white hover:bg-amber-600 rounded-[4px] px-[36.5px] py-[5px]" @click="onProcess('revise')">
              Revise
            </button>
        <button v-show="route.query.action?.toLowerCase() === 'verifikasi'" @click="posted" class="bg-orange-500 hover:bg-orange-600 text-white px-[36.5px] py-[5px] font-semibold rounded-[4px] ">
            Posted
          </button>
        <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[5px] font-semibold rounded-[4px]">
            Simpan
          </button>
      </div>
    </div>
  </div>
</div>
@endverbatim
@endif