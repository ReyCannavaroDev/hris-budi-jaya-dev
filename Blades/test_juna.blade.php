@if(!$req->has('id'))

@verbatim
<div class="bg-white p-6 rounded-xl flex justify-center flex-col">
  <div class="grid grid-cols-2 lg:grid-cols-8 w-full text-sm overflow-x-auto">
          <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 0}"
            @click="activeTabIndex = 0"
          >
            Absen
          </button>
          <button
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === 1}"
            @click="activeTabIndex = 1"
          >
            Daftar Absensi
          </button>
        </div>
  <div v-show="activeTabIndex === 0">
    <h1 class="font-semibold text-xl mt-8 text-center">{{form.attending?.toLowerCase() === 'not attend' ? 'Presensi Checkin' : (form.attending?.toLowerCase() === 'working' ? 'Presensi Checkout' : 'Sudah Absen')}} </h1>
      <div class="mt-4 lg:mt-6">
        <video v-show="!isImage" v-if="form.attending?.toLowerCase() !== 'attend'" ref="videoElement" autoplay playsinline muted class="rounded-xl h-full lg:h-[20rem] m-auto"></video>
        <!-- <div v-if="isImage" class="bg-gray-600 rounded-xl"></div> -->
        <!-- <div v-else class="bg-gray-700 m-auto rounded-xl w-full h-full lg:w-[426px] lg:h-[320px]"></div> -->
        <img v-show="isImage" id="imgElem" class="w-full lg:max-w-[426px] max-h-[320px] m-auto rounded-xl"></img>
      </div>
      <div class="flex mt-4 justify-center space-x-4 lg:mt-6">
        <div v-show="form.attending?.toLowerCase() !== 'attend'">
          <button v-show="!isImage" @click="capture" class="bg-blue-600 hover:bg-blue-700 text-white w-fit px-6 py-2 rounded-lg m-auto">Capture</button>
          <button v-show="isImage" @click="recapture" class="bg-yellow-600 hover:bg-yellow-700 text-white w-fit px-6 py-2 rounded-lg m-auto">Recapture</button>
        </div>
        <div v-show="form.attending?.toLowerCase() !== 'attend'">
          <button @click="postAttend" v-show="isImage" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">Absen {{form.attending?.toLowerCase() === 'not attend' ? 'Checkin' :'Checkout'}}</button>
        </div>
        <div v-show="form.attending?.toLowerCase() === 'attend'">
          <button @click="activeTabIndex = 1" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Lihat Detail Absen</button>
        </div>
      </div>
      <div class="flex justify-between mt-6">
        <div class="flex space-x-2 items-center">
          <icon fa="calendar" class="text-blue-600"/>
          <h2 class="text-md lg:pr-10 text-gray-700">{{form.day}}, {{form.tanggal}}</h2>
        </div>
        <div class="flex space-x-2 items-center">
          <icon fa="clock" class="text-blue-600"/>
          <h2 class="text-md lg:pr-10 text-gray-700">{{form.currentTime}}</h2>
        </div>
    </div>
    <hr>
    <div class="px-4">
      <table class="mt-2 lg:block hidden">
        <tr>
          <td class="align-top font-semibold">Lokasi</td>
          <td class="px-2 align-top font-semibold">:</td>
          <td class="align-top pb-2">{{form.address}}</td>
        </tr>
        <tr>
          <td class="align-top font-semibold">Keterangan</td>
          <td class="px-2 align-top font-semibold">:</td>
          <td :class="form.distance_check ? 'text-green-600 align-top':'text-red-600 align-top'">{{form.distance_check ? 'On Scope' : 'Out Scope'}}</td>
        </tr>
      </table>
      <table class="mt-4 block lg:hidden">
        <tr>
          <td colspan="2" class="align-top font-semibold">Lokasi :</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="align-top pb-6">{{form.address}}</td>
        </tr>
        <tr>
          <td colspan="2" class="align-top font-semibold">Keterangan :</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td :class="form.distance_check ? 'text-green-600 align-top':'text-red-600 align-top'">{{form.distance_check ? 'On Scope' : 'Out Scope'}}</td>
        </tr>
      </table>
    </div>
  </div>
  <div v-show="activeTabIndex === 1">
    <div class="hidden lg:block my-8 lg:flex space-x-6 items-center">
      <label>Bulan :</label>
      <FieldSelect
      class="w-[20%]"
        :bind="{ disabled: false, clearable:false }"
        :value="form.month" @input="v=>form.month=v"
        valueField="id" displayField="name"
        :options="listMonths"
        @update:valueFull="(e)=>{
          getDetailAbsen(form.year,e.id)
        }"
        placeholder="Pilih Bulan" label="":check="false"
      />
      <label>Tahun :</label>
      <FieldSelect
        :bind="{ disabled: false, clearable:false }"
        :value="form.year" @input="v=>{form.year=v}"
        valueField="key" displayField="key"
        :options="listTahun"
        @update:valueFull="(e)=>{
          getDetailAbsen(e.id,form.month)
        }"
        placeholder="Tahun" label="":check="false"
        class="w-[20%]"
      />
    </div>
    <div class="block lg:hidden my-8 grid grid-rows-2 gap-4">
      <div class="flex space-x-3 items-center">
        <label>Bulan :</label>
        <FieldSelect
        class="w-[50%]"
          :bind="{ disabled: false, clearable:false }"
          :value="form.month" @input="v=>form.month=v"
          valueField="id" displayField="name"
          :options="listMonths"
          @update:valueFull="(e)=>{
            getDetailAbsen(form.year,e.id)
          }"
          placeholder="Pilih Bulan" label="":check="false"
        />
      </div>
      <div class="flex space-x-3 items-center">
        <label>Tahun :</label>
        <FieldSelect
          :bind="{ disabled: false, clearable:false }"
          :value="form.year" @input="v=>{form.year=v}"
          valueField="key" displayField="key"
          :options="listTahun"
          @update:valueFull="(e)=>{
            getDetailAbsen(e.id,form.month)
          }"
          placeholder="Tahun" label="":check="false"
          class="w-[50%]"
        />
      </div>
    </div>
    <div class="mt-4 grid grid-cols-1 lg:grid-cols-4 gap-4">
      <div @click=(tampilkanModal(item)) class="border-2 p-4 space-y-3 rounded hover:bg-gray-200 delay-100 cursor-pointer hover:border-gray-400" v-for="(item, index) in listDetail" :key="index">
        <span v-if="item.type?.toLowerCase() === 'hari kerja'" :class="item.status?.toLowerCase() === 'attend' ? 'bg-green-200 text-green-800' : (item.status?.toLowerCase() === 'working' ? 'bg-blue-200 text-blue-800' : 'bg-red-200 text-red-800')" class="font-semibold px-4 py-1 rounded">{{item.status?.toLowerCase() === 'attend' ? 'Hadir' : (item.status?.toLowerCase() === 'working' ? 'Belum Check Out' : 'Tidak Hadir')}}</span>
        <span v-else class="font-semibold px-4 py-1 rounded bg-gray-200 text-gray-800">Hari Libur</span>
        <h1>{{item.day_name_idn}}, {{removeStrip(item.date_to_idn)}}</h1>
        <div class="flex space-x-4">
          <span>In : {{item.checkin_time ? item.checkin_time : '-'}}</span>
          <span>Out : {{item.checkout_time ? item.checkout_time : '-'}}</span>
        </div>
      </div>
    </div>
  </div>
  <!-- <img v-if="capturedImage" :src="capturedImage" alt="Captured Image"> -->
</div>
<div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50" id="modal">
    <!-- Modal Overlay (background) -->
    <div class="fixed inset-0 bg-black opacity-50" id="modal"></div>

    <!-- Modal Content -->
    <div class="bg-white w-[90%] lg:w-[70%] rounded shadow-lg z-10 overflow-auto max-h-[70%]">
        <div class="flex justify-between items-center px-[30px] py-[27px] border-b">
          <h2 class="text-2xl font-semibold">Detail</h2>
            <icon fa="remove" class="cursor-pointer text-[30px] font-normal text-[#8F8F8F]"  @click="showModal=false"/>
        </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 text-[14px] gap-x-[29px] gap-y-[20px] px-[10px] lg:px-[30px] py-[27px]">
        <div class="flex space-x-4">
          <div class="w-[60%] lg:w-[40%]">
            <img v-if="dataDetail.checkin_foto" :src="`${dataDetail.checkin_foto}`"class="!mt-2 w-full lg:w-[166px]">
            <div v-else class="h-[166px] bg-gray-500 w-full lg:w-[166px] rounded-[10px]"></div>
          </div>
          <table class="w-full lg:block hidden table-auto">
            <tr class="h-fit">
              <td class="w-[30%] align-top h-fit font-semibold">Alamat Checkin</td>
              <td class="align-top h-fit px-2 w-[10%] font-semibold">:</td>
              <td class="align-top h-fit pb-2">{{dataDetail.checkin_address ? dataDetail.checkin_address : '-'}}</td>
            </tr>
            <tr>
              <td class="align-top h-fit font-semibold">Jam Checkin</td>
              <td class="align-top h-fit px-2 w-[10%] font-semibold">:</td>
              <td class="align-top h-fit pb-2">{{dataDetail.checkin_time ? dataDetail.checkin_time : '-'}}</td>
            </tr>
            <tr>
              <td class="align-top h-fit font-semibold">Office</td>
              <td class="align-top h-fit px-2 w-[10%] font-semibold">:</td>
              <td :class="dataDetail.checkin_region?.toLowerCase() === 'in scope' ? 'text-green-600' : (dataDetail.checkin_region?.toLowerCase() === 'out scope' ? 'text-red-600' : 'text-black')" class="align-top h-fit pb-2">{{dataDetail.checkin_region ? dataDetail.checkin_region : '-'}}</td>
            </tr>
          </table>
          <table class="w-full block lg:hidden table-auto">
            <tr>
              <td class="w-[30%] align-top h-fit font-semibold">Alamat Checkin :</td>
            </tr>
            <tr>
              <td class="align-top h-fit pb-2">{{dataDetail.checkin_address ? dataDetail.checkin_address : '-'}}</td>
            </tr>
            <tr>
              <td class="align-top h-fit font-semibold">Jam Checkin :</td>
            </tr>
            <tr>
              <td class="align-top h-fit pb-2">{{dataDetail.checkin_time ? dataDetail.checkin_time : '-'}}</td>
            </tr>
            <tr>
              <td class="align-top h-fit font-semibold">Office :</td>
            </tr>
            <tr>
              <td :class="dataDetail.checkin_region?.toLowerCase() === 'in scope' ? 'text-green-600' : (dataDetail.checkin_region?.toLowerCase() === 'out scope' ? 'text-red-600' : 'text-black')" class="align-top h-fit pb-2">{{dataDetail.checkin_region ? dataDetail.checkin_region : '-'}}</td>
            </tr>
          </table>
        </div>
        <div class="flex space-x-4">
          <div class="w-[60%] lg:w-[40%]">
            <img v-if="dataDetail.checkout_foto" :src="`${dataDetail.checkout_foto}`"class="!mt-2 w-full lg:w-[166px]">
            <div v-else class="h-[166px] bg-gray-500 w-full lg:w-[166px] rounded-[10px]"></div>
          </div>
          <table class="w-full lg:block hidden table-auto">
            <tr class="h-fit">
              <td class="w-[30%] align-top h-fit font-semibold">Alamat Checkout</td>
              <td class="align-top px-2 h-fit w-[10%] font-semibold">:</td>
              <td class="align-top h-fit pb-2">{{dataDetail.checkout_address ? dataDetail.checkout_address : '-'}}</td>
            </tr>
            <tr>
              <td class="align-top h-fit font-semibold">Jam Checkin</td>
              <td class="align-top px-2 h-fit w-[10%] font-semibold">:</td>
              <td class="align-top h-fit pb-2">{{dataDetail.checkout_time ? dataDetail.checkout_time : '-'}}</td>
            </tr>
            <tr>
              <td class="align-top h-fit font-semibold">Office</td>
              <td class="align-top px-2 h-fit w-[10%] font-semibold">:</td>
              <td :class="dataDetail.checkout?.toLowerCase() === 'in scope' ? 'text-green-600' : 'text-red-600' " class="align-top h-fit pb-2">{{dataDetail.checkout_region ? dataDetail.checkout_region : '-'}}</td>
            </tr>
          </table>
          <table class="w-full block lg:hidden table-auto">
            <tr>
              <td class="w-[30%] align-top h-fit font-semibold">Alamat Checkout :</td>
            </tr>
            <tr>
              <td class="align-top h-fit pb-2">{{dataDetail.checkout_address ? dataDetail.checkout_address : '-'}}</td>
            </tr>
            <tr>
              <td class="align-top h-fit font-semibold">Jam Checkout :</td>
            </tr>
            <tr>
              <td class="align-top h-fit pb-2">{{dataDetail.checkout_time ? dataDetail.checkout_time : '-'}}</td>
            </tr>
            <tr>
              <td class="align-top h-fit font-semibold">Office :</td>
            </tr>
            <tr>
              <td :class="dataDetail.checkout_region?.toLowerCase() === 'in scope' ? 'text-green-600' : (dataDetail.checkout_region?.toLowerCase() === 'out scope' ? 'text-red-600' : 'text-black')" class="align-top h-fit pb-2">{{dataDetail.checkout_region ? dataDetail.checkout_region : '-'}}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
@endverbatim
@else


@verbatim

<div>
  <div class="flex flex-col bg-white p-6 w-full h-full">
    <Writer :value="values.content" 
    @input="$log('halo')" />
  </div>
</div>
@endverbatim
@endif