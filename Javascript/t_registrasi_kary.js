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
const endpointApi = '/m_kary'
onBeforeMount(()=>{
  document.title = 'Registrasi Karyawan'
})
// const formatCurrency = (text) => {
//   if (!text) text = 0

//     const formatter = new Intl.NumberFormat('id', {
//       style: 'currency',
//       currency: 'IDR',
//       maximumFractionDigits: 0,
//     })

//     if (typeof text === 'string') {
//       if (isNaN(parseFloat(text)) || isNaN(parseInt(text))) {
//         return formatter.format(0)
//       }

//       if (text.includes(',') || text.includes('.')) {
//         return formatter.format(parseFloat(text))
//       }

//       return formatter.format(parseInt(text))
//     }

//     return formatter.format(text)
// }
//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
let initialValues2 = {}
let initialValues3 = {}
const changedValues = []

const values = reactive({
})





onBeforeMount(async () => {
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

      const dataURL3 = `${store.server.url_backend}/operation/t_pelamar/${initialValues.ref_id}`
      const params3 = { join: true, transform: false }
      const fixedParams3 = new URLSearchParams(params3)
      const res3 = await fetch(dataURL3 + '?' + fixedParams3, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })
      if (!res3.ok) throw new Error("Failed when trying to read data")
      const resultJson3 = await res3.json()
      initialValues3 = resultJson3.data
      initialValues3.t_pelamar_id = initialValues3.id
      console.log(initialValues3)
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

  for (const key in initialValues3) {
    values[key] = initialValues3[key]
  }
  if(values.t_pelamar_id && values.m_dir_id){
    getNilai()
  }
})

// let _id = 0
// const detailArr = ref([])
// const addDetail = () => {
//   const tempItem = {
//     __id : ++_id,
//     komponen: values.komponen,
//     biaya: values.biayaKomp,
//     catatan_kom: values.catatanKomp
//   }
//   detailArr.value = [...detailArr.value, tempItem]
//   values.komponen= null
//   values.biayaKomp = null
//   values.catatanKomp = null
// }

const getNilai = async () => {
  const dataURL2 = `${store.server.url_backend}/operation/t_hasil_tes`
  isRequesting.value = true

  const params2 = { join: false, transform: false, where: `t_pelamar_id=${values.t_pelamar_id??0} AND m_dir_id=${values.m_dir_id}` }
  const fixedParams2 = new URLSearchParams(params2)
  const res2 = await fetch(dataURL2 + '?' + fixedParams2, {
    headers: {
      'Content-Type': 'Application/json',
      Authorization: `${store.user.token_type} ${store.user.token}`
    },
  })
  if (!res2.ok) throw new Error("Failed when trying to read data")
  const resultJson2 = await res2.json()
  initialValues2 = resultJson2.data[resultJson2.data.length - 1]
  if(initialValues2?.nilai_tes){
    values.nilai_tes = initialValues2.nilai_tes 
  }
  isRequesting.value = false
}

function onPostedKary(typePar) {
  const payload = {
    id: values.t_pelamar_id,
    status: typePar === 'konfirmasi' ? 'KONFIRMASI': 'TOLAK'
  };
  console.log(payload)
  swal.fire({
    icon: 'warning',
    text: typePar === 'konfirmasi' ? 'Konfirmasi Data Pelamar?' :  'Tolak Data Pelamar?',
    showDenyButton: true,
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const dataURL = `${store.server.url_backend}/operation/m_kary/postKaryawan`;
        isRequesting.value = true;
        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`,
          },
          body: JSON.stringify(payload),
        });
        if (!res.ok) {
          const responseJson = await res.json();
          if ([400, 422, 500].includes(res.status)) {
            formErrors.value = responseJson.errors || {};
            if (res.status === 422) {
              throw (responseJson.message + " Pastikan anda sudah mengisi semua kolom dengan tanda bintang merah");
            }
            throw (responseJson.message || "Failed when trying to post data");
          } else {
            throw ("Failed when trying to post data");
          }
        } else {
          // Success case
          swal.fire({
            icon: 'success',
            text: 'Proses berhasil',
          });
          router.replace('/'+ modulPath);
        }
      } catch (err) {
        isBadForm.value = true;
        swal.fire({
          icon: 'error',
          text: err.message || 'Failed when trying to post data',
        });
      } finally {
        isRequesting.value = false;
      }
    }
  });
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

function onSave() {
  //values.tags = JSON.stringify(values.tags)
  swal.fire({
    icon: 'warning',
    text: 'Save data?',
    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        values.is_active=(values.is_active===true)?1:0
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
              if (!res.ok){ 
                const resultJson = await res.json()
                throw (resultJson.message || "Failed when trying to remove data")
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
    // {
    //   icon: 'edit',
    //   title: "Edit",
    //   class: 'bg-blue-600 text-light-100',
    //   // show: (row) => (currentMenu?.can_update)||store.user.data.username==='developer',
    //   click(row) {
    //     router.push(`${route.path}/${row.id}?action=Edit&`+tsId)
    //   }
    // },
    // {
    //   icon: 'copy',
    //   title: "Copy",
    //   class: 'bg-gray-600 text-light-100',
    //   click(row) {
    //     router.push(`${route.path}/${row.id}?action=Copy`+tsId)
    //   }
    // }
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield:'this.id, this.nik, this.nama_lengkap, m_dept.nama,  this.alamat_domisili, this.no_tlp, this.is_active',
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
    field: 'nik',
    headerName:'NIK',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Nama',
    field: 'nama_lengkap',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Departemen',
    field: 'm_dept.nama',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Alamat',
    field: 'alamat_domisili',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Telepon',
    field: 'no_tlp',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Status',
    field: 'is_active',
    filter: true,
    // resizable: true,
    // valueGetter: (p) => p.node.data['status'].toLowerCase()==='active'? 'Aktif':'Tidak Aktif',
    sortable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
      return value === true
        ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>`
        : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>`
    }
  },
  ]
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