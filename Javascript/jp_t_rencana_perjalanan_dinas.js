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
const is_approval = route.query.is_approval ? true : false
const is_to_upload = route.query.is_to_upload ? true : false
let isApproved = ref(false)
let modalOpen = ref(false)
let isFinish = ref(false)

// ------------------------------ PERSIAPAN
const endpointApi = '/t_spd'
onBeforeMount(()=>{
  document.title = 'Rencana Perjalanan Dinas'
})

const formatCurrency = (text) => {
  if (!text) text = 0

    const formatter = new Intl.NumberFormat('id', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    })

    if (typeof text === 'string') {
      if (isNaN(parseFloat(text)) || isNaN(parseInt(text))) {
        return formatter.format(0)
      }

      if (text.includes(',') || text.includes('.')) {
        return formatter.format(parseFloat(text))
      }

      return formatter.format(parseInt(text))
    }

    return formatter.format(text)
}
//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []
const values = reactive({
  is_active: true,
  is_kend_dinas:false,
  tanggal: new Date().toLocaleDateString('en-GB'),
  tgl_acara_awal: new Date().toLocaleDateString('en-GB')
  // direktorat: store.user.data?.direktorat
})

onBeforeMount(async () => {
  if (isRead) {
    //  READ DATA
    try {
      // if(route.query.is_approval){

      // }
      let dataURL = ''
      let dataURLAprv = ''
      let resAprv = ''
      if (route.query.is_approval) {
        dataURLAprv = `${store.server.url_backend}/operation/t_spd/detail?id=${route.params.id}`
        isRequesting.value = true
        const apiApp = await fetch(dataURLAprv, {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        const resultJson = await apiApp.json()
        console.log(resultJson.data)
        const apiTrx = await fetch(`${store.server.url_backend}/operation${endpointApi}/${resultJson.data.approval.trx_id}`, {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!apiTrx.ok || !apiApp.ok) throw new Error("Failed when trying to read data")
        const resultTrxJson = await apiTrx.json()
        values.approval = resultJson?.data.approval
        values.trx = resultJson?.data.trx
        values.datalog = resultJson?.data.approval_log
        initialValues = resultTrxJson.data
        initialValues.t_spd_det?.forEach((items)=>{
          items.__id = ++_id
          detailArr.value = [items, ...detailArr.value]
        })
        if(initialValues.tgl_acara_akhir){
          initialValues.jml_hari = initialValues.tgl_acara_akhir.split('/')[0] - initialValues.tgl_acara_awal.split('/')[0]
        }
        // logic finish & Approved data
        isApproved.value = resultTrxJson?.data?.cuti_status == 'APPROVED' ? true : false
        isFinish.value = resultJson?.data?.approval?.tahap_saat_ini == resultJson?.data?.approval?.tahap_total ? true : false
      } else {
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
        initialValues.t_spd_det?.forEach((items)=>{
          items.__id = ++_id
          detailArr.value = [items, ...detailArr.value]
        })
        if(initialValues.tgl_acara_akhir){
          initialValues.jml_hari = initialValues.tgl_acara_akhir.split('/')[0] - initialValues.tgl_acara_awal.split('/')[0]
        }
      }
      // initialValues.status=(initialValues.status.toUpperCase()==='IN APPROVAL')?true:false
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

const cekTgl = (tanggal)=>{
  if(tanggal < values.tgl_acara_awal){
    swal.fire({
      icon: 'warning',
        iconColor: '#1469AE',
        confirmButtonColor: '#1469AE',
        text: 'Tidak Boleh Lebih Kecil Dari Tanggal Awal',
    })
    values.tgl_acara_akhir = ''
  }
}
const hitungHari = () => {
  if(values.tgl_acara_awal){
    if(values.tgl_acara_akhir){
      // console.log(values.tgl_acara_awal.split('/')[0])
      values.jml_hari = values.tgl_acara_akhir.split('/')[0] - values.tgl_acara_awal.split('/')[0]
    }else{
      values.jml_hari = values.tgl_acara_awal.split('/')[0]
    }
  }else{
    values.jml_hari = values.tgl_acara_akhir.split('/')[0]
  }
}

const countBiaya = () =>{
  const total = detailArr.value.reduce((acm, item) => {
    return acm + item.biaya;
  }, 0);
  values.total_biaya = total
}

const getDetailSPD = async () => {
  try{
    detailArr.value = []
    isRequesting.value = true
    const res3 = await fetch(`${store.server.url_backend}/operation/m_spd/${values.m_spd_id}`, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    })
    if (!res3.ok) throw ("Gagal Mendapatkan Data Detail SPD")
    const resultJson3 = await res3.json()
    // console.log(resultJson3)
    resultJson3.data.m_spd_det_biaya?.forEach(async (item) => {
      // console.log('cok',item)
      const tempObItem = {
        __id: ++_id,
        tipe_spd_id: item.tipe_id,
        biaya: item.total_biaya,
        keterangan: item.keterangan
      }
      detailArr.value = [...detailArr.value, tempObItem]
    })
  }catch(err){
      isBadForm.value = true
      swal.fire({
        icon: 'error',
        text: err,
      })
    }
    isRequesting.value = false
  
}

    // tipe_spd_id: null,
    // biaya: null,
    // biaya_realisasi: 0,
    // detail_transport: {},
    // m_knd_dinas_id: 0,
    // is_kendaraan_dinas: true,
    // keterangan: null,
    // catatan_realisasi: null,
    // is_now: 0,
let _id = 0
const detailArr = ref([])
const addDetail = () => {
  const tempItem = {
    __id : ++_id,
  }
  detailArr.value = [...detailArr.value, tempItem]
}

function openModal(id) {
  dataLog.items = []
  modalOpen.value = true
  loadLog(id)
}

function closeModal(i) {
  dataLog.items = []
  modalOpen.value = false
}

let dataLog = reactive({items:[]})
async function loadLog(id) {
  const url = `${store.server.url_backend}/operation/t_spd/log?id=${id}`
  const res = await fetch(url, {
    headers: {
      'Content-Type': 'Application/json',
      Authorization: `${store.user.token_type} ${store.user.token}`
    },
  })
  if (!res.ok) throw new Error("Failed when trying to read data")
  const result = await res.json()
  dataLog.items = result
}

function onProcess(typePar) {
  const payload = {
    id: route.params.id,
    type: typePar === 'revise' ? 'REVISED' : (typePar === 'reject' ? 'REJECTED' : 'APPROVED'),
    note: values.catatan,
  };

  swal.fire({
    icon: 'warning',
    text: typePar === 'revise' ? 'Revised data?' : (typePar === 'reject' ? 'Rejected data?' : 'Approved data?'),
    showDenyButton: true,
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        // if(typePar === 'REVISED'){
        //   console.log(!payload.note)
        //   if(!payload.note) {
        //     swal.fire({
        //       icon: 'warning',
        //       text: "Catatan wajib diisi",
        //     });
        //     return  
        //   }
        // }
        const dataURL = `${store.server.url_backend}/operation/t_spd/progress`;
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
          router.replace('/notifikasi');
        }
      } catch (err) {
        isBadForm.value = true;
        swal.fire({
          icon: 'error',
          text: err || 'Failed when trying to post data',
        });
      } finally {
        isRequesting.value = false;
      }
    }
  });

  if (route.params.id === 'create') {
    activeTabIndex = 0;
  }
}

function onBack() {
  if (!is_approval) {
    router.replace('/' + modulPath)
  } else {
    router.replace('/notifikasi')
  }
  return
}

const removeDetail = (detailItem) => {
  values.total_biaya -= detailItem.biaya
  detailArr.value = detailArr.value.filter((e) => e.__id != detailItem.__id)
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
  if (values.tgl_acara_akhir < values.tgl_acara_awal) {
    swal.fire({
      icon: 'warning',
      iconColor: '#1469AE',
      confirmButtonColor: '#1469AE',
      text: 'Tanggal Akhir Tidak Boleh Lebih Kecil Dari Tanggal Awal',
    });
    formErrors.value.tgl_acara_akhir = ['Tidak boleh lebih kecil dari tanggal awal'];
    return;
  }

  let shouldStop = false;
  detailArr.value.forEach((item, i) => {
    if (item.biaya.toString().length >= 12) {
      shouldStop = true;
      swal.fire({
        icon: 'warning',
        text: `Detail Biaya baris ${i + 1}, Tidak Boleh Lebih Dari 1 Triliun`,
      });
      return;
    }
  });

  if (!shouldStop) {
    try {
      if (values.status === 'REVISED') {
        values.status = 'DRAFT'; // Change status to DRAFT if it was REVISED
      }

      values.t_spd_det = detailArr.value;
      values.is_kend_dinas = values.is_kend_dinas ? 1 : 0;
      
      const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
      const dataURL = `${store.server.url_backend}/operation${endpointApi}${
        isCreating ? '' : '/' + route.params.id
      }`;

      isRequesting.value = true;

      const res = await fetch(dataURL, {
        method: isCreating ? 'POST' : 'PUT',
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`,
        },
        body: JSON.stringify(values),
      });

      if (!res.ok) {
        if ([400, 422].includes(res.status)) {
          const responseJson = await res.json();
          formErrors.value = responseJson.errors || {};
          throw responseJson.errors.length ? responseJson.errors[0] : responseJson.message || 'Failed when trying to post data';
        } else {
          throw 'Failed when trying to post data';
        }
      }

      router.replace('/' + modulPath + '?reload=' + Date.parse(new Date()));
    } catch (err) {
      isBadForm.value = true;
      swal.fire({
        icon: 'error',
        text: err,
      });
    } finally {
      isRequesting.value = false;
    }
  }
}

//  @else----------------------- LANDING
const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status?.toUpperCase() === 'DRAFT',
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
{
  icon: 'edit',
  title: "Edit",
  class: 'bg-blue-600 text-light-100',
  show: (row) => row.status?.toUpperCase() === 'DRAFT' || row.status?.toUpperCase() === 'REVISED',
  click(row) {
    router.push(`${route.path}/${row.id}?action=Edit&` + tsId);
  }
},
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      show: (row) => row.status?.toUpperCase() === 'DRAFT',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&`+tsId)
      }
    },
    {
      icon: 'location-arrow',
      title: "Send Approval",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status?.toUpperCase() === 'DRAFT' ,
      async click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Send Approval?',
          iconColor: '#1469AE',
          confirmButtonColor: '#1469AE',

          showDenyButton: true
        }).then(async (res) => {
          if (res.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation/t_spd/send_approval`
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
                if ([400, 422, 500].includes(res.status)) {
                  const responseJson = await res.json()
                  formErrors.value = responseJson.errors || {}
                  throw (responseJson.message+ " "+responseJson.data.errorText || "Failed when trying to post data")
                } else {
                  throw ("Failed when trying to post data")
                }
              }
              const responseJson = await res.json()
              swal.fire({
                icon: 'success',
                text: responseJson.message
              })
              // const resultJson = await res.json()
            } catch (err) {
              isBadForm.value = true
              swal.fire({
                icon: 'error',
                iconColor: '#1469AE',
                confirmButtonColor: '#1469AE',
                text: err
              })
            }
            isRequesting.value = false

            apiTable.value.reload()
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
      join: true,
      searchfield:'this.id, this.nomor,  m_kary.nik, this.tanggal, this.tgl_acara_awal, this.tgl_acara_akhir, m_zona_asal.nama, m_zona_tujuan.nama, this.status',
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
    wrapText: true,
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'NIK',
    field: 'm_kary.nik',
    wrapText: true,
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Nama',
    field: 'm_kary.nama_lengkap',
    wrapText: true,
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Tanggal Acara Awal',
    field: 'tgl_acara_awal',
    wrapText: true,
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Tanggal Acara Akhir',
    field: 'tgl_acara_akhir',
    wrapText: true,
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Zona Asal',
    field: 'm_zona_asal.nama',
    wrapText: true,
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Zona Tujuan',
    field: 'm_zona_tujuan.nama',
    wrapText: true,
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
 {
    headerName: 'Status',
    field: 'status',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
      let color = 'gray'
      if(value == 'APPROVED')
        color = 'green'
      else if(value == 'IN APPROVAL')
        color = 'blue'
      else if(value == 'REVISED')
        color = 'yellow'
      else if(value == 'REJECTED')
        color = 'red'
    return `<span class="text-${color}-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${value}</span>`
  }}
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