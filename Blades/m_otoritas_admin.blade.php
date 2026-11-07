@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'" :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
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
      <div class="flex flex-col items-start mb-2 pb-4">
        <h1 class="text-[24px] mb-[10px] font-bold">
          {{actionText}} Otoritas Admin
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Nama Peran<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.nama" :errorText="formErrors.nama?'failed':''"
              @input="v=>values.nama=v" :hints="formErrors.nama" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Deskripsi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="textarea" class="col-span-12 !mt-0 w-full"
              :value="values.deskripsi" :errorText="formErrors.deskripsi?'failed':''"
              @input="v=>values.deskripsi=v" :hints="formErrors.deskripsi" :check="false"
            />
          </div>
        </div>
      </div>
      <div class="col-span-8 md:col-span-12">
        <div>
          <div class="mt-4">
            <TableStatic
              customClass="h-50vh"
              ref="detail" 
              :value="detailArr"
              :columns="[{
                  headerName: 'No',
                  cellRenderer: !actionText?null:'ButtonGrid',
                  valueGetter:p=>p.node.rowIndex + 1,
                  cellRendererParams: !actionText?null:{
                    showValue: true,
                    icon: 'times',
                    class: 'btn-text-danger',
                    click:(app)=>{
                      if (app && app.params) {
                        const row = app.params.node.data
                        swal.fire({
                          icon: 'warning', showDenyButton: true,
                          text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
                        }).then((res) => {
                          if (res.isConfirmed) {
                            app.params.api.applyTransaction({ remove: [app.params.node.data] })
                          }
                        })
                      }
                    }
                  },
                  width: 60,
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                flex: 1,
                headerName: 'Tipe Form',
                field: 'tipe',
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              {
                flex: 1,
                headerName: 'Nama Form',
                field: 'nama',
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              {
                flex: 1,
                headerName: 'Alamat Form',
                field: 'alamat',
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              ]"
              >
              <template #header>
                <button @click="openModal(true)" type="button" class="mr-[10px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="plus" /> <span>Add to List</span>
                </button>
              </template>
            </TableStatic>
            
            <!-- <table class="w-full overflow-x-auto table-auto">
              <thead>
                <tr class="border-y">
                  <td class="text-blue-500 font-bold text-capitalize px-2 py-2 text-center w-[10%]">No.</td>
                  <td class="text-blue-500 font-bold text-capitalize px-2 text-center w-[22.5%]">Tipe Form</td>
                  <td class="text-blue-500 font-bold text-capitalize px-2 text-center w-[22.5%]">Nama Form</td>
                  <td class="text-blue-500 font-bold text-capitalize px-2 text-center w-[22.5%]">Alamat Form</td>
                  <td class="text-blue-500 font-bold text-capitalize px-2 text-center w-[22.5%]">Action</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t">
                  <td class="p-4 text-center">
                    {{ i + 1 }}.
                  </td>
                  <td class="p-4">
                    <FieldSelect
                      :bind="{ disabled: !actionText, clearable:false }" class="!mt-0 w-full uppercase"
                      :value="item.tipe" @input="v=>item.tipe=v"
                      valueField="key" displayField="key"
                      :options="['INFO', 'MASTER', 'REPORT', 'TRANSACTION']" :check="false"
                    />
                  </td>
                  <td class="p-4">
                    <FieldSelect
                      :bind="{ disabled: !actionText, clearable:false }" class=" !mt-0 w-full"
                      :value="item.nama" @input="v=>item.nama=v"
                      valueField="key" displayField="key"
                      :options="['NOTIFY BIRTHDAY', 'NOTIFY MAX EMPLOYEE', 'NOTIFY REQUEST SPPD', 'NOTIFY EXP CONTRACT']" :check="false"
                    />
                  </td>
                  <td class="p-4">
                    <FieldX :bind="{ readonly: true }" class=" col-span-8 !mt-0 w-full"
                      :value="item.alamat" @input="v=>values.alamat=v" :check="false"
                    />
                    
                  </td>
                  <td class="p-4">
                    <div class="flex justify-center">
                      <button type="button" @click="removeDetail(item)">
                      <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                      </svg>
                    </button>
                    </div>

                  </td>
                </tr>
              </tbody>
            </table> -->

          </div>
  <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50" id="modal">
    <!-- Modal Overlay (background) -->
    <div class="fixed inset-0 bg-black opacity-50" id="modal"></div>

    <!-- Modal Content -->
      <div class="bg-white w-[70%] rounded shadow-lg z-10">
        <div class="flex justify-between items-center px-[30px] py-[27px] border-b">
          <h2 class="text-2xl font-semibold">Form List</h2>
            <icon fa="remove" class="cursor-pointer text-[30px] font-normal text-[#8F8F8F]" @click="openModal(false)" />
        </div>
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[20px] px-[30px] py-[27px]">
        <div class="col-span-8 md:col-span-6">
          <label>Tipe Form</label>
          <FieldSelect
          class="mt-2"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="valuesEdit.tipe" @input="v=>valuesEdit.tipe=v"
            valueField="key" displayField="key":check="false"
            :options="['Info']"
          />
        </div>
        <div class="col-span-8 md:col-span-6">
          <label>Nama Form</label>
          <FieldX
            :bind="{disabled: !actionText}"
            class="mt-2" 
            @input="(v)=>valuesEdit.nama=v"
            :value="valuesEdit.nama"
            :check="false"  />
        </div>
        <div class="col-span-8 md:col-span-6">
          <label>Alamat Form</label>
          <FieldX
            :bind="{readonly: !actionText}"
            class="mt-2" 
            @input="(v)=>valuesEdit.alamat=v"
            :value="valuesEdit.alamat"
            :check="false"  />
        </div>
      </div>
        <div class="float-right px-[30px] pb-[27px]">
          <button @click="addOtoritas" type="button" class=" bg-blue-600 hover:bg-blue-500 text-white py-4 px-4 flex items-center justify-center space-x-2 rounded">
            <icon fa="plus" /> <span>Add to List</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
        <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
        Kembali
      </button>
      <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
        Simpan
      </button>
  </div>
      <!-- FORM END -->
</div>
@endverbatim
@endif