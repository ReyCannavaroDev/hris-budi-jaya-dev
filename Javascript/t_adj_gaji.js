//   javascript//   javascript

import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated, computed } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Tambah' : (route.query.action?.toLowerCase() === 'verifikasi' ? null : route.query.action))
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const detailArr = ref([])
const titleOpen = ref('')
const formErrors = ref({})
const modalOpen = ref(false)
const detailArrOpen = reactive({ items: [] })
const detailArrAdjOpen = reactive({ items: [] })
let objectOpen = reactive({ items: 0 })
let idxOpen = reactive({ value: null })
let totalAdjOpen = reactive({ value: 0 })
let totalAdjPPHOpen = reactive({ value: 0 })
let totalAdjFinalOpen = reactive({ value: 0 })
const tsId = `ts=` + (Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = '/t_final_gaji'
onBeforeMount(() => {
  document.title = 'Finalisasi Gaji'
})

function formatRupiah(amount) {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
}


//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []
let _id = 0
let thisMonth = new Date().toISOString().split('T')[0]
let tempYear = thisMonth.split('-')[0]
let tempMonth = thisMonth.split('-')[1]


// Tanggal hari ini
const today = new Date();
const day = String(today.getDate()).padStart(2, '0'); // Menambahkan 0 di depan jika < 10
const month = String(today.getMonth() + 1).padStart(2, '0'); // Menambahkan 0 di depan jika < 10
const year = today.getFullYear();
const formattedDate = `${year}-${month}-${day}`;

// Menambahkan 14 hari ke tanggal hari ini
const nextWeek = new Date(today);
nextWeek.setDate(today.getDate() + 13);
const nextDay = String(nextWeek.getDate()).padStart(2, '0'); // Menambahkan 0 di depan jika < 10
const nextMonth = String(nextWeek.getMonth() + 1).padStart(2, '0'); // Menambahkan 0 di depan jika < 10
const nextYear = nextWeek.getFullYear();
const dayNext = `${nextYear}-${nextMonth}-${nextDay}`;


const values = reactive({
  total_pengeluaran_gaji: 0,
  periode_awal: formattedDate,
  periode_akhir: dayNext,
  type_perhitungan: 'HARIAN'
})


const checkTglStDate = (v) => {
  if (v !== null) {
    const [day, month, year] = v.split('/').map(Number);
    values.periode_awal = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

    if (values.periode_akhir) {
      const startDate = new Date(year, month - 1, day); // Konversi tanggal awal
      const [endYear, endMonth, endDay] = values.periode_akhir.split('-').map(Number);
      const endDate = new Date(endYear, endMonth - 1, endDay); // Konversi tanggal akhir

      if (endDate < startDate) {
        swal.fire({
          icon: 'warning',
          text: 'Tanggal Mulai tidak boleh melebihi Tanggal Selesai',
          showDenyButton: false,
        }).then((res) => {
          if (res.isConfirmed) {
            values.periode_awal = formattedDate;
            values.periode_akhir = dayNext;
          }
        });
      } else {
        // Tambahkan 13 hari ke tanggal awal
        const resultDate = new Date(startDate);
        resultDate.setDate(startDate.getDate() + 13);

        const resultDay = String(resultDate.getDate()).padStart(2, '0');
        const resultMonth = String(resultDate.getMonth() + 1).padStart(2, '0');
        const resultYear = resultDate.getFullYear();

        values.periode_akhir = `${resultYear}-${resultMonth}-${resultDay}`;
      }
    }
  }
};

const checkTglEdDate = (v) => {
  if (!v) return;

  const [day, month, year] = v.split('/').map(Number);
  values.periode_akhir = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
};




function closeModal() {
  detailArrOpen.items = []
  modalOpen.value = false
}

function openDetail(i) {
  idxOpen.value = i;
  detailArrAdjOpen.items = [];
  objectOpen.items = {
    ...detailArr.value[i],  // Ambil seluruh data dari detailArr sesuai index
    netto: detailArr.value[i].netto  // Tambahkan netto agar tersedia di objectOpen.items
  };

  // console.log(`Index ${i} - Netto:`, objectOpen.items.netto);

  titleOpen.value = (detailArr.value[i]['m_kary.nama_lengkap'] ?? '-') +
    ' - ' +
    (detailArr.value[i]['m_kary.nik'] ?? '');

  let dataFormat = detailArr.value[i]?.detail_gaji?.slice() ??
    detailArr.value[i].t_final_gaji_det_rincian?.sort((a, b) => a.seq - b.seq);
    
    console.log('cek',dataFormat)
  // Tambahkan netto ke setiap item dalam dataFormat
  dataFormat = dataFormat.map(item => ({
    ...item,
    netto: detailArr.value[i].netto
  }));

  // Log dataFormat setelah netto ditambahkan
  console.log('dataFormat with netto:', dataFormat);

  // Ambil dari detail_adj jika sudah ada
  detailArrOpen.items = dataFormat;
  dataFormat = detailArr.value[i]?.detail_adj?.length ? detailArr.value[i]?.detail_adj : dataFormat;

  dataFormat.filter(item => item.can_adjust == 1).forEach((v) => {
    const formattedValue = parseFloat(v.value);
    detailArrAdjOpen.items.push({
      ...v,
      default: v.default ?? true,
      type: v.type,
      value_ref: formattedValue,
      value: formattedValue,
      netto: objectOpen.items.netto // Tambahkan netto ke setiap item
    });
  });

  summaryAdj();
  generatePPH(false);

  modalOpen.value = true;
}

function addRowAdj() {
  detailArrAdjOpen.items.push({
    _id: ++_id,
    label: '',
    name: '',
    can_adjust: 1,
    default: false,
    factor: '+',
    type: 'Bulanan',
    value_ref: null,
    value: 0
  })
}

const grandTotalAdj = ref(0); // Tambahkan ini

function summaryAdj() {
  totalAdjPPHOpen.value = []
  totalAdjOpen.value = 0

  detailArrAdjOpen.items.forEach((v) => {
    if (v.factor == '-') {
      totalAdjOpen.value -= Number(v.value ?? 0)
    } else if (v.factor == '+') {
      totalAdjOpen.value += Number(v.value ?? 0)
    }
  })

  totalAdjFinalOpen.value = totalAdjOpen.value

  // Hitung Grand Total ADJ (Total Penyesuaian - PPH 21)
  grandTotalAdj.value = totalAdjFinalOpen.value - (totalAdjPPHOpen.value.length ? totalAdjPPHOpen.value[0].value : 0);

  summaryPengeluaranGaji()
}

onBeforeMount(async () => {
  // tampilkan default direktorat dengan store user comp.nama
  values.direktorat = store.user.data?.direktorat

  if (isRead) {
    //  READ DATA
    swal.fire({
      title: 'Memuat Data',
      text: 'Mohon Tunggu sebentar kami sedang menyiapkan data anda',
      icon: 'info',
      showConfirmButton: false,
      allowOutsideClick: false,
      willOpen: () => {
        swal.showLoading();
      },
    });
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { transform: false }
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
      let tempYear2 = initialValues.periode_awal?.split('-')[0]
      let tempMonth2 = initialValues.periode_awal?.split('-')[1]
      let tempDay2 = initialValues.periode_awal?.split('-')[2]
      initialValues.periode_awal = tempYear2 + '-' + tempMonth2 + '-' + tempDay2

      let tempYear3 = initialValues.periode_akhir?.split('-')[0]
      let tempMonth3 = initialValues.periode_akhir?.split('-')[1]
      let tempDay3 = initialValues.periode_akhir?.split('-')[2]
      initialValues.periode_akhir = tempYear3 + '-' + tempMonth3 + '-' + tempDay3
      detailArr.value = initialValues.t_final_gaji_det?.sort((a, b) => a.id - b.id)

      console.log(initialValues.t_final_gaji_det)


      detailArr.value.forEach((item, index) => {
        console.log(`Index ${index}: ${item.netto}`);
      });




      console.log('cek All data', initialValues)
      detailArr.value.forEach((items) => {
        // console.log(items)
        items.karyawan = items['m_kary.nama_lengkap']
      })

      swal.close();
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
  } else {
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


async function generatePPH(popup = true) {
  try {
    const dataURL = `${store.server.url_backend}/operation/t_perhitungan_gaji/generatePPH`
    const params = {
      m_kary_id: objectOpen.items['m_kary_id'],
      netto: totalAdjOpen.value,
    }
    const fixedParams = new URLSearchParams(params)
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      }
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
    if (result.length) {
      totalAdjPPHOpen.value = result
      totalAdjFinalOpen.value = totalAdjOpen.value - totalAdjPPHOpen.value[0]?.value ?? 0
    } else {
      totalAdjPPHOpen.value = 0;
      if (!popup) return
      swal.fire({
        icon: 'warning',
        text: 'Total Gaji berdasarkan jenis tanggungan dibawah standar minimun perhitungan PPH'
      })
    }

  } catch (err) {
    isBadForm.value = true
    swal.fire({
      icon: 'error',
      text: err
    })
  }
}



const searchQuery = ref('');


async function generatePerhitungan() {
  detailArr.value = [];

  // SweetAlert loading notification
  swal.fire({
    title: 'Memuat Data',
    text: 'Silahkan tunggu sebentar kami sedang mengambil data Anda',
    icon: 'info',
    showConfirmButton: false,
    allowOutsideClick: false,
    willOpen: () => {
      swal.showLoading();
    },
  });

  try {
    const dataURL = `${store.server.url_backend}/operation/t_perhitungan_gaji`;
    const params = {
      scopes: 'GenerateForFinal',
      type_perhitungan: values.type_perhitungan,
      periode_awal: values.periode_awal,
      periode_akhir: values.periode_akhir,
      date_from: values.periode_awal,
      date_to: values.periode_akhir,
      m_divisi_id: values.m_divisi_id,
      m_dept_id: values.m_dept_id,
      paginate: 9999,
    };
    const fixedParams = new URLSearchParams(params);
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });

    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json();
        formErrors.value = responseJson.errors || {};
        throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data");
      } else {
        throw ("Failed when trying to post data");
      }
    }

    const result = await res.json();
    const resultData = result.data;

    // Check if the data is empty
    if (!resultData || resultData.length === 0) {
      swal.fire({
        icon: 'warning',
        title: 'Generate Gagal',
        text: 'Tidak ditemukan data / sudah diproses di periode ini, silahkan pilih periode kembali',
        allowOutsideClick: false,
        confirmButtonText: 'OK',
      });
      return;
    }

    resultData.forEach((item) => {
      item._id = ++_id;
      item.t_perhitungan_gaji_id = item.id;
      item.karyawan = item['m_kary.nama_lengkap'];
      item.deskripsi = item['deskripsi'];
      item.detail_gaji = item['detail_gaji'];
    });

    detailArr.value = resultData;
    summaryPengeluaranGaji();

    // Close SweetAlert after successfully loading data
    swal.close();
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: err,
    });
  }
}


function deleteRow(item) {
  detailArrAdjOpen.items = detailArrAdjOpen.items.filter((e) => e._id != item._id)
  summaryAdj()
  generatePPH(false)
}



async function posted() {
  const payload = {
    id: route.params.id
  }
  try {
    const dataURL = `${store.server.url_backend}/operation${endpointApi}/postData`
    isRequesting.value = true
    const res = await fetch(dataURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(payload)
    })
    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json()
        formErrors.value = responseJson.errors || {}
        throw (responseJson.message || "Failed when trying to post data")
      } else {
        throw ("Failed when trying to post data")
      }
    }
    router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())))
  } catch (err) {
    isBadForm.value = true
    swal.fire({
      icon: 'error',
      text: err
    })
  }
  isRequesting.value = false

}



function saveModal() {
  // **Menghapus nilai 0 dari items**
  detailArrAdjOpen.items = detailArrAdjOpen.items.filter(v => v !== 0);
  // **Menambahkan PPH 21 ke dalam detailArrAdjOpen.items**
  detailArrAdjOpen.items = detailArrAdjOpen.items.concat(totalAdjPPHOpen.value);
  // **Menghapus kembali nilai 0 jika masih ada**
  detailArrAdjOpen.items = detailArrAdjOpen.items.filter(v => v !== 0);
  detailArrAdjOpen.items.forEach((v, i) => {
    v.seq = i + 1;
  });

  detailArrAdjOpen.items = detailArrAdjOpen.items.filter(item => item.label !== "Total Gaji");

  // **Simpan data ke detailArr**
  detailArr.value[idxOpen.value]['detail_adj'] = detailArrAdjOpen.items;
  // **Simpan total tax dan netto**
  detailArr.value[idxOpen.value]['total_tax'] = totalAdjPPHOpen.value.length ? totalAdjPPHOpen.value[0].value : 0;
  detailArr.value[idxOpen.value]['netto'] = totalAdjFinalOpen.value;
  // **Generate PPH dan summary pengeluaran gaji**
  generatePPH(false);
  summaryPengeluaranGaji();
  console.log("Setelah generatePPH & summaryPengeluaranGaji");
  // **Menutup modal**
  modalOpen.value = false;
  console.log("Modal ditutup");
}

function summaryPengeluaranGaji() {
  values.total_pengeluaran_gaji = detailArr.value.reduce((a, b) => {
    const netto = Number(b.netto);
    if (!isNaN(netto)) {
      return a + netto;
    } else {
      console.warn(`Skipping non-numeric value: ${b.netto}`);
      return a;
    }
  }, 0)
}


// save  betul
// async function onSave() {
//   if (!values.desc) {
//     swal.fire({
//       icon: 'warning',
//       text: 'Deskripsi Pendek wajib di isi!'
//     });
//     return;
//   }

//   let dataSave = JSON.parse(JSON.stringify(values));

//   detailArr.value.forEach(v => {
//     if (!Array.isArray(v.detail_adj)) {
//       if (Array.isArray(v.detail_gaji)) {
//         v.detail_adj = v.detail_gaji.map(d => ({ ...d }));

//       } else {
//         v.detail_adj = [];
//       }
//     }

//     if (!Array.isArray(v.detail_gaji)) {
//       v.detail_gaji = [];
//     }

//     v.detail_gaji.forEach((d, i) => {
//       d.seq = i + 1;
//     });

//     const totalTambah = v.detail_adj
//       .filter(item => item.factor === "+")
//       .reduce((sum, item) => sum + Number(item.value || 0), 0);

//     const totalKurang = v.detail_adj
//       .filter(item => item.factor === "-")
//       .reduce((sum, item) => sum + Number(item.value || 0), 0);

//     const totalGaji = totalTambah - totalKurang;

//     const totalGajiItem = v.detail_adj.find(item => item.label === "Total Gaji");

//     if (totalGajiItem) {
//       totalGajiItem.value = totalGaji;
//       totalGajiItem.factor = "=";
//     } else {
//       v.detail_adj.push({
//         type: "-",
//         label: "Total Gaji",
//         value: totalGaji,
//         factor: "=",
//         seq: v.detail_adj.length + 1
//       });
//     }

//     v.t_final_gaji_det_rincian = v.detail_adj;
//   });

//   dataSave.t_final_gaji_det = detailArr.value;

//   try {
//     isRequesting.value = true;
//     const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
//     const dataURL = isCreating
//       ? `${store.server.url_backend}/operation${endpointApi}/save`
//       : `${store.server.url_backend}/operation${endpointApi}/update`;

//     const res = await fetch(dataURL, {
//       method: isCreating ? 'POST' : 'PUT',
//       headers: {
//         'Content-Type': 'Application/json',
//         Authorization: `${store.user.token_type} ${store.user.token}`
//       },
//       body: JSON.stringify(dataSave)
//     });

//     if (!res.ok) {
//       if ([400, 422].includes(res.status)) {
//         const responseJson = await res.json();
//         formErrors.value = responseJson.errors || {};
//         throw (responseJson.errors?.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data");
//       } else {
//         throw ("Failed when trying to post data");
//       }
//     }

//     router.replace('/' + modulPath + '?reload=' + Date.now());
//   } catch (err) {
//     isBadForm.value = true;
//     swal.fire({
//       icon: 'error',
//       text: err
//     });
//   }

//   isRequesting.value = false;
// }

async function onSave() {
  if (!values.desc) {
    swal.fire({
      icon: 'warning',
      text: 'Deskripsi Pendek wajib di isi!'
    });
    return;
  }

  const dataSave = JSON.parse(JSON.stringify(values));
  const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);

  detailArr.value.forEach(v => {

    const oldAdj = Array.isArray(v.t_final_gaji_det_rincian)
      ? v.t_final_gaji_det_rincian.map(x => ({ ...x }))
      : [];

    const gajiAdj = Array.isArray(v.detail_gaji)
      ? v.detail_gaji.map(x => ({ ...x }))
      : [];

    const editAdj = Array.isArray(v.detail_adj)
      ? v.detail_adj.map(x => ({ ...x }))
      : [];

    const mergeMap = new Map();

    if (isCreating) {
      [...gajiAdj, ...editAdj].forEach(item => {
        const key = `${item.label}-${item.factor}-${item.type}`;
        mergeMap.set(key, item);
      });
    } else {
      oldAdj.forEach(item => {
        const key = item.id ?? `${item.label}-${item.factor}-${item.type}`;
        mergeMap.set(key, item);
      });

      editAdj.forEach(item => {
        const key = item.id ?? `${item.label}-${item.factor}-${item.type}`;
        mergeMap.set(key, {
          ...mergeMap.get(key),
          ...item
        });
      });
    }

    let mergedAdj = Array.from(mergeMap.values())
      .filter(item => item.label !== 'Total Gaji');

    const totalTambah = mergedAdj
      .filter(i => i.factor === '+')
      .reduce((s, i) => s + Number(i.value || 0), 0);

    const totalKurang = mergedAdj
      .filter(i => i.factor === '-')
      .reduce((s, i) => s + Number(i.value || 0), 0);

    const totalGaji = totalTambah - totalKurang;

    mergedAdj.push({
      id: null,
      type: '-',
      label: 'Total Gaji',
      value: totalGaji,
      factor: '=',
      seq: mergedAdj.length + 1
    });

    mergedAdj.forEach((d, i) => {
      d.seq = i + 1;
    });

    v.detail_adj = mergedAdj;
    v.t_final_gaji_det_rincian = mergedAdj;
  });

  dataSave.t_final_gaji_det = detailArr.value;

  try {
    isRequesting.value = true;

    const dataURL = isCreating
      ? `${store.server.url_backend}/operation${endpointApi}/save`
      : `${store.server.url_backend}/operation${endpointApi}/update`;

    const res = await fetch(dataURL, {
      method: isCreating ? 'POST' : 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(dataSave)
    });

    if (!res.ok) {
      const responseJson = await res.json();
      throw responseJson.message || 'Failed when trying to post data';
    }

    router.replace('/' + modulPath + '?reload=' + Date.now());
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: err
    });
  } finally {
    isRequesting.value = false;
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
                const resultJson = await res.json()
                throw (resultJson.message || "Failed when trying to remove data")
              }
              apiTable.value.reload()
              const resultJson = await res.json()
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
      // click(row) {
      //   openDetailFromLanding(row)
      // }'
      click(row) {
        router.push(`${route.path}/${row.id}?` + tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      show: (row) => row.status?.toUpperCase() === 'DRAFT',
      // show: (row) => (currentMenu?.can_update)||store.user.data.username==='developer',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&` + tsId)
      }
    },
    {
      icon: 'paper-plane',
      title: "Posted Data",
      class: 'bg-rose-700 rounded-lg text-white',
      show: (row) => row.status?.toUpperCase() === 'DRAFT',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Verifikasi&` + tsId)
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
      searchfield: 'this.nomor, this.desc, this.periode_awal, this.periode_akhir, this.total_pengeluaran_gaji, this.status',
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
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'desc',
    headerName: 'Deskripsi Pendek',
    filter: true,
    sortable: true,
    wrapText: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'periode_awal',
    headerName: 'Periode Awal',
    filter: true,
    wrapText: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'periode_akhir',
    headerName: 'Periode Akhir',
    filter: true,
    wrapText: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'total_pengeluaran_gaji',
    headerName: 'Total Pengeluaran Gaji',
    filter: true,
    sortable: true,
    wrapText: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-end'],
    cellRenderer: ({ value }) => {
      return formatRupiah(value)
    }
  },
  {
    field: 'status',
    headerName: 'Status',
    filter: true,
    wrapText: true,
    sortable: true,
    flex: 1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
      return value === 'DRAFT'
        ? `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${value}</span>`
        : `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">${value}</span>`
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
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))