//   javascript//   javascript

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
let trx_dtl = reactive({items: []})
let detailKey = ref(0)
let modalOpen = ref(false)
let detailIdxSelected = ref(0)
let trx_dtl_sub = reactive({items: []})
let _id = ref(0) 

// ------------------------------ PERSIAPAN
const endpointApi = '/t_jadwal_kerja'
onBeforeMount(()=>{
  document.title = 'Jadwal Kerja'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const values = reactive({
   status: 'DRAFT',
})

onBeforeMount(async () => {
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { join: true }
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
      trx_dtl.items = resultJson.data?.t_jadwal_kerja_det_hari
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

  values?.generate_num_det.forEach((v,i)=>{
    v._id = i++
  })
})
function log(v){
  console.log(v)
}
async function generate(status = false) {
  // if(!values.tipe_jam_kerja_id){
  //   swal.fire({
  //     icon: 'warning',
  //     text: `Pilih tipe jam kerja terlebih dahulu`
  //   })
  //   return
  // }
  if(status){
    swal.fire({
      icon: 'warning',
      text: 'Reset Detail?',
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        trx_dtl.items = []
        getJamKerjaDefault()
      }
    })
  }else{
    getJamKerjaDefault()
  }
}


async function getJamKerjaDefault() {
  try {
    const response = await fetch(`${store.server.url_backend}/operation/t_jadwal_kerja/get_jam_kerja_default`, {
      method: 'GET',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },

      // ajarane sopo get ngirim body
      // body: JSON.stringify({
      //   where: `this.is_active='true' AND this.m_zona_id=${zonaId} AND this.grading_id=${gradingId}`,
      // })
    });

    if (!response.ok) {
      throw new Error('Coba kembali nanti');
    }

    const data = await response.json();
      
    let jamKerja = data?.data;
      
      trx_dtl.items = [
        {
          day : 'Senin',
          day_num : 1,
          m_jam_kerja_id: jamKerja?.id,
          tipe_hari: 'KERJA',
          waktu_mulai: jamKerja?.waktu_mulai,
          waktu_akhir: jamKerja?.waktu_akhir,
          
        },
        {
          day : 'Selasa',
          day_num : 2,
          m_jam_kerja_id: jamKerja?.id,
          tipe_hari: 'KERJA',
          waktu_mulai: jamKerja?.waktu_mulai,
          waktu_akhir: jamKerja?.waktu_akhir,
        },
        {
          day : 'Rabu',
          day_num : 3,
          m_jam_kerja_id: jamKerja?.id,
          tipe_hari: 'KERJA',
          waktu_mulai: jamKerja?.waktu_mulai,
          waktu_akhir: jamKerja?.waktu_akhir,
        },
        {
          day : 'Kamis',
          day_num : 4,
          m_jam_kerja_id: jamKerja?.id,
          tipe_hari: 'KERJA',
          waktu_mulai: jamKerja?.waktu_mulai,
          waktu_akhir: jamKerja?.waktu_akhir,
        },
        {
          day : 'Jumat',
          day_num : 5,
          m_jam_kerja_id: jamKerja?.id,
          tipe_hari: 'KERJA',
          waktu_mulai: jamKerja?.waktu_mulai,
          waktu_akhir: jamKerja?.waktu_akhir,
        },
        {
          day : 'Sabtu',
          day_num : 6,
          m_jam_kerja_id: jamKerja?.id,
          tipe_hari: 'KERJA',
          waktu_mulai: jamKerja?.waktu_mulai,
          waktu_akhir: jamKerja?.waktu_akhir,
        },
        {
          day : 'Minggu',
          day_num : 7,
          m_jam_kerja_id: jamKerja?.id,
          tipe_hari: 'LIBUR',
          waktu_mulai: jamKerja?.waktu_mulai,      
          waktu_akhir: jamKerja?.waktu_akhir,      
        }  
      ]

    console.log(trx_dtl.items)
  } catch (error) {
    console.error('Error fetching tunjangan kemahalan:', error);

  }
}

function addRow() {
  const newItem = { 
    _id: ++_id.value,
  }
  trx_dtl.items.push(newItem)
}

function deleteAll(item) {
  swal.fire({
    icon: 'warning',
    text: 'Hapus semua detail susunan?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      trx_dtl.items = []
    }
  })
}
function deleteDetail(item) {
  swal.fire({
    icon: 'warning',
    text: 'Hapus baris ini?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      trx_dtl.items = trx_dtl.items.filter((e) => e._id != item._id)
    }
  })
}

function onBack() {
  let isChanged = false
  for (const key in initialValues) {
    if (values[key] !== initialValues[key]) {
      isChanged = true
      break;
    }
  }

  if (!isChanged) {
    router.replace('/' + modulPath)
    return
  }

      router.replace('/' + modulPath)
}

function move() {
   router.replace('/t_jadwal_kerja_det/'+route.params.id)
}

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

async function onSave() {
  let next = true
  if(!trx_dtl.items.length) 
    return swal.fire({
      icon: 'warning',
      text: `Detail tidak boleh kosong`
    })
  if(!next) return

  trx_dtl.items.forEach((v,i)=>{
    v.seq = i+1
  })
  // merging detail data
  values['t_jadwal_kerja_det_hari'] = trx_dtl.items
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
            throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
          } else {
            throw ("Failed when trying to post data")
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

//  @else----------------------- LANDING
function openReport() {
  router.replace('/l_jadwal_kerja?menu_click=true')
}
const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status?.toUpperCase() == 'DRAFT',
      click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Hapus Data Terpilih?',
          confirmButtonText: 'Yes',
          showDenyButton: true,
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation${endpointApi}/${row.id}`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'DELETE',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                }
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
              apiTable.value.reload()
              // const resultJson = await res.json()
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
    },
    {
      icon: 'eye',
      title: "Read",
      class: 'bg-green-600 text-light-100',
      // show: (row) => (currentMenu?.can_read)||store.user.data.username==='developer',
      click(row) {
        router.push(`${route.path}/${row.id}?`+tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row.status?.toUpperCase() == 'DRAFT',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&`+tsId)
      }
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&`+tsId)
      }
    },
    {
      icon: 'tools',
      show: (row) => row.status?.toUpperCase() != 'EXPIRED' && row['tipe_jam_kerja.code'] != 'OFFICE',
      title: "Sesuaikan Jadwal",
      class: 'bg-blue-400 text-light-100',
      click(row) {
        router.replace(`/t_jadwal_kerja_det/${row.id}?${tsId}`)
      }
    },
    {
      icon: 'times',
      title: "Nonaktifkan Jadwal",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status?.toUpperCase() === 'POSTED' ,
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Proses Data Ini?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',

          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/t_jadwal_kerja/expired`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                body: JSON.stringify({ id: row.id })
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
              apiTable.value.reload()
              // const resultJson = await res.json()
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
    },
    {
      icon: 'location-arrow',
      title: "Post Data",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status?.toUpperCase() === 'DRAFT' ,
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Post Data?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',

          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/t_jadwal_kerja/post`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'POST',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                body: JSON.stringify({ id: row.id })
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
              apiTable.value.reload()
              // const resultJson = await res.json()
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
    }
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield:'this.id, this.nama, this.is_active',
    },
    onsuccess(response) {
      response.page = response.current_page
      response.hasNext = response.has_next
      return response
    }
  },
  columns: [{
    headerName: 'No',
    valueGetter: (params) => params.node.rowIndex + 1,
    width: 60,
    sortable: true,
    resizable: true,
    filter: true,
    cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
  },
  {
    field: 'nomor',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Tipe Jam Kerja',
    field: 'tipe_jam_kerja.value',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'keterangan',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'status',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
      let color = 'gray'
      color = value == 'POSTED' ? 'green' : (value == 'EXPIRED' ? 'red' : 'gray')
      return value === 'POSTED'
        ? `<span class="text-${color}-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${value}</span>`
        : `<span class="text-${color}-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${value}</span>`
    }
  }]
})
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