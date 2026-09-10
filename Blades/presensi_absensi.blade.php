@if(!$req->has('id'))
@verbatim
<div class="bg-white p-6 rounded-md">
  <div class="flex grid-cols-3 gap-3">
    <FieldX :bind="{ readonly: openDateSelected ? true : false , required: true}" 
      class="w-full py-2 !mt-0"
      :value="headerValues.month" 
      :check="false" 
      type="month" 
      label="Filter" placeholder="Periode" 
      @input="(v)=>{
        dayPerWeek(v)
        headerValues.month = v
        loadData()
      }" />
    <FieldSelect class="w-full py-2 !mt-0" 
      :bind="{ disabled: false, clearable:true }" 
      :value="headerValues.weeks"
      :check="false" 
      @input="(v)=>{
        $log(v)
        headerValues.weeks = v
        loadData()
      }" 
      displayField="text"
      valueField="value" 
      :options="weeksOptions"
      placeholder="Periode Tgl"
      fa-icon="calendar" 
      :check="true" />
    <FieldSelect 
      class="w-full py-2 !mt-0" 
      :bind="{ disabled: false, clearable:true }" 
      :value="headerValues.divisi_id"
      :check="false" 
      @input="(v)=>{
        headerValues.divisi_id = v
        loadData()
      }" 
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
      fa-icon="search" :check="true" placeholder="Divisi"/>
    <FieldSelect class="w-full py-2 !mt-0" 
      :bind="{ disabled: false, clearable:true }" 
      :value="headerValues.dept_id"
      :check="false" 
      @input="(v)=>{
        headerValues.dept_id = v
        loadData()
      }" 
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
            divisi_id: headerValues.divisi_id ?? null,
            transform:false,
          }
      }" 
      placeholder="Departemen"
      fa-icon="search" 
      :check="true" />
  </div>
  <h2 v-if="loaderData" class="text-center"><span class="italic">Harap tunggu, kami sedang menyiapkan data untuk anda..</span> &#128522;</h2>
  <button  v-if="openDateSelected" @click="onBackReal" class="bg-blue-500 mb-2 text-white hover:bg-blue-600 rounded-[6px] py-2 px-[12.5px]">Kembail ke list</button>
  <h3 v-if="openDateSelected" class="font-semibold">Tanggal : {{openDateSelected}}</h3>
  <table class="table-auto w-full" v-if="openDateSelected && dataByDateDetail.length">
    <thead class="bg-blue-600 text-white ">
      <tr>
        <th class="border-1 border-gray-500 px-3 py-2 w-[5%]">No</th>
        <th class="border-1 border-gray-500 px-3 py-2 w-[10%]">NIK</th>
        <th class="border-1 border-gray-500 px-3 py-2 w-[25%]">Nama</th>
        <th class="border-1 border-gray-500 px-3 py-2 w-[27%]">Departemen</th>
        <th class="border-1 border-gray-500 px-3 py-2 w-[10%]">Status</th>
        <th class="border-1 border-gray-500 px-3 py-2 w-[12%]">Waktu Checkin</th>
        <th class="border-1 border-gray-500 px-3 py-2 w-[12%]">Waktu Checkout</th>
        <th class="border-1 border-gray-500 px-3 py-2 w-[50px]">Aksi</th>
      </tr>
    </thead>
    <tbody class="bg-blue-100" v-if="openDateSelected && dataByDateDetail.length">
      <tr v-for="item,idx in dataByDateDetail" :key="idx" :class="item?.absensi?.status == 'NOT ATTEND' ? 'bg-red-200' : ''" class="hover:bg-amber-200">
        <!-- {{$log(item)}} -->
        <td class="text-left border-1 border-gray-500 px-3">{{idx+1}}</td>
        <td class="text-left border-1 border-gray-500 px-3">{{item.kode}}</td>
        <td class="text-left border-1 border-gray-500 px-3">{{item.nama_lengkap}}</td>
        <td class="text-left border-1 border-gray-500 px-3">{{item.dept}}</td>
        <td class="text-left border-1 border-gray-500 px-3">{{item.absensi?.status}}</td>
        <td class="text-left border-1 border-gray-500 px-3 cursor-pointer"@click="openDateDetail(true,item.absensi,'checkin',item)">
          <div class="flex items-center">
            <icon v-show="item.absensi?.checkin_time" class="mx-1" :class="item.absensi?.checkin_on_scope?'text-green-600':'text-red-600'" fa="map-marker-alt"/>{{item.absensi?.checkin_time}}
          </div>
        </td>
        <td class="text-left border-1 border-gray-500 px-3 cursor-pointer"@click="openDateDetail(true,item.absensi,'checkout',item)">
          <div class="flex items-center">
            <icon v-show="item.absensi?.checkout_time" class="mx-1" :class="item.absensi?.checkout_on_scope?'text-green-600':'text-red-600'" fa="map-marker-alt"/>{{item.absensi?.checkout_time}}
          </div>
          </td>
        <td class="text-center border-1 border-gray-500 px-3">
          <RouterLink v-if="item.absensi?.status.toLowerCase() === 'not attend'" :to="$route.path+'/create?ts='+openDateSelected+`&user_id=${item.default_user_id}`" class="text-gray-600">
            <icon fa="plus" />
          </RouterLink>
          <RouterLink v-else :to="$route.path+`/${item.absensi?.presensi_absensi_id}?action=Edit&ts=`+item.absensi?.tanggal" class="text-gray-600">
            <icon fa="edit" />
          </RouterLink>
          <!-- <Icon fa="file" class="hover:text-yellow-500 cursor-pointer"/>
          <Icon fa="file" class="hover:text-yellow-500 cursor-pointer"/> -->
        </td>
      </tr>
    </tbody>
  </table>
  <div v-else>
    <table v-if="!loaderData" class="table-auto w-full">
      <thead class="bg-blue-600 text-white ">
        <tr>
          <th class="border-1 border-gray-500 px-3 py-2">Hari</th>
          <th class="border-1 border-gray-500 px-3 py-2">Tanggal</th>
          <th class="border-1 border-gray-500 px-3 py-2">Hadir</th>
          <th class="border-1 border-gray-500 px-3 py-2">Izin/Sakit/Cuti</th>
          <th class="border-1 border-gray-500 px-3 py-2">Tidak Hadir</th>
          <th class="border-1 border-gray-500 px-3 py-2">Karyawan Aktif</th>
          <th class="border-1 border-gray-500 px-3 py-2">Presentase</th>
          <th class="border-1 border-gray-500 px-3 py-2">Aksi</th>
        </tr>
      </thead>
      <tbody class="bg-blue-100">
        <tr v-for="item in dataByDate" class="hover:bg-amber-200" 
          :class="item.type == 'Hari Libur' ? 'bg-gray-500 text-white' : (item.type == 'Cuti Bersama' ? 'bg-red-400 text-white' : '')"
          :title="item.type"
          >
          <td class="text-left border-1 border-gray-500 px-3">{{item.day_name_idn}}</td>
          <td class="text-left border-1 border-gray-500 px-3">{{item.all_days_of_month}}</td>
          <td class="text-right border-1 border-gray-500 px-3">{{item.attend}}</td>
          <td class="text-right border-1 border-gray-500 px-3">{{item.cuti}}</td>
          <td class="text-right border-1 border-gray-500 px-3">{{item.alpha}}</td>
          <td class="text-right border-1 border-gray-500 px-3">{{item.total_kary}}</td>
          <td class="text-right border-1 border-gray-500 px-3">{{Math.round(item.presentase)}}%</td>
          <td class="text-center border-1 border-gray-500 px-3">
            <Icon @click="openDate(item.all_days_of_month)" fa="search" class="hover:text-yellow-500 cursor-pointer"/>
          </td>
        </tr>
      </tbody>
    </table>
    <br>
    <i class="text-red-500 font-semibold">*Karena proses data berat, sistem menerapkan filter tanggal per 3 hari</i><br>
    <i class="text-red-500 ">Estimasi proses data ±30 detik</i><br>
    <!-- <i class="text-yellow-600 ">Prosesing : {{(processLoad) || 0}}ms</i> -->
  </div>
</div>

<div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50" id="modal">
    <!-- Modal Overlay (background) -->
    <div class="fixed inset-0 bg-black opacity-50" id="modal"></div>

    <!-- Modal Content -->
    <div class="bg-white w-[50%] rounded shadow-lg z-10 overflow-auto max-h-[70%]">
        <div class="flex justify-between items-center px-[30px] py-[27px] border-b">
          <h2 class="text-2xl font-semibold">Detail {{isCheckin ? 'Checkin' : 'Checkout'}}</h2>
            <icon fa="remove" class="cursor-pointer text-[30px] font-normal text-[#8F8F8F]" @click="showModal=false" />
        </div>
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[20px] px-[30px] py-[27px]">
        <div class="col-span-8 md:col-span-3">
          <img v-if="isCheckin ? dataDetail.checkin_foto : dataDetail.checkout_foto" :src="`${isCheckin ? dataDetail.checkin_foto : dataDetail.checkout_foto}`"class="!mt-2 w-[166px]">
          <div v-else class="h-[166px] bg-gray-500 w-[166px] rounded-[10px]"></div>
        </div>
        <div class="col-span-8 md:col-span-9 mt-2">
          <table>
            <tr class="font-semibold">
              <td class="w-[30%] py-1 px-2 ">NIK </td>
              <td class="align-top py-1 px-1">:</td>
              <td class=" py-1 px-1">{{dataDetail.kode}}</td>
            </tr>
            <tr class="font-semibold">
              <td class="w-[30%] py-1 px-2">Nama </td>
              <td class="align-top py-1 px-1">:</td>
              <td class=" py-1 px-1">{{dataDetail.nama_lengkap}}</td>
            </tr>
            <tr class="font-semibold">
              <td class="w-[30%] py-1 px-2">Departemen </td>
              <td class="align-top py-1 px-1">:</td>
              <td class=" py-1 px-1">{{dataDetail.dept}}</td>
            </tr>
            <tr>
              <td class="w-[30%] py-1 px-2">Waktu {{isCheckin ? 'Checkin' : 'Checkout'}}</td>
              <td class="align-top py-1 px-1">:</td>
              <td class=" py-1 px-1">{{isCheckin ? dataDetail.checkin_time :dataDetail.checkout_time}}</td>
            </tr>
            <tr>
              <td class="w-[30%] py-1 px-2">Kantor {{isCheckin ? 'Checkin' : 'Checkout'}}</td>
              <td class="align-top py-1 px-1">:</td>
              <td class=" py-1 px-1">{{isCheckin ? dataDetail.checkin_region : dataDetail.checkout_region}}</td>
            </tr>
            <tr>
              <td class="w-[30%] py-1 px-2">Lokasi {{isCheckin ? 'Checkin' : 'Checkout'}}</td>
              <td class="align-top py-1 px-1">:</td>
              <td class=" py-1 px-1">{{isCheckin ? dataDetail.checkin_address : dataDetail.checkout_address}}</td>
            </tr>
            <tr>
              <td class="w-[30%] py-1 px-2">Catatan {{isCheckin ? 'Checkin' : 'Checkout'}}</td>
              <td class="align-top py-1 px-1">:</td>
              <td class=" py-1 px-1">{{isCheckin ? (dataDetail.catatan_in ? dataDetail.catatan_in : '-') : (dataDetail.catatan_out ? dataDetail.catatan_out : '-')}}</td>
            </tr>
            <tr>
              <td class="w-[30%] py-1 px-2">On Scope</td>
              <td class="align-top py-1 px-1">:</td>
              <td class="px-1 py-1" v-if="isCheckin">
                <icon :fa="dataDetail.checkin_on_scope?'check':'times'" :class="dataDetail.checkin_on_scope?'text-green-600':'text-red-600'"/>
              </td>
              <td v-else class="px-1 py-1">
                <icon :fa="dataDetail.checkout_on_scope?'check':'times'" :class="dataDetail.checkout_on_scope?'text-green-600':'text-red-600'"/>
              </td>
            </tr>
          </table>
        </div>
      </div>
      </div>
    </div>
  </div>
@endverbatim
@else

@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Form presensi Absensi
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-x-[80px] gap-y-2">
        <!-- START COLUMN -->
        <div>
          <label class="font-semibold">Karyawan</span></label>
          <FieldPopup class="w-full py-2 !mt-0" :bind="{ readonly: true }" :value="values.default_user_id"
            @input="(v)=>values.default_user_id=v" :errorText="formErrors.default_user_id?'failed':''"
            :hints="formErrors.default_user_id" label="" valueField="id" displayField="name" :api="{
                url: `${store.server.url_backend}/operation/default_users`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  join: true,
                  searchfield: 'this.name, m_dir.nama, m_kary.no_tlp'
                }
              }" placeholder="Pilih Karyawan" :check="false" :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'name',
              headerName:  'Nama',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'm_dir.nama',
              headerName:  'Direktorat',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'm_kary.no_tlp',
              headerName:  'No Telp',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            ]" />

          <!-- <FieldX 
            :bind="{ readonly: true }" 
            label="" 
            class="w-full py-2 !mt-0"
            :value="values.creator_name"
            :errorText="formErrors.creator ? 'failed' : ''"
            @input="v=>values.creator=v" 
            :hints="formErrors.creator" 
            :check="false"
            label=""
            placeholder="Karyawan"
          /> -->

        </div>
        <div>
          <label class="font-semibold">Tanggal</span></label>
          <FieldX :bind="{ readonly: true, disabled: true }" label="" class="w-full py-2 !mt-0"
            :value="values.tanggal" type="date" :errorText="formErrors.tanggal ? 'failed' : ''"
            @input="v=>values.tanggal=v" :hints="formErrors.tanggal" :check="false" label="" placeholder="Tanggal" />
        </div>
        <div>
          <label class="font-semibold">Status</span></label>
          <FieldX :bind="{ readonly: true }" label="" class="w-full py-2 !mt-0" :value="values.status"
            :errorText="formErrors.status ? 'failed' : ''" @input="v=>values.status=v" :hints="formErrors.status"
            :check="false" label="" placeholder="Status" />
        </div>
        <div></div>


        <!-- Check IN -->
      <div class="space-y-4">
        <div class="mt-2 sm:mt-6">
          <p class="font-bold text-[18px]">Check In</p>
        </div>
        <div class="">
          <label class="font-semibold">Waktu Check In</span></label>
          <FieldX :bind="{ readonly: !actionText }" type="time" label="" class="w-full py-2 !mt-0"
            :value="values.checkin_time" :errorText="formErrors.checkin_time ? 'failed' : ''"
            @input="v=>values.checkin_time=v" :hints="formErrors.checkin_time" :check="false" label=""
            placeholder="Waktu Check In" />
        </div>

        <div>
          <label class="font-semibold">Foto Check In</span></label>
          <div class="rounded-md py-2 h-[370px]">
            <img v-if="!isCheckin && values.checkin_foto" :src="values.checkin_foto" alt="Check In Photo" class="w-[200px] h-[100%] rounded-md object-center">
            <div v-else class="h-full bg-gray-500 w-[200px] rounded-[10px]"></div>
          </div>
        </div>
        <div v-show="isCreateEdit">
          <label class="font-semibold">Lokasi Check In</span></label>
          <FieldSelect class="w-full py-2 !mt-0" :bind="{ disabled: !actionText, clearable:false }"
            :value="values.checkin_region" @input="v=>values.checkin_region=v" :errorText="formErrors.checkin_region?'failed':''"
            :hints="formErrors.checkin_region" @update:valueFull="(objVal)=>{
              $log(objVal)
              values.geo_checkin = `POINT(${objVal.long} ${objVal.lat})`
              values.checkin_long = objVal.long
              values.checkin_lat = objVal.lat
            }" label="" valueField="nama" displayField="nama" :api="{
                url: `${store.server.url_backend}/operation/presensi_lokasi`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  selectfield: 'this.id, this.nama, this.lat, this.long'
                }
              }" placeholder="Pilih Master Lokasi" :check="false" />

        </div>
        <div v-show="isCreateEdit">
          <label class="font-semibold">Pin Titik Lokasi Check In</span></label>
          <FieldGeo class="w-full py-2 !mt-0" :bind="{ readonly: !actionText }" @input="(v)=>{
            values.geo_checkin=v
            values.test = values.geo_checkin?.match(/\(([^)]+)\)/)[1].split(' ')
            if(values.test){
              values.checkin_long = values.test[0]
              values.checkin_lat = values.test[1]
            }
          }" :center="[-7.3244677, 112.7550714]" :errorText="formErrors.geo_checkin?'failed':''"
            :hints="formErrors.geo_checkin" geostring="POINT(112.7550714 -7.3244677)" :value="values.geo_checkin"
            placeholder="Pilih Titik Lokasi" fa-icon="map-marker-alt" :check="false" />
        </div>
        <div>
          <label class="font-semibold">Latitude Check In</span></label>
          <FieldX :bind="{ readonly: true }" label="" class="w-full py-2 !mt-0" :value="values.checkin_lat"
            :errorText="formErrors.checkin_lat ? 'failed' : ''" @input="v=>values.checkin_lat=v"
            :hints="formErrors.checkin_lat" :check="false" label="" placeholder="Latitude Check In" />
        </div>
        <div>
          <label class="font-semibold">Longtitude Check In</span></label>
          <FieldX :bind="{ readonly: true }" label="" class="w-full py-2 !mt-0" :value="values.checkin_long"
            :errorText="formErrors.checkin_long ? 'failed' : ''" @input="v=>values.checkin_long=v"
            :hints="formErrors.checkin_long" :check="false" label="" placeholder="Longtitude Check In" />
        </div>
        <div>
          <label class="font-semibold">Alamat Check In</span></label>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" label="" class="w-full py-2 !mt-0" :value="values.checkin_address"
            :errorText="formErrors.checkin_address ? 'failed' : ''" @input="v=>values.checkin_address=v"
            :hints="formErrors.checkin_address" :check="false" label="" placeholder="Alamat Check In" />
        </div>

        <div>
          <label class="font-semibold">Catatan Check In<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" label="" class="w-full py-2 !mt-0" :value="values.catatan_in"
            :errorText="formErrors.catatan_in ? 'failed' : ''" @input="v=>values.catatan_in=v"
            :hints="formErrors.catatan_in" :check="false" label="" placeholder="Catatan Check In" />
        </div>
        <div class="flex flex-col">
          <label class="font-semibold">On Scope<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <div class="flex py-2 gap-2">
            <input :disabled="!actionText" type="radio" :value="true" v-model="values.checkin_on_scope" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
            <label for="ya">Ya</label>
            <div class="w-10 " />
            <input :disabled="!actionText" type="radio" v-model="values.checkin_on_scope" :value="false" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
            <label for="tidak">Tidak</label>
          </div>
        </div>


        <!-- <div>
          <label class="font-semibold">Lokasi Check In</span></label>
          <FieldX :bind="{ readonly: true }" label="" class="w-full py-2 !mt-0" :value="values.checkin_region"
            :errorText="formErrors.checkin_region ? 'failed' : ''" @input="v=>values.checkin_region=v"
            :hints="formErrors.checkin_region" :check="false" label="" placeholder="Lokasi Check In" />
        </div>-->
      </div> 
      <div class="space-y-4">

        <!-- Check Out -->

        <div class="mt-2 sm:mt-6">
          <p class="font-bold text-[18px]">Check Out</p>
        </div>

        <div class="">
          <label class="font-semibold">Waktu Check Out<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: !actionText }" type="time" label="" class="w-full py-2 !mt-0"
            :value="values.checkout_time" :errorText="formErrors.checkout_time ? 'failed' : ''"
            @input="v=>values.checkout_time=v" :hints="formErrors.checkout_time" :check="false" label=""
            placeholder="Waktu Check Out" />
        </div>

        <div>
          <label class="font-semibold">Foto Check Out<span class="text-red-500 space-x-0 pl-0"></span></label>
          <div class="h-[370px]">
            <img v-if="!isCheckout && values.checkout_foto" :src="values.checkout_foto" alt="Check In Photo" class="w-[200px] h-full object-center rounded-md py-2" :class="{'w-[200px]': isCheckout}">
            <div v-else class="h-full bg-gray-500 w-[200px] rounded-[10px]"></div>
          </div>
        </div>

        <div v-show="isCreateEdit">
          <label class="font-semibold">Lokasi Check Out</span></label>
          <FieldSelect class="w-full py-2 !mt-0" :bind="{ disabled: !actionText, clearable:false }"
            :value="values.checkout_region" @input="v=>values.checkout_region=v"
            :errorText="formErrors.checkout_region?'failed':''" :hints="formErrors.checkout_region" @update:valueFull="(objVal)=>{
              $log(objVal)
              values.geo_checkout = `POINT(${objVal.long} ${objVal.lat})`
              values.checkout_long = objVal.long
              values.checkout_lat = objVal.lat
            }" label="" valueField="nama" displayField="nama" :api="{
                url: `${store.server.url_backend}/operation/presensi_lokasi`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  selectfield: 'this.id, this.nama, this.lat, this.long',
                }
              }" placeholder="Pilih Master Lokasi" :check="false" />

        </div>
        <div v-show="isCreateEdit">
          <label class="font-semibold">Pin Titik Lokasi Check Out</span></label>
          <FieldGeo class="w-full py-2 !mt-0" :bind="{ readonly: !actionText }" @input="(v)=>{
            values.geo_checkout=v
            values.test_out = values.geo_checkout?.match(/\(([^)]+)\)/)[1].split(' ')
            if(values.test_out){
              values.checkout_long = values.test_out[0]
              values.checkout_lat = values.test_out[1]
            }
          }" :center="[-7.3244677, 112.7550714]" :errorText="formErrors.geo_checkout?'failed':''"
            :hints="formErrors.geo_checkout" geostring="POINT(112.7550714 -7.3244677)" :value="values.geo_checkout"
            placeholder="Pilih Titik Lokasi" fa-icon="map-marker-alt" :check="false" />
        </div>

        <div>
          <label class="font-semibold">Latitude Check Out<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" label="" class="w-full py-2 !mt-0" :value="values.checkout_lat"
            :errorText="formErrors.checkout_lat ? 'failed' : ''" @input="v=>values.checkout_lat=v"
            :hints="formErrors.checkout_lat" :check="false" label="" placeholder="Foto Check Out" />
        </div>

        <div>
          <label class="font-semibold">Longtitude Check Out<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" label="" class="w-full py-2 !mt-0" :value="values.checkout_long"
            :errorText="formErrors.checkout_long ? 'failed' : ''" @input="v=>values.checkout_long=v"
            :hints="formErrors.checkout_long" :check="false" label="" placeholder="Longtitude Check Out" />
        </div>

        <div>
          <label class="font-semibold">Alamat Check Out<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" label="" class="w-full py-2 !mt-0" :value="values.checkout_address"
            :errorText="formErrors.checkout_address ? 'failed' : ''" @input="v=>values.checkout_address=v"
            :hints="formErrors.checkout_address" :check="false" label="" placeholder="Alamat Check Out" />
        </div>
      
      <div>
          <label class="font-semibold">Catatan Check Out<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" label="" class="w-full py-2 !mt-0" :value="values.catatan_out"
            :errorText="formErrors.catatan_out ? 'failed' : ''" @input="v=>values.catatan_out=v"
            :hints="formErrors.catatan_out" :check="false" label="" placeholder="Catatan Check Out" />
        </div>
        <div class="flex flex-col">
          <label class="font-semibold">On Scope<span class="text-red-500 space-x-0 pl-0"></span></label>
          <div class="flex py-2 gap-2">
            <input :disabled="!actionText" type="radio" v-model="values.checkout_on_scope" :value="true" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
            <label for="ya">Ya</label>
            <div class="w-10 " />
            <input :disabled="!actionText" type="radio" v-model="values.checkout_on_scope"  :value="false" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
            <label for="tidak">Tidak</label>
          </div>
        </div>

        <!-- <div>
          <label class="font-semibold">Lokasi Check Out<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" label="" class="w-full py-2 !mt-0" :value="values.checkout_region"
            :errorText="formErrors.checkout_region ? 'failed' : ''" @input="v=>values.checkout_region=v"
            :hints="formErrors.checkout_region" :check="false" label="" placeholder="Lokasi Check Out" />
        </div> -->




        <!-- END COLUMN -->
      </div>
      </div>
      
      <!-- ACTION BUTTON START -->
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
        <!-- <button @click="onPost" class="bg-orange-500 hover:bg-orange-600 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Mengajukan Persetujuan
          </button> -->
        <button @click="onBack" class="bg-gray-400 hover:bg-gray-500 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Kembali
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