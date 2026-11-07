@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'" :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
        <icon fa="plus" />Tambah Data
      </RouterLink>
    </template>
  </TableApi>
</div>
@else

@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Form Hasil Test Lamaran Kerja
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
        <div>
          <label class="font-semibold">Nomor<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.nomor" :errorText="formErrors.nomor?'failed':''"
              @input="v=>values.nomor=v" :hints="formErrors.nomor" 
              :check="false"
              label=""
              placeholder=""
          />
        </div>
        <div>
          <label class="font-semibold">Pelamar<span class="text-red-500 space-x-0 pl-0"> *</span></label>
          <FieldPopup
              :value="values.t_pelamar_id"
              @input="v=>values.t_pelamar_id=v"
              class="w-full py-2 !mt-0"
              :errorText="formErrors.t_pelamar_id?'failed':''"
              label=""
              @update:valueFull="(objVal)=>{
                
              }"
              valueField="id" displayField="nama_pelamar"
              :api="{
                url: `${store.server.url_backend}/operation/t_pelamar`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  searchfield:'this.nama_pelamar,m_dir.nama,m_divisi.nama,m_dept.nama,m_posisi.desc_kerja',
                }
              }"
              placeholder="Pilih Pelamar" :check="false" 
              :columns="[
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
              ]"
            />
        </div>
        <div>
          <label class="font-semibold">Tanggal <span class="text-red-500 space-x-0 pl-0"> *</span></label>
          <FieldX :bind="{ readonly: !actionText , required: true}" class="w-full py-2 !mt-0"
              :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''"
              @input="v=>values.tanggal=v" :hints="formErrors.tanggal" 
              :check="false"
              type="date"
              label=""
              placeholder="DD/MM/YYYY"
          />
        </div>
        <div>
          <label class="font-semibold">Jenis Loker <span class="text-red-500 space-x-0 pl-0"> *</span></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
                label=""
                :value="values.jenis_tes" :errorText="formErrors.jenis_tes?'failed':''"
                @input="v=>values.jenis_tes=v" :hints="formErrors.jenis_tes" 
                placeholder="Tuliskan Jenis Loker" :check="false"
              />
        </div>
        <div>
          <label class="font-semibold">Nilai Struktural <span class="text-red-500 space-x-0 pl-0"> *</span></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
                type="number"
                label=""
                @change="countNilai()"
                :value="values.nilai_struktural" :errorText="formErrors.nilai_struktural?'failed':''"
                @input="v=>values.nilai_struktural=v" :hints="formErrors.nilai_struktural" 
                placeholder="0,00" :check="false"
              />
        </div>
        <div>
          <label class="font-semibold">Nilai Analitikal <span class="text-red-500 space-x-0 pl-0"> *</span></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
                type="number"
                label=""
                @change="countNilai()"
                :value="values.nilai_analitikal" :errorText="formErrors.nilai_analitikal?'failed':''"
                @input="v=>values.nilai_analitikal=v" :hints="formErrors.nilai_analitikal" 
                placeholder="0,00" :check="false"
              />
        </div>
        <div>
          <label class="font-semibold">Nilai Sosial <span class="text-red-500 space-x-0 pl-0"> *</span></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
                type="number"
                label=""
                @change="countNilai()"
                :value="values.nilai_sosial" :errorText="formErrors.nilai_sosial?'failed':''"
                @input="v=>values.nilai_sosial=v" :hints="formErrors.nilai_sosial" 
                placeholder="0,00" :check="false"
              />
        </div>
        <div>
          <label class="font-semibold">Nilai Konseptual <span class="text-red-500 space-x-0 pl-0"> *</span></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
                type="number"
                label=""
                @change="countNilai()"
                :value="values.nilai_konseptual" :errorText="formErrors.nilai_konseptual?'failed':''"
                @input="v=>values.nilai_konseptual=v" :hints="formErrors.nilai_konseptual" 
                placeholder="0,00" :check="false"
              />
        </div>
        <div>
          <label class="font-semibold">Total Nilai <span class="text-red-500 space-x-0 pl-0"> *</span></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
                type="number"
                label=""
                :value="values.nilai_tes" :errorText="formErrors.nilai_tes?'failed':''"
                @input="v=>values.nilai_tes=v" :hints="formErrors.nilai_tes" 
                placeholder="0,00" :check="false"
              />
        </div>
        <div>
          <label class="font-semibold">Keterangan<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.deskripsi" :errorText="formErrors.deskripsi?'failed':''"
              @input="v=>values.deskripsi=v" :hints="formErrors.deskripsi" 
              :check="false"
              type="textarea"
              label=""
              placeholder="Tuliskan Keterangan"
          />
        </div>
        <div>
          <label class="font-semibold">Status<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" class="w-[50%] py-2 !mt-0"
              :value="values.status" :errorText="formErrors.status?'failed':''"
              @input="v=>values.status=v" :hints="formErrors.status" 
              :check="false"
              label=""
              placeholder=""
          />
        </div>
        <!-- END COLUMN -->
      </div>
        <!-- ACTION BUTTON START -->
        <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Batal
          </button>
          <button v-show="route.query.action?.toLowerCase() === 'verifikasi'" @click="posted" class="bg-orange-500 hover:bg-orange-600 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Posted
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
        </div>
    </div>
  </div>
</div>
@endverbatim
@endif