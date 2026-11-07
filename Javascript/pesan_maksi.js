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

// ------------------------------ PERSIAPAN
const endpointApi = '/presensi_maksi'
onBeforeMount(()=>{
  document.title = 'Form Pesan Makan Siang'
})

let initialValues = {}
const changedValues = []
let resultValues = reactive({items:[]})

const values = reactive({
})


onBeforeMount(async () => {
  loadInitalData()

  // const tempNewTanggal = values.tanggal.split('-')
  // values.tanggal = `${tempNewTanggal[2]} ${tempNewTanggal[1]} ${tempNewTanggal[0]}`
  const tempDate = new Date()
  const day = tempDate.getDate() 
  const monthName = ["Januari", "Februari", "Maret", "April","Mei", "Juni", "Juli", "Agustus","September", "Oktober", "November", "Desemeber"][tempDate.getMonth()]
  const year = tempDate.getFullYear() 
  values.tanggal = `${day} ${monthName} ${year}`  
  values.day = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][tempDate.getDay()]
})

const loadInitalData = async ()=>{
   //  READ DATA
  try {
    const dataURL = `${store.server.url_backend}/operation${endpointApi}/get_maksi`
    isRequesting.value = true

    const params = { join: false, transform: false }
    const fixedParams = new URLSearchParams(params)
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    })
    if (!res.ok) throw new Error("Failed when trying to read data")
    const resultJson = await res.json()
    initialValues = resultJson.data
   
  } catch (err) {
    isBadForm.value = true
    // swal.fire({
    //   icon: 'error',
    //   text: err,
    //   allowOutsideClick: false,
    //   confirmButtonText: 'Kembali',
    // })
    // isRequesting.value = false
  }

  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
  // penting utk hitung menu tabel bawah
  loadDet()
  isRequesting.value=false
}

const checkItemDetail = (idx, i)=>{
  if(values.group_data[idx]['detail'][i]['tipe_lauk.value_2']?.toLowerCase()=== 'single'){
    values.group_data[idx]['not_check'] = false;
    values.group_data[idx]['detail'].forEach((d)=>{
      if(d.lauk === values.group_data[idx]['detail'][i]['lauk']){
        if(d.check){
          d.check = false;
        }else{
          d.check = true;
        }
      }else{
        d.check = false;
      }
    })
  }else{
    values.group_data[idx]['not_check'] = false;
    if(values.group_data[idx]['detail'][i]['check']){
      values.group_data[idx]['detail'][i]['check'] = false;
    }else{
      values.group_data[idx]['detail'][i]['check'] = true;
    }
  }

  loadDet()
}

const loadDet = () =>{
  if(initialValues?.sudah_pesan){
    resultValues.items = initialValues?.lauk
  }else{
    initialValues?.group_data?.forEach((d)=>{
      d['detail']?.forEach((drx)=>{
        if(drx['tipe_lauk.value_2']?.toLowerCase() === 'single'){
          if(drx['lauk']?.toLowerCase() === 'full' || drx['lauk']?.toLowerCase() === 'penuh'){
            // drx['check'] = true
          }else{
            return
          }
        }
      })
    })
    const filteredArrResult = values?.group_data?.map(item => ({ ...item, detail: item.detail.filter(det => det.check) })).filter(item => !item?.not_check);
    resultValues.items = filteredArrResult
    filteredArrResult?.forEach((v)=>{
      v.detail_text = v?.detail?.map(det => det.lauk).join(', ');
    })
  }
}

const checkItemDetailNotIn = (idx, i)=>{
  values.group_data[idx]['not_check'] = true;
  values.group_data[idx]['detail'].forEach((d)=>{
    d.check = false;
  })
  loadDet()
}

async function onSave() {
  //values.tags = JSON.stringify(values.tags)
  swal.fire({
    icon: 'warning',
    text: 'Kamu yakin sudah memilih menu dengan benar ?',
    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      let arrResult = {"pesan": []};

      try {
        const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
        const dataURL = `${store.server.url_backend}/operation/presensi_maksi_det/pesan_maksi`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify({
            presensi_maksi_id:initialValues.id,
            pesan:resultValues.items
          })
        })
        loadInitalData()
        if (!res.ok) {
          if ([400, 422].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
          } else {
            throw ("Failed when trying to post data")
          }
        }
      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          text: err
        })
      }
      isRequesting.value = false
    }
  })
}

async function onCancel() {
  //values.tags = JSON.stringify(values.tags)
  swal.fire({
    icon: 'warning',
    text: 'Kamu yakin akan membatalkan pesananmu ?',
    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {

      try {
        const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
        const dataURL = `${store.server.url_backend}/operation/presensi_maksi_det/cancel`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify({
            id:initialValues.id
          })
        })
        loadInitalData()
        if (!res.ok) {
          if ([400, 422].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
          } else {
            throw ("Failed when trying to post data")
          }
        }
      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          text: err
        })
      }
      isRequesting.value = false
    }
  })
}

watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))