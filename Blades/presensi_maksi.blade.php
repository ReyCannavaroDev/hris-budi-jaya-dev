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
         Form Transaksi Makan Siang
        </h1>
      </div>
      <!-- HEADER END -->
      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Master Menu Makan Siang<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldSelect class="!mt-0 w-full col-span-12"
              :bind="{ disabled: !actionText }"
              :value="values.presensi_m_menu_maksi_id" @input="v=>values.presensi_m_menu_maksi_id=v"
              :errorText="formErrors.presensi_m_menu_maksi_id?'failed':''" 
              :hints="formErrors.presensi_m_menu_maksi_id" label=""
              @update:valueFull="(v)=>{
                getDetail(v.id)
              }"
              @input="getDetail()"
              valueField="id" displayField="judul"
              :api="{
                  url: `${store.server.url_backend}/operation/presensi_m_menu_maksi`,
                  headers: { 'Content-Type': 'Application/json', 
                  Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    join:true,
                  }
              }"
              placeholder="Pilih Master" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tanggal<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="date"
              :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''"
              @input="v=>values.tanggal=v" :hints="formErrors.tanggal" 
              :check="false"
              class="col-span-12 !mt-0 w-full"
            />    
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Keterangan<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: !actionText }" type="textarea"
              :value="values.keterangan" :errorText="formErrors.keterangan?'failed':''"
              @input="v=>values.keterangan=v" :hints="formErrors.keterangan" 
              :check="false"
              class="col-span-12 !mt-0 w-full"
              placeholder="Tuliskan Keterangan" label=""
            />    
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
            <button :disabled="!actionText" @click="addDetail" type="button" class="bg-[#005FBF] hover:bg-[#0055ab] text-white py-[7px] px-[10.5px] text-[12px] flex items-center justify-center space-x-2 rounded">
              <icon fa="plus" /> <span>Add to List</span></button>
        <div class="mt-4">
          <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
            <thead>
              <tr class="border">
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[40%] border-[#CACACA]">Tipe Lauk</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Lauk</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[5%] bg-[#f8f8f8] border-[#CACACA]">Aksi</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, i) in detailArr" :key="item.__id" class="border-t" v-if="detailArr.length">
                {{$log('asu')}}
                <td class="p-2 text-center border border-[#CACACA]">
                  {{ i + 1 }}.
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldSelect class="!mt-0 w-full"
                    :bind="{ disabled: !actionText, clearable:false }"
                    :value="item.tipe_lauk_id"
                    @update:valueFull="(v)=>{
                      item['tipe_lauk.value_2']=v.value_2
                    }"
                    @input="v=>item.tipe_lauk_id=v"
                    :errorText="formErrors.tipe_lauk_id?'failed':''" 
                    :hints="formErrors.tipe_lauk_id" label=""
                    valueField="id" displayField="value"
                    :api="{
                        url: `${store.server.url_backend}/operation/m_general`,
                        headers: { 'Content-Type': 'Application/json', 
                        Authorization: `${store.user.token_type} ${store.user.token}`},
                        params: {
                          join:true,
                          where: `this.group = 'TIPE LAUK'`,
                          selectfield: 'this.id,this.value,this.value_2'
                        }
                    }"
                    placeholder="Pilih Tipe" :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX :bind="{ readonly: !actionText }" class="!mt-0 w-full"
                    :value="item.lauk" @input="v=>item.lauk=v"
                    :errorText="formErrors.lauk?'failed':''" 
                    :hints="formErrors.lauk" label="" placeholder="Tuliskan Lauk" :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <div class="flex justify-center">
                    <button type="button" @click="removeDetail(item)" :disabled="!actionText">
                    <svg width="10" height="15" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                    </svg>
                  </button>
                  </div>

                </td>
              </tr>
              <tr v-else class="text-center">
                {{$log('jb')}}
                <td colspan="7" class="py-[20px]">
                  No data to show
                </td>
              </tr>
            </tbody>
          </table>
      </div>
    </div>
      <!--BUTTON-->
      </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
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