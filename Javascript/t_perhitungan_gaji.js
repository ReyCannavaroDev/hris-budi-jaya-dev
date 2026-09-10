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
const detailArr = ref([])
const titleOpen = ref('')
const formErrors = ref({})
const modalOpen = ref(false)
const detailArrOpen = reactive({items: []})
const tsId = `ts=`+(Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = '/t_perhitungan_gaji'
onBeforeMount(()=>{
  document.title = 'Perhitungan Gaji'
})

function openDetailFromLanding(row) {
  titleOpen.value = row['m_kary.nama_lengkap'] + ' - ' +row['m_kary.nik']
  let dataFormat =  row?.detail_gaji ?? []

  detailArrOpen.items = dataFormat
  modalOpen.value = true
}

function closeModal(i) {
  modalOpen.value = false
}

function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
}


//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []
let _id = 0


function formatDateToSlash(date) {
  const d = new Date(date);
  const day = String(d.getDate()).padStart(2, '0');
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const year = d.getFullYear();
  return `${day}/${month}/${year}`;
}
const values = reactive({
  periode_awal_form: formatDateToSlash(new Date()), 
  periode_akhir_form: formatDateToSlash(new Date(new Date().setDate(new Date().getDate() + 7))) 
});
// watchEffect(() => {
//   if (!values.periode_awal_form) {
//     values.periode_akhir_form = '';
//   } else {
//     const awalDate = new Date(values.periode_awal_form.split('/').reverse().join('-'));
//     const akhirDate = new Date(awalDate.setDate(awalDate.getDate() + 14)); 
//     values.periode_akhir_form = formatDateToSlash(akhirDate); 
//   }
// });

const formatDateToISO = (dateString) => {
  const [day, month, year] = dateString.split('/');
  return `${year}-${month}-${day}`;
};


// Validasi periode awal dan akhir
const checkTglStDate = (v) => {
  if (v) {
    values.periode_awal_form = v;
    console.log('tanggal awal', periode_awal_form)
    if (values.periode_akhir_form) {
      const startDate = new Date(values.periode_awal_form);
      const endDate = new Date(values.periode_akhir_form);

      if (endDate < startDate) {
        swal.fire({
          icon: 'warning',
          text: 'Tanggal Mulai tidak boleh melebihi Tanggal Selesai',
          showDenyButton: false
        });
      }
    }
  }
};

const checkTglEdDate = (v) => {
  if (v) {
    values.periode_akhir_form = v;
    console.log('tanggal awal', periode_akhir_form)
    if (values.periode_awal_form) {
      const startDate = new Date(values.periode_awal_form);
      const endDate = new Date(values.periode_akhir_form);

      if (endDate < startDate) {
        swal.fire({
          icon: 'warning',
          text: 'Tanggal Mulai tidak boleh melebihi Tanggal Selesai',
          showDenyButton: false
        });
      }
    }
  }
};




// Generate calculation and post to API
async function generatePerhitungan() {
  
  if (!values.periode_awal_form && !values.periode_akhir_form) {
    // Show loading SweetAlert for 3 seconds
    swal.fire({
      title: 'Please Wait',
      text: 'Memeriksa periode...',
      allowOutsideClick: false,
      didOpen: () => {
        swal.showLoading();  // Show loading spinner
      }
    });

    // Wait for 3 seconds before showing the error
    await new Promise(resolve => setTimeout(resolve, 3000));
    
    // Close the loading alert
    swal.close();

    // Show error alert
    swal.fire({
      icon: 'error',
      text: 'Wajib pilih periode awal dan akhir terlebih dahulu!'
    });
    return; // Exit the function
  }

  // Show loading SweetAlert before starting the data fetch
  swal.fire({
    title: 'Please Wait',
    text: 'Loading data...',
    allowOutsideClick: false,
    didOpen: () => {
      swal.showLoading();  // Show loading spinner
    }
  });
  values.is_tunjangan = values.is_tunjangan ? true : false
  values.tgl_awal = formatDateToISO(values.periode_awal_form);
  values.tgl_akhir = formatDateToISO(values.periode_akhir_form);  
  detailArr.value = [];

  try {
    
    const dataURL = `${store.server.url_backend}/operation/t_perhitungan_gaji/generate`;
    const res = await fetch(dataURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(values)  // Posting formatted dates
    });

    if (!res.ok) {
      const responseJson = await res.json();
      formErrors.value = responseJson.errors || {};
      throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data");
    }

    const result = await res.json();
    const resultData = result.data;

    // Close the loading alert once the data is fetched
    swal.close();

    // Handle cases where no data is found
    if (!resultData?.length) {
      swal.fire({
        icon: 'warning',
        text: "Tidak ditemukan data Perhitungan Gaji, pastikan karyawan sudah memiliki standar gaji"
      });
      return;
    }

    // Process the resultData and push it to detailArr
    resultData.forEach((item) => {
      item.id = ++_id;
      item.karyawan = item['nama_lengkap'];
      item.deskripsi = item['desc'];
      detailArr.value.push(item);
    });

    // Show success alert after data is successfully loaded
    swal.fire({
      icon: 'success',
      text: 'Data berhasil di-load!'
    });

  } catch (err) {
    // Close the loading alert if there's an error
    swal.close();
    isBadForm.value = true;

    // Show error alert
    swal.fire({
      icon: 'error',
      text: "Sesuatu yang salah terjadi, silahkan coba kembali nanti atau hubungi administrator"
    });
  }
}




function openDetail(i) {
  titleOpen.value = detailArr.value[i]?.nama_lengkap ?? '-' +' - ' +detailArr.value[i]?.nama_lengkap ?? ''
  let dataFormat =  detailArr.value[i]?.detail_gaji ?? []

  detailArrOpen.items = dataFormat
  modalOpen.value = true
}

onBeforeMount(async () => {
  console.log(values.periode_awal_form)
  // tampilkan default direktorat dengan store user comp.nama
  values.direktorat = store.user.data?.direktorat

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

function onBack() {
  if (route.query.view_gaji) {
    router.replace('/t_info_gaji')
  }else{
    router.replace('/' + modulPath)
  }
  return
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

   if (detailArr.value.length === 0) {
    swal.fire({
      icon: 'error',
      title : 'Oops..',
      text: "Silahkan generate data perhitungan gaji terlebih dahulu sebelum menyimpan."
    });
    return;
  }

  // merging detail data
    values['detail'] = detailArr._value
      try {
        const dataURL = `${store.server.url_backend}/operation/t_perhitungan_gaji/save`
        isRequesting.value = true

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
    // {
    //   icon: 'trash',
    //   class: 'bg-red-600 text-light-100',
    //   title: "Hapus",
    //   // show: () => store.user.data.username==='developer',
    //   click(row) {
    //     swal.fire({
    //       icon: 'warning',
    //       text: 'Hapus Data Terpilih?',
    //       confirmButtonText: 'Yes',
    //       showDenyButton: true,
    //     }).then(async (result) => {
    //       if (result.isConfirmed) {
    //         try {
    //             const dataURL = `${store.server.url_backend}/operation${endpointApi}/${row.id}`
    //             isRequesting.value = true
    //             const res = await fetch(dataURL, {
    //               method: 'DELETE',
    //               headers: {
    //                 'Content-Type': 'Application/json',
    //                 Authorization: `${store.user.token_type} ${store.user.token}`
    //               }
    //             })
    //             if (!res.ok) {
    //             const resultJson = await res.json()
    //             throw (resultJson.message || "Failed when trying to remove data")
    //           }
    //             apiTable.value.reload()
    //             const resultJson = await res.json()
    //           } catch (err) {
    //             isBadForm.value = true
    //             swal.fire({
    //               icon: 'error',
    //               text: err
    //             })
    //           }
    //           isRequesting.value = false
    //       }
    //     })
    //   }
    // },
    {
      icon: 'eye',
      title: "Read",
      class: 'bg-green-600 text-light-100',
      // show: (row) => (currentMenu?.can_read)||store.user.data.username==='developer',
      click(row) {
        openDetailFromLanding(row)
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
      where: `lower(this.type_perhitungan) != 'borongan'`,
      simplest: true,
      searchfield:'this.id, this.nomor, m_kary.nik, m_kary.nama_lengkap, m_kary_dir.nama, m_kary_divisi.nama, m_kary_dept.nama, this.periode, periode.value, this.netto',
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
    headerName: 'Nomor Generate',
    filter: true,
    wrapText: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start','w-[400px]']
  },
  {
    field: 'm_kary.nik',
    headerName: 'NIK',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'm_kary.nama_lengkap',
    headerName: 'Karyawan',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'm_kary_dept.nama',
    headerName: 'Departement',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'periode',
    headerName: 'Tgl Periode',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'periode.value',
    headerName: 'Periode Gaji',
    filter: true,
    wrapText: true,
    sortable: true,
    filter: 'ColFilter',
    flex:1,
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'netto',
    headerName: 'Gaji',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-end'],
    cellRenderer: ({ value }) => {
      return formatRupiah(value);
    },
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