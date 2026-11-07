@verbatim
<div class="bg-white p-6 space-y-2 rounded-md h-fit shadow-md shadow-gray-400">
  <div>
    <div class="flex space-y-1 lg:flex-row flex-col justify-between items-center">
      <h1 class="text-xl font-semibold">Pesan Makan Siang</h1>
      <h2 class="text-md lg:pr-10 text-gray-700">{{values.day}}, {{values.tanggal}}</h2>
    </div>
    <i class="text-gray-700" v-show="!values?.sudah_pesan">Keterangan : {{ values.keterangan ?? '-' }}</i>
    <div class="border-t-gray-300 border border-sm mt-2"></div>
  </div>
  <div class="mb-2 w-[50%] <md:w-[100%]">
    <h3 class="text-gray-700">Pesanan kamu</h3>
    <table v-if="resultValues.items?.length" class="text-gray-700 w-[100%]">
      <tr v-for="(item,idx) in resultValues.items" :key="idx">
        <th class="border border-[0.5px] border-gray-400 py-2 px-2">{{ item.tipe_lauk }}</th>
        <td class="border border-[0.5px] border-gray-400 py-2 px-2">{{ item?.detail_text ?? '-' }}</td>
      </tr>
    </table>
    <h4 v-else class="text-yellow-700 italic">Oops, kamu belum pesan makan, pilih menu dibawah ini untuk pesan makan</h3>
  </div>
  <div v-if="values?.sudah_pesan" class="mt-2 <md:text-center">
    <button @click="onCancel" class="bg-red-500 hover:bg-red-600 text-white px-[15px] py-[6px] rounded-md w-32">
      <span>Batalkan Pesanan</span>
    </button>
    <p class="font-[10px] text-gray-400 mt-1">*pesanan masih bisa dibatlkan jika menu makan untuk besok belum ditutup oleh admin.</p>
  </div>
  <hr>
  <div v-if="values?.group_data?.length && !values?.sudah_pesan">
    <h3 class="text-gray-700">Mau pesan makan apa ?</h3>
    <div>
      <div class="border rounded-md border-gray-200 p-4 shadow-xl">
        <div v-if="values.group_data" class="grid grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-4 lg:gap-y-0 <md:grid-cols-1">
          <div v-for="(item, idx) in values.group_data" :key="idx" >
            <h1 class="font-semibold pb-2 text-[14pt] text-gray-700">{{item?.tipe_lauk}}</h1>
            <div v-if="item?.detail?.length" v-for="(detailItem, i) in item.detail" :key="i" 
              @click="checkItemDetail(idx, i)"
              :class="detailItem.check == true ? 'font-semibold bg-green-500 text-white hover:border-green-500' : ''"
              class="space-x-3 mb-2 p-2 border-[1px] border-[#DEDEDE] rounded-lg cursor-pointer hover:border-green-400 duration-200">
                {{detailItem.lauk}}
            </div>
            <div  @click="checkItemDetailNotIn(idx)"
              :class="item?.not_check == true ? 'bg-red-400 !text-white' : ''"
              class="text-red-600 font-semibold space-x-3 mb-2 p-2 border-[1px] border-[#DEDEDE] rounded-lg cursor-pointer hover:border-red-400 duration-200">
              Tanpa {{item?.tipe_lauk}}
            </div>
          </div> 
        </div> 
        <div class="flex justify-end">
          <button @click="onSave" v-if="values.group_data" class="bg-green-500 hover:bg-green-600 text-white px-[15px] py-[6px] rounded-md w-32">
            <span>Pesan</span>
          </button>
        </div>
      </div>
    </div>
  </div>
  <div v-else>
    <h4 v-else class="text-red-400 italic font-semibold" v-show="!values?.sudah_pesan">Maaf, belum ada menu makan</h3>
  </div>
</div>

@endverbatim