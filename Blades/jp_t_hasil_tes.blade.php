<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
      <!-- <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Inactive</button>
      </div> -->
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
        <h1 class="text-20px font-bold">Form Hasil Test Lamaran Kerja</h1>
        <p class="text-gray-100">Hasil Test Lamaran Kerja</p>
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
      <FieldPopup :value="values.t_pelamar_id" @input="v=>values.t_pelamar_id=v" class="w-full mt-3"
        :errorText="formErrors.t_pelamar_id?'failed':''" label="Pelamar" placeholder="Pilih Pelamar" @update:valueFull="(objVal)=>{
                
              }" valueField="id" displayField="nama_pelamar" :api="{
                url: `${store.server.url_backend}/operation/t_pelamar`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  searchfield:'this.nama_pelamar,m_dir.nama,m_divisi.nama,m_dept.nama,m_posisi.desc_kerja',
                }
              }" placeholder="Pilih Pelamar" :check="false" :columns="[
                {
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  field: 'nama_pelamar',
                  headerName:  'Nama',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
                {
                  flex: 1,
                  field: 'm_dir.nama',
                  headerName:  'Direktorat',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
                {
                  flex: 1,
                  field: 'm_divisi.nama',
                  headerName:  'Divisi',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
                {
                  flex: 1,
                  field: 'm_dept.nama',
                  headerName:  'Departemen',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
                {
                  flex: 1,
                  field: 'm_posisi.desc_kerja',
                  headerName:  'Posisi',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-start']
                },
              ]" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full mt-3" :value="values.tanggal"
        :errorText="formErrors.tanggal?'failed':''" @input="v=>values.tanggal=v" :hints="formErrors.tanggal"
        :check="false" type="date" label="Tanggal" placeholder="Masukan Tanggal" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3" label="" :value="values.jenis_tes"
        :errorText="formErrors.jenis_tes?'failed':''" @input="v=>values.jenis_tes=v" :hints="formErrors.jenis_tes"
        placeholder="Tuliskan Jenis Loker" label="Jenis Loker" :check="false" />
    </div>

    <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full mt-3"  :value="values.nilai_struktural" @change="countNilai()"
        :errorText="formErrors.nilai_struktural?'failed':''" @input="v=>values.nilai_struktural=v" :hints="formErrors.nilai_struktural"
        placeholder="Tuliskan Nilai Struktural" label="Nilai Struktural" :check="false" />
    </div>

        <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full mt-3"  :value="values.nilai_analitikal" @change="countNilai()"
        :errorText="formErrors.nilai_analitikal?'failed':''" @input="v=>values.nilai_analitikal=v" :hints="formErrors.nilai_analitikal"
        placeholder="Tuliskan Nilai analitikal" label="Nilai analitikal" :check="false" />
    </div>

            <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full mt-3"  :value="values.nilai_sosial" @change="countNilai()"
        :errorText="formErrors.nilai_sosial?'failed':''" @input="v=>values.nilai_sosial=v" :hints="formErrors.nilai_sosial"
        placeholder="Tuliskan Nilai sosial" label="Nilai sosial" :check="false" />
    </div>

                <div>
      <FieldNumber :bind="{ readonly: !actionText }" class="w-full mt-3"  :value="values.nilai_konseptual" @change="countNilai()"
        :errorText="formErrors.nilai_konseptual?'failed':''" @input="v=>values.nilai_konseptual=v" :hints="formErrors.nilai_konseptual"
        placeholder="Tuliskan Nilai konseptual" label="Nilai konseptual" :check="false" />
    </div>

    
    <div>
      <FieldNumber :bind="{ readonly: true }" class="w-full mt-3" type="number" label="Total Nilai"
        placeholder="Masukan Total Nilai Tes" :value="values.nilai_tes" :errorText="formErrors.nilai_tes?'failed':''"
        @input="v=>values.nilai_tes=v" :hints="formErrors.nilai_tes" :check="false" />
    </div>
    <div>
      <FieldX :bind="{ readonly: !actionText }" class="w-full mt-3" :value="values.deskripsi"
        :errorText="formErrors.deskripsi?'failed':''" @input="v=>values.deskripsi=v" :hints="formErrors.deskripsi"
        :check="false" type="textarea" label="Keterangan" placeholder="Tuliskan Keterangan" />
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