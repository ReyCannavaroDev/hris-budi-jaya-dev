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
          Form Tarif Borongan
        </h1>
        <hr>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="font-semibold">Group<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldSelect
             class="w-full py-2 !mt-0"
            label=""
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.m_tarif_group_id" @input="v=>values.m_tarif_group_id=v"
            :errorText="formErrors.m_tarif_group_id?'failed':''" 
            :hints="formErrors.m_tarif_group_id"
            valueField="id" displayField="nama"
            :api="{
                url: `${store.server.url_backend}/operation/m_tarif_group`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  where: 'this.is_active = true'
                }
            }"
            placeholder="Pilih Group" :check="false"
          />
        </div>

        <div>
          <label class="font-semibold">Nama <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.nama"
            :errorText="formErrors.nama?'failed':''" @input="v=>values.nama=v" :hints="formErrors.nama" :check="false"
            label="" placeholder="Masukan Nama" />
        </div>

        <div>
          <label class="font-semibold">Nominal 1<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.nominal" type="number"
            :errorText="formErrors.nominal?'failed':''" @input="v=>values.nominal=v" :hints="formErrors.nominal" :check="false"
            label="" placeholder="Masukan nominal" />
        </div>

        <div>
          <label class="font-semibold">Nominal 2<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldNumber :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.nominal_2" type="number"
            :errorText="formErrors.nominal_2?'failed':''" @input="v=>values.nominal_2=v" :hints="formErrors.nominal_2" :check="false"
            label="" placeholder="Masukan nominal 2" />
        </div>

        <div>
          <label class="font-semibold">Keterangan<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.keterangan"
            :errorText="formErrors.keterangan?'failed':''" @input="v=>values.keterangan=v"
            :hints="formErrors.keterangan" :check="false" type="textarea" label="" placeholder="Tulis Keterangan" />
        </div>
        <div> </div>
        <div class="flex flex-col gap-2">
          <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="is_active_for_click"
            >Status :</label>
          <div class="flex w-40">
            <div class="flex-auto">
              <i class="text-red-500">InActive</i>
            </div>
            <div class="flex-auto">
              <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                :class="{'after:bg-gray-500': values.is_active === false}"
                role="switch"
                id="is_active_for_click"
                :disabled="!actionText"
                v-model="values.is_active"
                />
            </div>
            <div class="flex-auto">
              <i class="text-green-500">Active</i>
            </div>
          </div>
        </div>
      </div>
      <!-- END COLUMN -->
      <!-- <div class="col-span-8 md:col-span-12">
        <h1 class="text-xl font-semibold mb-5"> Detail Tarif </h1>
        <button :disabled="!actionText" @click="addDetail" type="button" class="bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
              <icon fa="plus" /> <span>Add to List</span></button>
        <div class="mx-1 mt-4">
          <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
            <thead>
              <tr class="border">
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">
                  No.</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[20%] border bg-[#f8f8f8] border-[#CACACA]">
                  Nama</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Nominal</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">
                  Nominal 2</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[20%] bg-[#f8f8f8] border-[#CACACA]">
                  Keterangan</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[20%] bg-[#f8f8f8] border-[#CACACA]">
                  Active</td>
                <td
                  class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[5%] bg-[#f8f8f8] border-[#CACACA]">
                  Aksi</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
                <td class="p-2 text-center border border-[#CACACA]">
                  {{ i + 1 }}.
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX :bind="{ readonly: !actionText }" class="!mt-0" :value="item.nama" @input="v=>item.nama=v"
                    :errorText="formErrors.nama?'failed':''" :hints="formErrors.nama" label=""
                    placeholder="Tuliskan Nama" :check="false" />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldNumber class="!mt-0 w-full" :bind="{ readonly: !actionText }" :value="item.nominal"
                    @input="(v)=>item.nominal=v" :errorText="formErrors.nominal?'failed':''" :hints="formErrors.nominal"
                    placeholder="Tuliskan Nominal" label="" :check="false" />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldNumber class="!mt-0 w-full" :bind="{ readonly: !actionText }" :value="item.nominal_2"
                    @input="(v)=>item.nominal_2=v" :errorText="formErrors.nominal_2?'failed':''"
                    :hints="formErrors.nominal_2" placeholder="Tuliskan Nominal 2" label="" :check="false" />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX :bind="{ readonly: !actionText }" class="!mt-0" :value="item.keterangan"
                    @input="v=>item.keterangan=v" :errorText="formErrors.keterangan?'failed':''"
                    :hints="formErrors.keterangan" label="" placeholder="Tuliskan keterangan" :check="false" />
                </td>
                <td class="flex justify-center w-full items-center h-full  p-3">
                  <div class="flex flex-col ">
                    <div class="flex w-40">
                      <div class="flex-auto">
                        <i class="text-red-500">InActive</i>
                      </div>
                      <div class="flex-auto">
                        <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                :class="{'after:bg-gray-500': item.is_active === false}"
                role="switch"
                id="is_active_for_click"
                :disabled="!actionText"
                v-model="item.is_active"
                />
                      </div>
                      <div class="flex-auto">
                        <i class="text-green-500">Active</i>
                      </div>
                    </div>
                  </div>
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
      </div> -->
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