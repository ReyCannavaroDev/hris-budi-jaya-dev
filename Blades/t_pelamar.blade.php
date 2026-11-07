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
          Form Pelamar
        </h1>
        <hr>
      </div>
        <div class="flex flex-row justify-center">
            <input type="file" ref="file" style="display: none" @input="(v)=>{

              $log(v)
              uploadFile(v)
            }">
        </div>
        <div class="flex flex-row justify-center space-x-[20px] mt-[5em]">
          <button @click="downloadTemplate" class="bg-[#44d5ef] hover:bg-[#44c7ef] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Download Template Upload
          </button>
          <button v-show="actionText" @click="$refs.file.click()" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Upload Data Pelamar
          </button>
        </div>
        
        <div class="mt-4 overflow-x-auto">
          <table class="w-[200%] overflow-x-auto table-auto border border-[#CACACA]">
            <thead>
              <tr class="border">
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[10%] border bg-[#f8f8f8] border-[#CACACA]">Nama Pelamar</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">No. KTP</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">Tanggal</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">Ref</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">Telepon</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">Jenis Kelamin</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">Tanggal Lahir</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">Salary</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">Deskripsi</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">Jenis Tes</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[8%] border bg-[#f8f8f8] border-[#CACACA]">Nilai Tes</td>
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
                    :bind="{ readonly:true}" class="!mt-0"
                    :value="item.nama_pelamar" @input="v=>item.nama_pelamar=v"
                    type="textarea"
                    label=""
                    :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX
                    :bind="{ readonly:true}" class="!mt-0"
                    :value="item.ktp_no" @input="v=>item.ktp_no=v"
                    type="text"
                    label=""
                    :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX :bind="{ readonly:true }" class="!mt-0"
                    :value="item.tanggal" @input="v=>item.tanggal=v" type="date" label="" :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX
                    :bind="{ readonly:true}" class="!mt-0"
                    :value="item.ref" @input="v=>item.ref=v"
                    type="text"
                    label=""
                    :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX
                    :bind="{ readonly:true}" class="!mt-0"
                    :value="item.telp" @input="v=>item.telp=v"
                    type="text"
                    label=""
                    :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX
                    :bind="{ readonly:true}" class="!mt-0"
                    :value="item.jk" @input="v=>item.jk=v"
                    type="text"
                    label=""
                    :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX :bind="{ readonly:true }" class="!mt-0"
                    :value="item.tgl_lahir" @input="v=>item.tgl_lahir=v" type="date" label="" :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldNumber
                    :bind="{ readonly:true}" class="!mt-0"
                    :value="item.salary" @input="v=>item.salary=v"
                    type="number"
                    label=""
                    :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX
                    :bind="{ readonly: true}" class="!mt-0"
                    :value="item.deskripsi" @input="v=>item.deskripsi=v"
                    type="text"
                    label=""
                    :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX
                    :bind="{ readonly: true}" class="!mt-0"
                    :value="item.jenis_tes" @input="v=>item.jenis_tes=v"
                    type="text"
                    label=""
                    :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX
                    :bind="{ readonly: true}" class="!mt-0"
                    :value="item.nilai_tes" @input="v=>item.nilai_tes=v"
                    type="text"
                    label=""
                    :check="false"
                  />
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
@endverbatim
@endif