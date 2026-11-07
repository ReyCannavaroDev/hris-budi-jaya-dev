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
const titleOpen = ref('')
const modalOpen = ref(false)
const detailArrOpen = reactive({items: []})

// ------------------------------ PERSIAPAN
const endpointApi = '/t_perhitungan_gaji'
onBeforeMount(()=>{
  document.title = 'Info Gaji'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS

function onReset() {
  swal.fire({
    icon: 'warning',
    text: 'Reset this form data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      for (const key in initialValues) {
        values[key] = initialValues[key]
      }
    }
  })
}

function onSave() {
  //values.tags = JSON.stringify(values.tags)
  swal.fire({
    icon: 'warning',
    text: 'Save data?',
    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
        const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify(values)
        })
        if (!res.ok) {
          if ([400, 422].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw new Error(responseJson.message || "Failed when trying to post data")
          } else {
            throw new Error("Failed when trying to post data")
          }
        }
        router.replace('/' + modulPath + '?reload='+(Date.parse(new Date())))
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

//  @else----------------------- 
let initialValues = {}
const changedValues = []
let thisMonth = new Date().toISOString().split('T')[0]
let tempYear = thisMonth.split('-')[0]
let tempMonth = thisMonth.split('-')[1]

const values = reactive({
  periode_awal: tempYear+'-'+tempMonth,
  periode_akhir: tempYear+'-'+tempMonth
})

function openDetail(i) {
  titleOpen.value = detailArr.value[i]?.nama_lengkap ?? '-' +' - ' +detailArr.value[i]?.nama_lengkap ?? ''
  let dataFormat =  detailArr.value[i]?.detail_gaji ?? []

  detailArrOpen.items = dataFormat
  modalOpen.value = true
}
onBeforeMount(async () => {
  // console.log(tempMonth, tempYear)
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
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
      swal.fire({
        icon: 'error',
        text: err,
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        router.back()
      })
    }
    isRequesting.value = false
  }

  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
})

let _id = 0
const detailArr = ref([])
// const onGenerate = async () => {
//   detailArr.value = []
//   const tempWhere = []
//   isRequesting.value = true
//   if(values.icon){
//     tempWhere.push(`icon='${values.icon}'`)
//   }
//   if(values.tgl){
//     const [hari, bulan, tahun] = values.tgl.split('/');
//     const tempDate= `${tahun}/${bulan}/${hari}`
//     if(values.tgl2){      
//       const [hari2, bulan2, tahun2] = values.tgl2.split('/');
//       const tempDate2= `${tahun2}/${bulan2}/${hari2}`
//       tempWhere.push(`t_perhitungan_gaji.periode_in_date BETWEEN '${tempDate}' AND '${tempDate2}'`)
//     }else{ 
//       tempWhere.push(`t_perhitungan_gaji.periode_in_date >= '${tempDate}'`)
//     }
//   }
//   if(values.tgl2){
//     if(values.tgl === undefined || values.tgl === null){
//       const [hari, bulan, tahun] = values.tgl2.split('/');
//       const tempDate= `${tahun}/${bulan}/${hari}`
//       tempWhere.push(`t_perhitungan_gaji.periode_in_date >= '${tempDate}'`)
//     }
//   }
//   if(values.m_kary_id){
//     tempWhere.push(`this.m_kary_id='${values.m_kary_id}'`)
//   }
//   const paramsWhere = tempWhere.join(" and ")
//   const response = await fetch(`${store.server.url_backend}/operation/t_perhitungan_gaji` + '?' + new URLSearchParams({where: paramsWhere}), {
//         headers: {
//           'Content-Type': 'Application/json',
//           Authorization: `${store.user.token_type} ${store.user.token}`
//         },
//       })
//   if (!response.ok) throw new Error("Failed when trying to read data")
//   const resultJson2 = await response.json()
//   const init = resultJson2.data
//   init.forEach((item) => {
//     item.id = ++_id
//     detailArr.value.push(item)
//   })
//   values.icon = null
//   values.tgl = null
//   values.tgl2 = null
//   isRequesting.value = false
// }

async function generatePerhitungan() {
    detailArr.value = []
  //values.tags = JSON.stringify(values.tags)
      try {
        const dataURL = `${store.server.url_backend}/operation/t_perhitungan_gaji/generate`

        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify(values)

        })
        if (!res.ok) {
          if ([400, 422].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
          } else {
            throw ("Failed when trying to post data")
          }
        }
        
        const result = await res.json()
        const resultData = result.data

        resultData.forEach((item) => {
          item.id           = ++_id
          item.karyawan     = item['nama_lengkap']
          item.total_gaji   = item['gaji']
          item.deskripsi    = item['desc']
          detailArr.value.push(item)
        })
        
      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          text: err
        })
      }
      
}
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
}

function openSub(i) {
  titleOpen.value = detailArr.value[i]?.nama_lengkap ?? '-'
  let dataFormat =  detailArr.value[i]?.gaji_detail ?? []

  detailArrOpen.items = dataFormat
  modalOpen.value = true
}

function closeModal(i) {
  modalOpen.value = false
}
// function onBack() {
//   let isChanged = false
//   for (const key in initialValues) {
//     if (values[key] !== initialValues[key]) {
//       isChanged = true
//       break;
//     }
//   }

//   if (!isChanged) {
//     router.replace('/' + modulPath)
//     return
//   }

//   swal.fire({
//     icon: 'warning',
//     text: 'Buang semua perubahan dan kembali ke list data?',
//     showDenyButton: true
//   }).then((res) => {
//     if (res.isConfirmed) {
//       router.replace('/' + modulPath)
//     }
//   })
// }


// const landing = reactive({
//   actions: [
//     {
//       icon: 'trash',
//       class: 'bg-red-600 text-light-100',
//       title: "Hapus",
//       // show: () => store.user.data.username==='developer',
//       click(row) {
//         swal.fire({
//           icon: 'warning',
//           text: 'Hapus Data Terpilih?',
//           confirmButtonText: 'Yes',
//           showDenyButton: true,
//         }).then(async (result) => {
//           if (result.isConfirmed) {
//             try {
//               const dataURL = `${store.server.url_backend}/operation${endpointApi}/${row.id}`
//               isRequesting.value = true
//               const res = await fetch(dataURL, {
//                 method: 'DELETE',
//                 headers: {
//                   'Content-Type': 'Application/json',
//                   Authorization: `${store.user.token_type} ${store.user.token}`
//                 }
//               })
//               if (!res.ok) throw new Error("Failed when trying to remove data")
//               apiTable.value.reload()
//               // const resultJson = await res.json()
//             } catch (err) {
//               isBadForm.value = true
//               swal.fire({
//                 icon: 'error',
//                 text: err
//               })
//             }
//             isRequesting.value = false
//           }
//         })
//       }
//     },
//     {
//       icon: 'eye',
//       title: "Read",
//       class: 'bg-green-600 text-light-100',
//       // show: (row) => (currentMenu?.can_read)||store.user.data.username==='developer',
//       click(row) {
//         router.push(`${route.path}/${row.id}?`+tsId)
//       }
//     },
//     {
//       icon: 'edit',
//       title: "Edit",
//       class: 'bg-blue-600 text-light-100',
//       // show: (row) => (currentMenu?.can_update)||store.user.data.username==='developer',
//       click(row) {
//         router.push(`${route.path}/${row.id}?action=Edit&`+tsId)
//       }
//     },
//     {
//       icon: 'copy',
//       title: "Copy",
//       class: 'bg-gray-600 text-light-100',
//       click(row) {
//         router.push(`${route.path}/${row.id}?action=Copy`+tsId)
//       }
//     }
//   ],
//   api: {
//     url: `${store.server.url_backend}/operation${endpointApi}`,
//     headers: {
//       'Content-Type': 'Application/json',
//       authorization: `${store.user.token_type} ${store.user.token}`
//     },
//     params: {
//       simplest: true
//     },
//     onsuccess(response) {
//       response.page = response.current_page
//       response.hasNext = response.has_next
//       return response
//     }
//   },
//   columns: [{
//     headerName: 'No',
//     valueGetter: (params) => params.node.rowIndex + 1,
//     width: 60,
//     sortable: true,
//     resizable: true,
//     filter: true,
//     cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
//   },
//   {
//     field: 'modul',
//     filter: true,
//     sortable: true,
//     flex:1,
//     filter: 'ColFilter',
//     resizable: true,
//     cellClass: [ 'border-r', '!border-gray-200', 'justify-center']
//   },
//   {
//     field: 'submodul',
//     filter: true,
//     sortable: true,
//     flex:1,
//     filter: 'ColFilter',
//     resizable: true,
//     cellClass: [ 'border-r', '!border-gray-200', 'justify-center']
//   },
//   {
//     field: 'menu',
//     filter: true,
//     sortable: true,
//     filter: 'ColFilter',
//     resizable: true,
//     flex:1,
//     cellClass: [ 'border-r', '!border-gray-200', 'justify-center']
//   },
//   {
//     field: 'path',
//     filter: true,
//     sortable: true,
//     filter: 'ColFilter',
//     resizable: true,
//     flex:1,
//     cellClass: [ 'border-r', '!border-gray-200', 'justify-center']
//   },
//   {
//     field: 'endpoint',
//     filter: true,
//     sortable: true,
//     filter: 'ColFilter',
//     resizable: true,
//     flex:1,
//     cellClass: [ 'border-r', '!border-gray-200', 'justify-center']
//   },
//   {
//     field: 'icon',
//     filter: true,
//     sortable: true,
//     filter: 'ColFilter',
//     resizable: true,
//     flex:1,
//     cellClass: [ 'border-r', '!border-gray-200', 'justify-center']
//   },
//   {
//     field: 'sequence',
//     filter: true,
//     sortable: true,
//     filter: 'ColFilter',
//     resizable: true,
//     flex:1,
//     cellClass: [ 'border-r', '!border-gray-200', 'justify-center']
//   },
//   {
//     field: 'is_active',
//     filter: true,
//     // resizable: true,
//     // valueGetter: (p) => p.node.data['status'].toLowerCase()==='active'? 'Aktif':'Tidak Aktif',
//     sortable: true,
//     flex:1,
//     cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
//     cellRenderer: ({ value }) => {
//       return value === true
//         ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>`
//         : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>`
//     }
//   },
//   ]
// })

onActivated(() => {
  //  reload table api landing
  if (apiTable.value) {
    if (route.query.reload) {
      apiTable.value.reload()
    }
  }
})

//  @endif -------------------------------------------------END
watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))