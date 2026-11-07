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
const endpointApi = '/generate_num'
onBeforeMount(()=>{
  document.title = 'Format Nomor'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const values = reactive({
   is_active: true,
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
      const sortedData = resultJson.data?.generate_num_det.sort((a, b) => a.seq - b.seq);
      trx_dtl.items = sortedData
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

async function pratinjau() {
  const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
  const dataURL = `${store.server.url_backend}/operation${endpointApi}/tes_nomor`
  isRequesting.value = true
  values['detail'] = trx_dtl.items
  try{
    const res = await fetch(dataURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(values)
    })
    const responseJson = await res.json()
    if (!res.ok) {
      if ([400, 422, 500].includes(res.status)) {
      } else {
      }
    }
    values.pratinjau = responseJson.data ?? ''
  } catch (err) {
    isBadForm.value = true
    swal.fire({
      icon: 'error',
      text: err
    })
  }
  isRequesting.value = false
}

function moveUp(idx){
  if(idx <= 0) return
  
  // Remove the element from the old index
  const removedElement = trx_dtl.items.splice(idx, 1)[0]

  const newIndex = idx-1
  // Insert the element at the new index
  trx_dtl.items.splice(newIndex, 0, removedElement)
}
function moveDown(idx){
  if(idx >= (trx_dtl.items.length-1)) return
  
  // Remove the element from the old index
  const removedElement = trx_dtl.items.splice(idx, 1)[0]

  const newIndex = idx+1
  // Insert the element at the new index
  trx_dtl.items.splice(newIndex, 0, removedElement)
}

function addRow() {
  const newItem = { 
    _id: ++_id.value,
  }
  trx_dtl.items.push(newItem)
}

function openSub(i) {
  trx_dtl_sub.items = []
  total_sub.value = 0
  total_sub_text.value = 0
  modalOpen.value = true
  detailIdxSelected.value = i
  trx_dtl_sub.items = trx_dtl.items[i]['m_spd_det_transport'] ?? []
}

function addRowSub(i) {
  const newItem = { 
    _id: ++_id.value,
    zona_tujuan_id: null,
    jenis_transport_id: null,
    nama_transport: null,
    biaya_transport: null,
    keterangan: null
  }
  trx_dtl_sub.items.push(newItem)
}

const total_sub = ref(0)
const total_sub_text = ref(0)
function countSub() {
  const total = trx_dtl_sub.items.reduce((acm, item) => {
    return acm + item.biaya_transport;
  }, 0);
  total_sub.value = total
  total_sub_text.value = total.toLocaleString('id-ID', {
    style: 'currency',
    currency: 'IDR'
  });
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
      text: `Detail susunan tidak boleh kosong`
    })
  trx_dtl.items.forEach((item, i)=>{
    if(!item.generate_num_type_id){
      swal.fire({
        icon: 'warning',
        text: `Detail baris ${i+1}, Lengkapi kolom dengan tanda bintang merah`
      })
      next = false
      return
    }
  })
  if(!next) return

  trx_dtl.items.forEach((v,i)=>{
    v.seq = i+1
  })
  // merging detail data
  values['generate_num_det'] = trx_dtl.items
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
const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      // show: () => store.user.data.username==='developer',
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
      // show: (row) => (currentMenu?.can_update)||store.user.data.username==='developer',
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
    field: 'nama',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Status',
    field: 'is_active',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>`
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