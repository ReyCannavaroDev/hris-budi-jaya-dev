//   javascriptimport { useRouter, useRoute, RouterLink } from 'vue-router'
import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Tambah' : route.query.action)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=`+(Date.parse(new Date()))
const detailArrKaryawan = ref([])
const detailArrJadwal = ref([])

// ------------------------------ PERSIAPAN
const endpointApi = '/presensi_maksi_jadwal_ambil'
onBeforeMount(()=>{
  document.title = 'Form Jadwal Pengambilan Makan Siang'
})

let initialValues = {}
const changedValues = []
let resultValues = reactive({items:[]})

const values = reactive({
})

function onDetailAdd(selectedData) {
  selectedData.forEach((item) => {
    item.id             = item['id']
    item.nama_lengkap   = item['nama_lengkap']
    item.nik            = item['nik']
    item['m_dept_nama'] = item['m_dept_nama']
    detailArrKaryawan.value.push(item)
  })
}

function removeDetail(item) {
  console.log(this)
  const index = this.detailArrKaryawan.findIndex((element) => element.id === item.id);
  if (index !== -1) {
    this.detailArrKaryawan.splice(index, 1);
  }
}

async function generateJadwal() {

  detailArrJadwal.value = []
  const dataKaryawan = detailArrKaryawan.value

  if(dataKaryawan.length<1){
    swal.fire({
      icon: 'warning',
      text: 'Silahkan pilih Karyawan terlebih dahulu sebelum Generate Jadwal'
    })
    return
  }

  dataKaryawan.forEach((item) => {
    item.id             = item['id']
    item.hari           = item['hari']
    item.tnggal         = item['nama_lengkap']
    item.nama_lengkap   = item['nama_lengkap']
    item.nik            = item['nik']
    detailArrJadwal.value.push(item)
  })
    // detailArrJadwal.value = []
    //   try {
    //     const dataURL = `${store.server.url_backend}/operation/t_perhitungan_gaji/generate`

    //     const res = await fetch(dataURL, {
    //       method: 'POST',
    //       headers: {
    //         'Content-Type': 'Application/json',
    //         Authorization: `${store.user.token_type} ${store.user.token}`
    //       },
    //       body: JSON.stringify(values)

    //     })
    //     if (!res.ok) {
    //       if ([400, 422].includes(res.status)) {
    //         const responseJson = await res.json()
    //         formErrors.value = responseJson.errors || {}
    //         throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
    //       } else {
    //         throw ("Failed when trying to post data")
    //       }
    //     }
        
    //     const result = await res.json()
    //     const resultData = result.data
    //     if(!resultData?.length) {
    //          swal.fire({
    //       icon: 'warning',
    //       text: "Tidak ditemukan data Perhitungan Gaji, pastikan karyawan sudah memiliki standar gaji"
    //     })
    //     }
    //     resultData.forEach((item) => {
    //       item.id           = item['id']
    //       item.hari     = item['hari']
    //       item.tanggal    = item['tanggal']
    //       item.nik    = item['nik']
    //       item.nama_lengkap    = item['nama_lengkap']
    //       detailArrJadwal.value.push(item)
    //     })
        
    //   } catch (err) {
    //     isBadForm.value = true
    //     swal.fire({
    //       icon: 'error',
    //       text: err
    //     })
    //   }
      
}

watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))