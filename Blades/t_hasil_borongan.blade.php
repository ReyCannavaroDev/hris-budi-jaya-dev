@if(!$req->has('id'))

<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'"
        :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
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
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Form Hasil Borongan
        </h1>
        <hr>
      </div>
      <div class="grid grid-cols-2 gap-3 p-4">

        <div>
          <label class="font-semibold">Tanggal<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.date"
            :errorText="formErrors.date?'failed':''" @input="v=>values.date=v" :hints="formErrors.date" :check="false"
            type="date" label="" placeholder="Masukan Tanggal"
            @input="v=>gantiTanggal(v)"
          />
        </div>

        <div>
          <label class="font-semibold">Pilih Karyawan<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldPopup :bind="{ readonly: !actionText }" class="cw-full py-2 !mt-0" :value="values.m_kary_id"
            @input="(v)=>values.m_kary_id=v" :errorText="formErrors.m_kary_id?'failed':''" :hints="formErrors.m_kary_id"
            @update:valueFull="gantiKaryawan"
            valueField="id" 
            displayField="nama_lengkap" 
            :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.is_active = true`,
                  searchfield: 'this.nik, this.nama_lengkap, m_dept.nama, m_divisi.nama'
                }
              }" placeholder="Pilih karyawan" label="" :check="false" :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'nik',
                wrapText:true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
                headerName: 'Divisi',
                field: 'm_divisi.nama',
                wrapText:true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
                headerName: 'Departemen',
                field: 'm_dept.nama',
                wrapText:true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
                field: 'nama_lengkap',
                wrapText:true,
                headerName: 'Nama Karyawan',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              ]" />
        </div>
      </div>
      <!-- END COLUMN -->
      <div class="col-span-8 md:col-span-12">
        <button :disabled="!actionText" @click="addDetail" type="button" class="bg-[#005FBF] hover:bg-[#0055ab] text-white py-[8px] px-[14px] flex items-center justify-center space-x-2 rounded-md shadow-sm ml-1">
              <icon fa="plus" /> <span>Add to List</span></button>
        <div class="mx-1 mt-4">
          <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
            <thead>
              <tr class="border">
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                  No.</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Nama Item</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border  bg-[#f8f8f8] border-[#CACACA]">
                  Tarif</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border  bg-[#f8f8f8] border-[#CACACA]">
                  Jumlah Item</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border  bg-[#f8f8f8] border-[#CACACA]">
                  Sub Total</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border  bg-[#f8f8f8] border-[#CACACA]">
                  Aksi</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
                <td class="p-2 text-center border border-[#CACACA]">
                  {{ i + 1 }}.
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldPopup :bind="{ readonly: !actionText }" class="cw-full py-2 !mt-0" :value="item.m_tarif_id"
                    @input="(v)=>item.m_tarif_id=v" :errorText="formErrors.m_tarif_id?'failed':''"
                    :hints="formErrors.m_tarif_id" valueField="id" @update:valueFull="v=>{
                      $log(v)
                  item.tarif= v.nominal
                  item.m_tarif_group_id= v.m_tarif_group_id
                  item.tarif_desc= v.keterangan
                   }" displayField="nama" :api="{
                url: `${store.server.url_backend}/operation/m_tarif`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.is_active = true`,
                  searchfield: 'this.nama, this.nominal'
                }
              }" placeholder="Pilih Item" label="" :check="false" :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                headerName : 'Nama',
                field: 'nama',
                wrapText:true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                headerName: 'Nominal',
                field: 'nominal',
                wrapText:true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-end'],
                valueFormatter: (params) => {
                  if (params.value != null) {
                    return params.value.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                  } else {
                    return '';
                  }
                }
              },
              ]" />
                </td>

                <td class="p-2 border border-[#CACACA]">
                  <FieldNumber class="!mt-0 w-full" :bind="{ readonly: !actionText }" :value="item.tarif" @input="(v)=>item.tarif=v" :errorText="formErrors.tarif?'failed':''" :hints="formErrors.tarif" placeholder="Masukan Harga "
                    type="number" label="" :check="false" />
                </td>

                <td class="p-2 border border-[#CACACA]">
                  <FieldNumber class="!mt-0 w-full" :bind="{ readonly: !actionText }" :value="item.qty"
                    @input="(v)=>item.qty=v" :errorText="formErrors.qty?'failed':''" :hints="formErrors.qty"
                    placeholder="Masukan jumlah " type="number" label="" :check="false" />
                </td>

                <td class="p-2 border border-[#CACACA]">
                  <FieldNumber class="!mt-0 w-full" :bind="{ readonly: true }" :value="item.subtotal"
                    @input="(v)=>item.subtotal=v" :errorText="formErrors.subtotal?'failed':''"
                    :hints="formErrors.subtotal" placeholder="Masukan sub_total " type="number" label=""
                    :check="false" />
                </td>

                <td class="p-2 border border-[#CACACA]">
                  <div class="flex justify-center">
                    <button type="button" @click="removeDetail(item)" :disabled="!actionText">
                    <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                    </svg>
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
      </div>

      <div class="mt-5 w-full flex justify-end">
        <span class="text-1xl ">
          <span style="font-family: sans-serif;">Grand Total = Rp. </span><span class="font-semibold">{{ gaji }}</span>
        </span>
      </div>

      <!--END-Table-->
      <div>
        <!-- ACTION BUTTON START -->
        <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Batal
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
        </div>
      </div>
    </div>



  </div>
</div>
</div>
@endverbatim
@endif