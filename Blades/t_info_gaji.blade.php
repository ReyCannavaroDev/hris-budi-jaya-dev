@if(!$req->has('id'))
@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Info Gaji
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
        <div>
          <label class="font-semibold">Periode Awal<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldX :bind="{ readonly: false , required: true}" class="w-full py-2 !mt-0"
              :value="values.periode_awal" :errorText="formErrors.periode_awal?'failed':''"
              :hints="formErrors.periode_awal" 
              :check="false"
              type="month"
              label=""
              placeholder="YYYY-MM"
              @input="(v)=>{
                //$log(v)
                values.periode_awal=v
                detailArr = []
                //$log(values.divisi)
              }"
          />
        </div>
        <div>
          <label class="font-semibold">Periode Akhir<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldX :bind="{ readonly: false , required: true}" class="w-full py-2 !mt-0"
              :value="values.periode_akhir" :errorText="formErrors.periode_akhir?'failed':''"
              :hints="formErrors.periode_akhir" 
              :check="false"
              type="month"
              label=""
              placeholder="YYYY-MM-"
              @input="(v)=>{
                //$log(v)
                values.periode_akhir=v
                detailArr = []
                //$log(values.divisi)
              }"
          />
        </div>
        <div>
          <label class="font-semibold">Divisi<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
            :bind="{ disabled: false, clearable:false }"
            :value="values.divisi" 
            :check="false"
            @input="(v)=>{
              //$log(v)
              values.divisi=v
              values.m_divisi_id=v
              values.m_dept_id=''
              detailArr = []
              //$log(values.divisi)
            }"
            :errorText="formErrors.divisi?'failed':''"
            :hints="formErrors.divisi"
            displayField="nama"
            valueField="id"
            :api="{
                url: `${store.server.url_backend}/operation/m_divisi`,
                headers: {
                  //'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }"
            fa-icon="search" :check="true" />
        </div>
        <div>
          <label class="font-semibold">Departemen<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
            :bind="{ disabled: false, clearable:false }"
            :value="values.m_dept_id" 
            :check="false"
            @input="(v)=>{
              //$log(v)
              values.m_dept_id=v
              detailArr = []
              //$log(values.departemen)
            }"
            :errorText="formErrors.m_dept_id?'failed':''"
            :hints="formErrors.m_dept_id"
            displayField="nama"
            valueField="id"
            :api="{
                url: `${store.server.url_backend}/operation/m_dept`,
                headers: {
                  //'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  simplest:true,
                  single:true,
                  scopes: 'filterDivisi',
                  divisi_id: values.m_divisi_id ?? null,
                  transform:false,
                }
            }"
            fa-icon="search" :check="true" />
        </div>
      </div>
      
      <div class="flex flex-row justify-center space-x-[10px] mt-[1em]">
        <button @click="generatePerhitungan" class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-[18px] py-[8px] rounded-[4px] ">
          <Icon fa="bolt"/> Generate
        </button>
        <button @click="detailArr = []" class="bg-[#EF4444] hover:bg-[#ed3232] text-white text-sm px-[18px] py-[8px] rounded-[4px] ">
          Hapus Detail
        </button>
      </div>
        
      <div class="mt-4">
        <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
          <thead>
            <tr class="border">
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[20%] border bg-[#f8f8f8] border-[#CACACA]">Karyawan</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[20%] border bg-[#f8f8f8] border-[#CACACA]">Periode</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[12,8%] border bg-[#f8f8f8] border-[#CACACA]">Total Gaji</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[12,8%] border bg-[#f8f8f8] border-[#CACACA]">Deskripsi</td>
              <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">Aksi</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX
                  :bind="{ readonly: true}" class="!mt-0"
                  :value="item.karyawan" @input="v=>item.karyawan=v"
                  type="text"
                  label=""
                  :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX
                  :bind="{ readonly: true}" class="!mt-0"
                  :value="item.periode" @input="v=>item.periode=v"
                  type="text"
                  label=""
                  :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldNumber
                  :bind="{ readonly: true}" class="!mt-0 flex justify-end"
                  :value="item.total_gaji" @input="v=>item.total_gaji=v"
                  type="number"
                  label=""
                  :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <FieldX
                  :bind="{ readonly: true}" class="!mt-0"
                  :value="item.deskripsi" @input="v=>item.deskripsi=v"
                  type="textarea"
                  label=""
                  :check="false"
                />
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button @click="openDetail(i)" class="rounded-lg flex items-center justify-center">
                      <icon fa="circle-info" size="lg">
                    </button>
                </div>

              </td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="7" class="py-[20px]">
                No data to show
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Content -->
      <!-- Modal Content -->
      <div v-show="modalOpen" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white  w-[70%] mx-auto rounded shadow-lg z-50 overflow-y-auto">
          <div class="modal-content py-4 text-left px-6">
            <!-- Modal Header -->
          <div class="modal-header flex items-center justify-between flex-wrap">
            <div class="flex items-center">
              <h3 class="text-xl font-semibold ml-2">Rincian Gaji {{ titleOpen }}</h3>
            </div>
          </div>

            <!-- Modal Body -->
            <div class="modal-body">
               <table class="w-full overflow-x-auto mt-2 table-auto border border-[#CACACA]">
                  <thead>
                    <tr class="border">
                      <td class="font-bold text-capitalize p-2 text-left w-[20%] table-auto border border-[#CACACA] bg-dark-500 text-white">Komponen</td>
                      <td class="font-bold text-capitalize p-2 text-right w-[15%] table-auto border border-[#CACACA] bg-dark-500 text-white">Besaran</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="border" v-for="(a,i) in detailArrOpen.items" :key="i">
                      <td class="text-left border border-gray-300 p-1" :class="a.factor == '=' ? 'font-bold bg-gray-200' : ''">{{ a.label }}</td>
                      <td class="text-right border border-gray-300 p-1" :class="a.factor == '=' ? 'font-bold bg-gray-200' : ''">{{ (a.factor == '-' ? '(-) ' : '') + formatRupiah(a.value) }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer flex justify-center mt-2">
              <button @click="closeModal" class="modal-button bg-red-500 hover:bg-red-600 text-white font-semibold ml-2 px-2 py-1 rounded-sm">
                Tutup
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@endverbatim
<!-- <div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'" :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
        Tambah
        <icon fa="plus" />
      </RouterLink>
    </template>
  </TableApi>
</div> -->
@else


@endif