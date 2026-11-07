@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'"
        :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
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
          Finalisasi Gaji
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
<div>
  <label class="font-semibold">Periode Awal<span class="text-red-500 space-x-0 pl-0">*</span></label>
  <FieldX 
    :bind="{ readonly: !actionText, required: true }" 
    class="w-full py-2 !mt-0"
    :value="values.periode_awal" 
    :errorText="formErrors.periode_awal ? 'failed' : ''"
    :hints="formErrors.periode_awal" 
    :check="false" 
    type="date" 
    @input="checkTglStDate" 
    label=""
    placeholder="YYYY-MM-DD" 
  />
</div>

<div>
  <label class="font-semibold">Periode Akhir<span class="text-red-500 space-x-0 pl-0">*</span></label>
  <FieldX 
    :bind="{ readonly: !actionText || values.type_perhitungan !== 'HARIAN', required: true }" 
    class="w-full py-2 !mt-0" 
    :value="values.periode_akhir"
    :errorText="formErrors.periode_akhir ? 'failed' : ''" 
    :hints="formErrors.periode_akhir" 
    :check="false"
    :type="values.type_perhitungan === 'HARIAN' ? 'date' : 'text'" 
    @input="checkTglEdDate" 
    label="" 
    placeholder="YYYY-MM-DD" 
  />
</div>
<!-- 
<div>
  <label class="font-semibold">Tipe Gaji<span class="text-red-500 space-x-0 pl-0"></span></label>
  <FieldSelect 
    class="w-full py-2 !mt-0" 
    :bind="{ disabled: !actionText, clearable: true }"
    :value="values.type_perhitungan" 
    :check="false" 
    @input="(v) => values.type_perhitungan = v"
    :errorText="formErrors.type_perhitungan ? 'failed' : ''" 
    :hints="formErrors.type_perhitungan"
    :options="['HARIAN', 'BORONGAN']" 
    :check="true" 
  />
</div> -->

<div>
  <label class="font-semibold">Tipe Gaji<span class="text-red-500 space-x-0 pl-0"></span></label>
  <FieldSelect 
    class="w-full py-2 !mt-0" 
    :bind="{ disabled: !actionText, clearable: true }"
    :value="values.type_perhitungan" 
    :check="false" 
    @input="(v) => values.type_perhitungan = v"
    :errorText="formErrors.type_perhitungan ? 'failed' : ''" 
    :hints="formErrors.type_perhitungan"
    :options="['HARIAN', 'BORONGAN']" 
    :check="true" 
  />
</div>

        <div>
          <label class="font-semibold">Divisi<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldSelect class="w-full py-2 !mt-0" :bind="{ disabled: !actionText, clearable:true }"
            :value="values.divisi" :check="false" @input="(v)=>{
              //$log(v)
              values.divisi=v
              values.m_divisi_id=v
              values.m_dept_id=''
              detailArr = []
              //$log(values.divisi)
            }" :errorText="formErrors.divisi?'failed':''" :hints="formErrors.divisi" displayField="nama"
            valueField="id" :api="{
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
            }" fa-icon="search" :check="true" />
        </div>
        <div>
          <label class="font-semibold">Departemen<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldSelect class="w-full py-2 !mt-0" :bind="{ disabled: !actionText, clearable:true }"
            :value="values.m_dept_id" :check="false" @input="(v)=>{
              //$log(v)
              values.m_dept_id=v
              detailArr = []
              //$log(values.departemen)
            }" :errorText="formErrors.m_dept_id?'failed':''" :hints="formErrors.m_dept_id" displayField="nama"
            valueField="id" :api="{
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
            }" fa-icon="search" :check="true" />
        </div>
        <div>
          <label class="font-semibold">Deskripsi Pendek<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldX :bind="{ readonly: !actionText}" class="!mt-0" :value="values.desc" @input="v=>values.desc=v"
            :errorText="formErrors.desc?'failed':''" :hints="formErrors.desc" type="textarea" label="" :check="false" />
        </div>
        <div>
          <label class="font-semibold">Total Pengeluaran Gaji<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldNumber :bind="{ readonly: true}" class="!mt-0 flex justify-end" :value="values.total_pengeluaran_gaji"
            @input="v=>values.total_pengeluaran_gaji=v" type="number" label="" :check="false" />
        </div>
      </div>

      <div class="flex flex-row justify-center space-x-[10px] mt-[1em]">
        <button v-show="actionText?.toLowerCase() !== 'edit'" :disabled="!actionText" @click="generatePerhitungan" class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-[18px] py-[8px] rounded-[4px] ">
          <Icon fa="bolt"/> Generate
        </button>
        <button v-show="actionText?.toLowerCase() !== 'edit'" :disabled="!actionText" @click="detailArr = []" class="bg-[#EF4444] hover:bg-[#ed3232] text-white text-sm px-[18px] py-[8px] rounded-[4px] ">
          Hapus Detail
        </button>
      </div>

      <div class="mt-4">
        <table class="w-full overflow-x-auto table-auto border border-[#CACACA] " style="zoom: 80%">
          <thead>
            <tr class="border">
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                No.</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[10%] border bg-[#f8f8f8] border-[#CACACA]">
                Periode</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[12%] border bg-[#f8f8f8] border-[#CACACA]">
                NIK</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[20%] border bg-[#f8f8f8] border-[#CACACA]">
                Karyawan</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[12%] border bg-[#f8f8f8] border-[#CACACA]">
                Divisi</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[12%] border bg-[#f8f8f8] border-[#CACACA]">
                Departemen</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[15%] border bg-[#f8f8f8] border-[#CACACA]">
                Gaji Bersih</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[15%] border bg-[#f8f8f8] border-[#CACACA]">
                Deskripsi</td>
              <td
                class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                Aksi</td>
            </tr>
          </thead>
          <tbody>
            <tr v-if="detailArr.length" v-for="(item, i) in detailArr" :key="item.id"
              class="border-t hover:bg-yellow-200">
              <td class="text-center border border-[#CACACA] px-2">
                {{ i + 1 }}.
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.periode }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item['m_kary.kode'] }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.karyawan }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item['m_kary_divisi.nama'] }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item['m_kary_dept.nama'] }}
              </td>
              <td class="text-right border border-[#CACACA] px-2">
                {{ formatRupiah(item.netto) }}
              </td>
              <td class="border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText}" class="!mt-0" :value="item.deskripsi"
                  @input="v=>item.deskripsi=v" type="textarea" label="" :check="false" />
              </td>
              <td class="border border-[#CACACA]">
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

@verbatim
<!-- Modal Content -->
<div v-show="modalOpen" class="fixed inset-0 flex items-center justify-center z-50">
  <!-- Overlay -->
  <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>

  <!-- Modal Container -->
  <div class="modal-container bg-white w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 2xl:w-[80%] mx-auto rounded-lg shadow-lg z-50 overflow-y-auto max-h-[90vh]">
    <!-- Modal Content -->
    <div class="modal-content py-6 px-6">
      <!-- Modal Header -->
      <div class="modal-header flex items-center justify-between mb-4">
        <h3 class="text-xl font-semibold">Rincian Gaji {{ titleOpen }}</h3>
      </div>
      <hr class="border-t border-gray-200 mb-4">

      <!-- Modal Body -->
      <div class="modal-body overflow-y-auto ">
        <div :class="['grid gap-6', actionText ? 'md:grid-cols-2' : 'md:grid-cols-1']">
          <!-- Standar Gaji -->
          <div>
            <h3 class="font-semibold mb-2">Standar Gaji</h3>
            <div class="overflow-y-auto max-h-[370px]">
              <table class="w-full table-auto border border-gray-300">
                <thead>
                  <tr class="bg-gray-700 text-white">
                    <th class="p-2 text-left">Komponen</th>
                    <th class="p-2 text-right">Besaran</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(a, i) in detailArrOpen.items" :key="i" class="hover:bg-gray-100">
<td class="p-2 border border-gray-300" :class="a.factor == '=' ? 'font-bold bg-gray-200' : ''">
    {{ a.label }}
</td>
<td class="p-2 border border-gray-300 text-right"
    :class="a.factor == '=' ? 'font-bold bg-gray-200' : ''">
    {{ (a.factor == '-' ? '(-) ' : '') + formatRupiah(a.label === 'Total Gaji' ? a.netto : a.value) }}
</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Sesuaikan Gaji -->
          <div v-show="actionText">
            <h3 class="font-semibold mb-2">Sesuaikan Gaji</h3>
            <div class="overflow-y-auto max-h-[370px]">
              <table class="w-full table-auto border border-gray-300">
                <thead>
                  <tr class="bg-gray-700 text-white">
                    <th class="p-2 text-left">Komponen</th>
                    <th class="p-2 text-left">Faktor</th>
                    <th class="p-2 text-right">Besaran</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(a, i) in detailArrAdjOpen.items" :key="i" class="hover:bg-gray-100">
                    <td class="p-2 border border-gray-300" :class="a.factor == '=' ? 'font-bold bg-gray-200' : ''">
                      <span v-if="a.default === true">{{ a.label }}</span>
                      <div v-else class="flex items-center gap-2">
                        <FieldX :bind="{ readonly: !actionText }" class="!mt-0 flex-1"
                          :value="detailArrAdjOpen.items[i]['label']"
                          @input="v => detailArrAdjOpen.items[i]['label'] = v" placeholder="" label="" :check="false" />
                        <Icon fa="x" @click="deleteRow(a)" class="text-red-500 cursor-pointer" title="hapus baris" />
                      </div>
                    </td>
                    <td class="p-2 border border-gray-300" :class="a.factor == '=' ? 'font-bold bg-gray-200' : ''">
                      <span v-if="a.default === true">{{ a.factor }}</span>
                      <FieldSelect v-else :bind="{ disabled: !actionText, clearable: false }"
                        :value="detailArrAdjOpen.items[i]['factor']"
                        @input="v => detailArrAdjOpen.items[i]['factor'] = v" valueField="key" displayField="key"
                        :options="['+', '-']" placeholder="" label="" :check="false" />
                    </td>
                    <td class="p-2 border border-gray-300 text-right"
                      :class="a.factor == '=' ? 'font-bold bg-gray-200' : ''">
                      <FieldNumber :bind="{ readonly: !actionText }" class="!mt-0 w-full"
                        :value="detailArrAdjOpen.items[i]['value']"
                        @input="v => { detailArrAdjOpen.items[i]['value'] = v; summaryAdj() }" type="number" label=""
                        :check="false" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div @click="addRowAdj" class="flex justify-end mt-2 cursor-pointer text-blue-500 hover:text-blue-600">
              <i class="flex items-center gap-1">
                Tambah Baris <Icon fa="plus" />
              </i>
            </div>

            <!-- Total Penyesuaian -->
            <div class="mt-4">
              <table class="w-full table-auto border border-gray-300">
                <tbody>
                  <tr class="bg-gray-200">
                    <td class="p-2 font-bold">Total Penyesuaian Gaji</td>
                    <td class="p-2 text-right">
                      <FieldNumber :bind="{ readonly: !actionText }" class="!mt-0 w-full" :value="totalAdjOpen.value"
                        type="number" label="" :check="false" />
                    </td>
                  </tr>
                  <tr>
                    <td class="p-2">PPH 21 {{ totalAdjPPHOpen.value.length ? totalAdjPPHOpen.value[0].label : '' }}</td>
                    <td class="p-2 text-right">
                      <button v-if="!totalAdjPPHOpen.value.length" @click="generatePPH" class="bg-blue-600 px-3 py-1 text-white hover:bg-blue-700">
                        Hitung PPH
                      </button>
                      <FieldNumber v-else :bind="{ readonly: true }" class="!mt-0 w-full"
                        :value="totalAdjPPHOpen.value[0].value" type="number" label="" :check="false" />
                    </td>
                  </tr>
                  <tr class="bg-gray-200">
                    <td class="p-2 font-bold">Total Penyesuaian Gaji Setelah PPH 21</td>
                    <td class="p-2 text-right">
                      <FieldNumber :bind="{ readonly: true }" class="!mt-0 w-full" :value="totalAdjFinalOpen.value"
                        type="number" label="" :check="false" />
                    </td>
                  </tr>
                  <tr class="bg-gray-200">
                    <td class="p-2 font-bold">Grand Total ADJ</td>
                    <td class="p-2 text-right">
                      <FieldNumber :bind="{ readonly: true }" class="!mt-0 w-full" :value="grandTotalAdj" type="number"
                        label="" :check="false" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer flex justify-end mt-6">
        <button @click="closeModal" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-md">
          Tutup
        </button>
        <button v-show="actionText" @click="saveModal(detailArrOpen.items.length)" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-md ml-2">
          Simpan
        </button>
      </div>
    </div>
  </div>
</div>

@endverbatim