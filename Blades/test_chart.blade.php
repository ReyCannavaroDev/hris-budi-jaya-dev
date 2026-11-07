@verbatim
<h1 class="text-2xl font-semibold px-4 mt-6 mb-4">Dashboard</h1>
<div class="grid grid-cols-4 gap-6 px-4 w-full mb-4">
    <div class="bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
      <p class="font-semibold text-base">Total Pesanan: Order/Hari</p>
      <p class="font-bold text-2xl mt-2 mb-1">22.560</p>
      <p><span class="text-green-600 font-semibold"><icon fa="arrow-up"/>+10% </span>Dari Kemarin</p>
    </div>

    <div class="bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
      <p class="font-semibold text-base">Total Pesanan: Order/Bulan</p>
      <p class="font-bold text-2xl mt-2 mb-1">83.490</p>
      <p><span class="text-green-600 font-semibold"><icon fa="arrow-up"/>+20% </span>Dari Bulan Lalu</p>
    </div>

    <div class="bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
      <p class="font-semibold text-base">Total Pesanan: Amount</p>
      <p class="font-bold text-2xl mt-2 mb-1">9.350.000.000</p>
      <p><span class="text-green-600 font-semibold"><icon fa="arrow-up"/>+15% </span>Dari Bulan Lalu</p>
    </div>

    <div class="bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
      <p class="font-semibold text-base">New User</p>
      <p class="font-bold text-2xl mt-2 mb-1">1.670</p>
      <p><span class="text-green-600 font-semibold"><icon fa="arrow-up"/>+10% </span>Dari Bulan Lalu</p>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 px-4 w-full mb-4">
  <div class="col-span-8 bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Total Pesanan: Order by Month</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
  <line-chart :data="dataChart" height="390px" adapter="highcharts"/>
  </div>
  <div class="col-span-4 bg-white bg-opacity-95 border-t-4 border-[#00AA13] shadow-lg py-4 rounded-lg px-4 flex flex-col">
  <div class="flex justify-between p-2"><h3 class="text-lg font-semibold">Calendar</h3><h3 class="align-text-bottom align-bottom">{{ currentMonth }} {{ currentYear }}</h3></div>
    <table class="border-collapse text-[#757575] mb-4">
      <tr>
        <th v-for="day in daysOfWeek" :key="day" class="p-1 font-semibold">{{ day }}</th>
      </tr>
      <tr v-for="row in calendarRows" :key="row" class="">
        <td v-for="cell in row" :key="cell.date" :class="{ 'bg-[#00AA13] text-white': cell.isToday, 'cursor-pointer':cell.day }" class="p-1 text-center" @click="handleDateClick(cell.date)">{{ cell.day }}</td>
      </tr>
    </table>
    <div class="bg-[#00AA13] h-[1px] mb-6"></div>
    <div class="flex justify-between mb-2">
      <h1 class="font-semibold text-lg">Activity</h1>
      <h1>{{currentDate}} {{currentMonth}} {{currentYear}}</h1>
    </div>
    <table>
      <tr class="bg-[#FBFBFB] rounded-xl">
        <td class="p-2 rounded-lg">Supriyadi - PT Jaya Makmur</td>
      </tr>
      <tr>
        <td class="p-2 rounded-lg">Fahrizal - PT Jasa</td>
      </tr>
      <tr class="bg-[#FBFBFB]">
        <td class="p-2 rounded-lg">Syamsul - PT Agung</td>
      </tr>
    </table>
  </div>
</div>

<div class="bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 mx-4 mb-4 flex flex-col">
  <h1 class="font-semibold text-lg mb-2">Tukang</h1>
  <TableApi ref='apiTable' class="!p-0" :isPopup="true" :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
    </template>
  </TableApi>
</div>


<div class="grid grid-cols-12 gap-6 px-4 w-full mb-4">
  <div class="col-span-8 bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Total Pesanan: Order by Area</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
    <column-chart height="300px" :data="[['Jawa Timur', 435], ['Kalimantan', 427], ['Bali', 150], ['Irian Jaya', 380], 
    ['Jawa Barat', 820], ['Jawa Tengah', 310], ['Madura', 530], ['DKI JAKARTA', 50], ['Sumatra', 504], ['NTT', 512]]" :colors="[['#F39C12','#F39C12']]"></bar-chart>
  </div>
  <div class="col-span-4 bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Order by Layanan</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
    <pie-chart :data="[['AC', 44], ['Listrik', 23], ['Pipa', 43], ['Massage', 73], ['Home Cleaning', 73] , ['Service Laptop & Gadget', 73]
    , ['Kunci', 73], ['Atap', 73] , ['Cat', 73] , ['Bangunan / Sipil', 73] , ['Besi , Las , Canopy', 73] , ['Desain Interior', 73] , ['Gorden', 73] , ['Cuci Mobil', 73]
    , ['Nail Art', 73] , ['MakeUp', 73]  ]" legend="bottom" :library="{plugins: {
            legend: {
              position: 'bottom',
              labels: {
                boxWidth: 20
              },
              labels: {
                usePointStyle: true
              },
              fullWidth: true,
              align: 'start' 
              }
            
        }}"></pie-chart>
  </div>
</div>

  <div class="bg-white mx-4 bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Layanan By Tukang</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
    <bar-chart height="500px" :data="[['AC', 550], ['Listrik', 490] , ['Pipa', 375], ['Massage', 400], ['Cleaning', 410] , 
    ['Service Laptop & Gadget', 390] , ['Kunci', 210] , ['Atap', 300] , ['Cat', 150] , ['Bangunan / Sipil', 150]
    , ['Besi , Las , Canopy', 150] , ['Desain Interior', 450] , ['Gorden', 200] , ['Cuci Mobil', 260]
    , ['Nail Art', 350] , ['MakeUp', 420] ]" :colors="[['#F39C12','#F39C12']]"></bar-chart>
</div>



<div class="grid grid-cols-2 gap-6 px-4 w-full mb-4 p-4">
  <div class=" bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Top Perfomance by Tukang</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
    <column-chart :data="dataChart" :colors="['#2F80ED99','#DC2B1599']"></column-chart>
  </div>

  <div class=" bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Top Perfomance by Layanan</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
    <column-chart :data="dataChart" :colors="['#2F80ED99','#DC2B1599']"></column-chart>
  </div>
</div>




</div>
<!-- 
<div class="grid grid-cols-12 gap-6 px-4 w-full mb-4 p-4">
  <div class="col-span-8 bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Layanan By Tukang</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
    <bar-chart style="width: 100%; height: 100%;" :data="[['AC', 550], ['Listrik', 490] , ['Pipa', 375], ['Massage', 400], ['Cleaning', 410] , 
    ['Service Laptop & Gadget', 390] , ['Kunci', 210] , ['Atap', 300] , ['Cat', 150] , ['Bangunan / Sipil', 150]
    , ['Besi , Las , Canopy', 150] , ['Desain Interior', 450] , ['Gorden', 200] , ['Cuci Mobil', 260]
    , ['Nail Art', 350] , ['MakeUp', 420] ]" :colors="[['#F39C12','#F39C12']]"></bar-chart>
    </div>



<div class="col-span-4 bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col space-y-10"> 
    <div class=" bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Top Perfomance by Tukang</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
    <column-chart :data="dataChart" :colors="['#2F80ED99','#DC2B1599']"></column-chart>
  </div>

      <div class=" bg-white bg-opacity-95 shadow-lg py-4 rounded-lg px-4 flex flex-col">
    <div class="flex mb-4 justify-between">
      <h1 class="font-semibold text-base">Top Perfomance by Layanan</h1>
      <FieldSelect class="w-[20%] !mt-0"
        :bind="{ disabled: false, clearable:false }"
        :value="values.bulan_1" @input="v=>values.bulan_1=v"
        valueField="key" displayField="key"
        :options="months"
        label="" :check="false"
      />
    </div>  
    <column-chart :data="dataChart" :colors="['#2F80ED99','#DC2B1599']"></column-chart>
  </div>
</div>




</div> -->

<!-- Best Perfoma -->
<h1 class="text-2xl font-semibold px-4 mt-6 mb-4">Best Perfomance</h1>
<div class="grid grid-cols-4 gap-4 p-4"> 
  <!-- 1 -->
  <div class="max-w-md">
    <div class="h-full border-2 bg-white border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
      <img class="h-48 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
      <div class="p-6">
        <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Udin</h1>
        <p class="leading-relaxed mb-3">
            Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing
            tousled waistcoat.
        </p>
        <div class="flex items-center flex-wrap">
          <span class="text-black font-semibold mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
            <p>768 PEKERJAAN</p>  
          </span>
          <span class="text-black font-semibold inline-flex items-center leading-none text-sm space-x-2">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="14" cy="14" r="14" fill="#FFB800" fill-opacity="0.13"/>
            <path d="M13.6634 7.80937C13.8011 7.53034 14.199 7.53034 14.3367 7.80937L16.1194 11.4215C16.1741 11.5323 16.2798 11.6091 16.402 11.6268L20.3882 12.206C20.6961 12.2508 20.8191 12.6292 20.5963 12.8464L17.7119 15.658C17.6234 15.7443 17.583 15.8685 17.6039 15.9903L18.2848 19.9604C18.3374 20.2671 18.0155 20.5009 17.7401 20.3561L14.1747 18.4817C14.0654 18.4242 13.9347 18.4242 13.8253 18.4817L10.26 20.3561C9.98459 20.5009 9.66269 20.2671 9.71529 19.9604L10.3962 15.9903C10.4171 15.8685 10.3767 15.7443 10.2882 15.658L7.40382 12.8464C7.181 12.6292 7.30396 12.2508 7.61189 12.206L11.5981 11.6268C11.7203 11.6091 11.826 11.5323 11.8807 11.4215L13.6634 7.80937Z" fill="#F7BE2C"/>
          </svg>
          <p>4.9</p>  
          </span>
        </div>
      </div>
    </div>
  </div>
<!-- 2 -->
  <div class="max-w-md">
    <div class="h-full border-2 bg-white border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
      <img class="h-48 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
      <div class="p-6">
        <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Udin</h1>
        <p class="leading-relaxed mb-3">
            Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing
            tousled waistcoat.
        </p>
        <div class="flex items-center flex-wrap">
          <span class="text-black font-semibold mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
            <p>768 PEKERJAAN</p>  
          </span>
          <span class="text-black font-semibold inline-flex items-center leading-none text-sm space-x-2">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="14" cy="14" r="14" fill="#FFB800" fill-opacity="0.13"/>
            <path d="M13.6634 7.80937C13.8011 7.53034 14.199 7.53034 14.3367 7.80937L16.1194 11.4215C16.1741 11.5323 16.2798 11.6091 16.402 11.6268L20.3882 12.206C20.6961 12.2508 20.8191 12.6292 20.5963 12.8464L17.7119 15.658C17.6234 15.7443 17.583 15.8685 17.6039 15.9903L18.2848 19.9604C18.3374 20.2671 18.0155 20.5009 17.7401 20.3561L14.1747 18.4817C14.0654 18.4242 13.9347 18.4242 13.8253 18.4817L10.26 20.3561C9.98459 20.5009 9.66269 20.2671 9.71529 19.9604L10.3962 15.9903C10.4171 15.8685 10.3767 15.7443 10.2882 15.658L7.40382 12.8464C7.181 12.6292 7.30396 12.2508 7.61189 12.206L11.5981 11.6268C11.7203 11.6091 11.826 11.5323 11.8807 11.4215L13.6634 7.80937Z" fill="#F7BE2C"/>
          </svg>
          <p>4.9</p>  
          </span>
        </div>
      </div>
    </div>
  </div>
<!-- 3 -->
  <div class="max-w-md">
    <div class="h-full border-2 bg-white border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
      <img class="h-48 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
      <div class="p-6">
        <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Udin</h1>
        <p class="leading-relaxed mb-3">
            Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing
            tousled waistcoat.
        </p>
        <div class="flex items-center flex-wrap">
          <span class="text-black font-semibold mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
            <p>768 PEKERJAAN</p>  
          </span>
          <span class="text-black font-semibold inline-flex items-center leading-none text-sm space-x-2">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="14" cy="14" r="14" fill="#FFB800" fill-opacity="0.13"/>
            <path d="M13.6634 7.80937C13.8011 7.53034 14.199 7.53034 14.3367 7.80937L16.1194 11.4215C16.1741 11.5323 16.2798 11.6091 16.402 11.6268L20.3882 12.206C20.6961 12.2508 20.8191 12.6292 20.5963 12.8464L17.7119 15.658C17.6234 15.7443 17.583 15.8685 17.6039 15.9903L18.2848 19.9604C18.3374 20.2671 18.0155 20.5009 17.7401 20.3561L14.1747 18.4817C14.0654 18.4242 13.9347 18.4242 13.8253 18.4817L10.26 20.3561C9.98459 20.5009 9.66269 20.2671 9.71529 19.9604L10.3962 15.9903C10.4171 15.8685 10.3767 15.7443 10.2882 15.658L7.40382 12.8464C7.181 12.6292 7.30396 12.2508 7.61189 12.206L11.5981 11.6268C11.7203 11.6091 11.826 11.5323 11.8807 11.4215L13.6634 7.80937Z" fill="#F7BE2C"/>
          </svg>
          <p>4.9</p>  
          </span>
        </div>
      </div>
    </div>
  </div>
<!-- 4 -->
  <div class="max-w-md">
    <div class="h-full border-2 bg-white border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
      <img class="h-48 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
      <div class="p-6">
        <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Udin</h1>
        <p class="leading-relaxed mb-3">
            Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing
            tousled waistcoat.
        </p>
        <div class="flex items-center flex-wrap">
          <span class="text-black font-semibold mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
            <p>768 PEKERJAAN</p>  
          </span>
          <span class="text-black font-semibold inline-flex items-center leading-none text-sm space-x-2">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="14" cy="14" r="14" fill="#FFB800" fill-opacity="0.13"/>
            <path d="M13.6634 7.80937C13.8011 7.53034 14.199 7.53034 14.3367 7.80937L16.1194 11.4215C16.1741 11.5323 16.2798 11.6091 16.402 11.6268L20.3882 12.206C20.6961 12.2508 20.8191 12.6292 20.5963 12.8464L17.7119 15.658C17.6234 15.7443 17.583 15.8685 17.6039 15.9903L18.2848 19.9604C18.3374 20.2671 18.0155 20.5009 17.7401 20.3561L14.1747 18.4817C14.0654 18.4242 13.9347 18.4242 13.8253 18.4817L10.26 20.3561C9.98459 20.5009 9.66269 20.2671 9.71529 19.9604L10.3962 15.9903C10.4171 15.8685 10.3767 15.7443 10.2882 15.658L7.40382 12.8464C7.181 12.6292 7.30396 12.2508 7.61189 12.206L11.5981 11.6268C11.7203 11.6091 11.826 11.5323 11.8807 11.4215L13.6634 7.80937Z" fill="#F7BE2C"/>
          </svg>
          <p>4.9</p>  
          </span>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Bad Perfoma -->
<h1 class="text-2xl font-semibold px-4 mt-6 mb-4">Bad Perfomance</h1><div class="grid grid-cols-4 gap-4 p-4"> 
  <!-- 1 -->
  <div class="max-w-md">
    <div class="h-full border-2 bg-white border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
      <img class="h-48 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
      <div class="p-6">
        <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Udin</h1>
        <p class="leading-relaxed mb-3">
            Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing
            tousled waistcoat.
        </p>
        <div class="flex items-center flex-wrap">
          <span class="text-black font-semibold mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
            <p>768 PEKERJAAN</p>  
          </span>
          <span class="text-black font-semibold inline-flex items-center leading-none text-sm space-x-2">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="14" cy="14" r="14" fill="#FFB800" fill-opacity="0.13"/>
            <path d="M13.6634 7.80937C13.8011 7.53034 14.199 7.53034 14.3367 7.80937L16.1194 11.4215C16.1741 11.5323 16.2798 11.6091 16.402 11.6268L20.3882 12.206C20.6961 12.2508 20.8191 12.6292 20.5963 12.8464L17.7119 15.658C17.6234 15.7443 17.583 15.8685 17.6039 15.9903L18.2848 19.9604C18.3374 20.2671 18.0155 20.5009 17.7401 20.3561L14.1747 18.4817C14.0654 18.4242 13.9347 18.4242 13.8253 18.4817L10.26 20.3561C9.98459 20.5009 9.66269 20.2671 9.71529 19.9604L10.3962 15.9903C10.4171 15.8685 10.3767 15.7443 10.2882 15.658L7.40382 12.8464C7.181 12.6292 7.30396 12.2508 7.61189 12.206L11.5981 11.6268C11.7203 11.6091 11.826 11.5323 11.8807 11.4215L13.6634 7.80937Z" fill="#F7BE2C"/>
          </svg>
          <p>4.9</p>  
          </span>
        </div>
      </div>
    </div>
  </div>
<!-- 2 -->
  <div class="max-w-md">
    <div class="h-full border-2 bg-white border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
      <img class="h-48 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
      <div class="p-6">
        <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Udin</h1>
        <p class="leading-relaxed mb-3">
            Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing
            tousled waistcoat.
        </p>
        <div class="flex items-center flex-wrap">
          <span class="text-black font-semibold mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
            <p>768 PEKERJAAN</p>  
          </span>
          <span class="text-black font-semibold inline-flex items-center leading-none text-sm space-x-2">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="14" cy="14" r="14" fill="#FFB800" fill-opacity="0.13"/>
            <path d="M13.6634 7.80937C13.8011 7.53034 14.199 7.53034 14.3367 7.80937L16.1194 11.4215C16.1741 11.5323 16.2798 11.6091 16.402 11.6268L20.3882 12.206C20.6961 12.2508 20.8191 12.6292 20.5963 12.8464L17.7119 15.658C17.6234 15.7443 17.583 15.8685 17.6039 15.9903L18.2848 19.9604C18.3374 20.2671 18.0155 20.5009 17.7401 20.3561L14.1747 18.4817C14.0654 18.4242 13.9347 18.4242 13.8253 18.4817L10.26 20.3561C9.98459 20.5009 9.66269 20.2671 9.71529 19.9604L10.3962 15.9903C10.4171 15.8685 10.3767 15.7443 10.2882 15.658L7.40382 12.8464C7.181 12.6292 7.30396 12.2508 7.61189 12.206L11.5981 11.6268C11.7203 11.6091 11.826 11.5323 11.8807 11.4215L13.6634 7.80937Z" fill="#F7BE2C"/>
          </svg>
          <p>4.9</p>  
          </span>
        </div>
      </div>
    </div>
  </div>
<!-- 3 -->
  <div class="max-w-md">
    <div class="h-full border-2 bg-white border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
      <img class="h-48 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
      <div class="p-6">
        <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Udin</h1>
        <p class="leading-relaxed mb-3">
            Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing
            tousled waistcoat.
        </p>
        <div class="flex items-center flex-wrap">
          <span class="text-black font-semibold mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
            <p>768 PEKERJAAN</p>  
          </span>
          <span class="text-black font-semibold inline-flex items-center leading-none text-sm space-x-2">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="14" cy="14" r="14" fill="#FFB800" fill-opacity="0.13"/>
            <path d="M13.6634 7.80937C13.8011 7.53034 14.199 7.53034 14.3367 7.80937L16.1194 11.4215C16.1741 11.5323 16.2798 11.6091 16.402 11.6268L20.3882 12.206C20.6961 12.2508 20.8191 12.6292 20.5963 12.8464L17.7119 15.658C17.6234 15.7443 17.583 15.8685 17.6039 15.9903L18.2848 19.9604C18.3374 20.2671 18.0155 20.5009 17.7401 20.3561L14.1747 18.4817C14.0654 18.4242 13.9347 18.4242 13.8253 18.4817L10.26 20.3561C9.98459 20.5009 9.66269 20.2671 9.71529 19.9604L10.3962 15.9903C10.4171 15.8685 10.3767 15.7443 10.2882 15.658L7.40382 12.8464C7.181 12.6292 7.30396 12.2508 7.61189 12.206L11.5981 11.6268C11.7203 11.6091 11.826 11.5323 11.8807 11.4215L13.6634 7.80937Z" fill="#F7BE2C"/>
          </svg>
          <p>4.9</p>  
          </span>
        </div>
      </div>
    </div>
  </div>
<!-- 4 -->
  <div class="max-w-md">
    <div class="h-full border-2 bg-white border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
      <img class="h-48 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
      <div class="p-6">
        <h1 class="title-font text-lg font-medium text-gray-900 mb-3">Udin</h1>
        <p class="leading-relaxed mb-3">
            Photo booth fam kinfolk cold-pressed sriracha leggings jianbing microdosing
            tousled waistcoat.
        </p>
        <div class="flex items-center flex-wrap">
          <span class="text-black font-semibold mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
            <p>768 PEKERJAAN</p>  
          </span>
          <span class="text-black font-semibold inline-flex items-center leading-none text-sm space-x-2">
          <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="14" cy="14" r="14" fill="#FFB800" fill-opacity="0.13"/>
            <path d="M13.6634 7.80937C13.8011 7.53034 14.199 7.53034 14.3367 7.80937L16.1194 11.4215C16.1741 11.5323 16.2798 11.6091 16.402 11.6268L20.3882 12.206C20.6961 12.2508 20.8191 12.6292 20.5963 12.8464L17.7119 15.658C17.6234 15.7443 17.583 15.8685 17.6039 15.9903L18.2848 19.9604C18.3374 20.2671 18.0155 20.5009 17.7401 20.3561L14.1747 18.4817C14.0654 18.4242 13.9347 18.4242 13.8253 18.4817L10.26 20.3561C9.98459 20.5009 9.66269 20.2671 9.71529 19.9604L10.3962 15.9903C10.4171 15.8685 10.3767 15.7443 10.2882 15.658L7.40382 12.8464C7.181 12.6292 7.30396 12.2508 7.61189 12.206L11.5981 11.6268C11.7203 11.6091 11.826 11.5323 11.8807 11.4215L13.6634 7.80937Z" fill="#F7BE2C"/>
          </svg>
          <p>4.9</p>  
          </span>
        </div>
      </div>
    </div>
  </div>
</div>





@endverbatim

