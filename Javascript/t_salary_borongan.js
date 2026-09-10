import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated, computed } from 'vue'

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
const tsId = `ts=` + (Date.parse(new Date()))
const dateArr = ref([1, 2, 3, 4, 5, 6, 7, 8])
const dateActive = ref(1)
const showItemDate = ref(false)

const activeDate = (index) => {
  dateActive.value = index
}
const activeTabIndex = ref(0)

// ------------------------------ PERSIAPAN
const endpointApi = '/m_general'
onBeforeMount(() => {
  document.title = 'Transaksi Salary Borongan'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []




onBeforeMount(async () => {
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

let _id = 0
// const detailArr = ref([])
// const addOtoritas = () => {
//   const tempItem = {
//     id: ++_id,
//     nama: values.nama,
//     akses: values.akses,
//     admin: values.admin
//   }
//   detailArr.value = [...detailArr.value, tempItem]
//   Object.keys(values).forEach(key => delete values[key]);
//   console.log(detailArr.value)
// }

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

  swal.fire({
    icon: 'warning',
    text: 'Buang semua perubahan dan kembali ke list data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      router.replace('/' + modulPath)
    }
  })
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

//  @else----------------------- LANDING

const searchQuery = ref(""); // Search query

const filteredLabels = computed(() => {
  // Filter the labels based on the search query
  return labelArr.value.filter(item =>
    item.label.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});


const values = reactive({
  is_active: true,
  jml_kary: 0
})

const currentYear = ref(new Date().getFullYear());
const currentMonth = ref('');
const currentDate = ref(new Date().getDate())
const daysOfWeek = ['Mng', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
const calendarRows = ref([]);
const dateNow = ref()
var months = [
  "Januari", "Februari", "Maret", "April", "Mei", "Juni",
  "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];

const initializeCalendar = async () => {
  const currentDate = new Date();
  currentMonth.value = currentDate.toLocaleString('default', { month: 'long' });
  values.month = `${currentYear.value}-${(currentDate.getMonth() + 1).toString().padStart(2, '0')}`
  const daysInMonth = new Date(currentYear.value, currentDate.getMonth() + 1, 0).getDate();
  const firstDay = new Date(currentYear.value, currentDate.getMonth(), 1).getDay();
  let tempCalendarRows = [];
  let dayCounter = 1;

  for (let i = 0; i < 6; i++) {
    let row = [];
    for (let j = 0; j < 7; j++) {
      if (i === 0 && j < firstDay) {
        row.push({ day: '', date: '' });
      } else if (dayCounter > daysInMonth) {
        break;
      } else {
        const date = new Date(currentYear.value, currentDate.getMonth(), dayCounter);
        if (isToday(date)) {
          dateNow.value = formatDate(date)
          values.date = formatDate(date)
          await getlabelDate(values.date)
        }
        row.push({ day: dayCounter, date: date, isToday: isToday(date) });
        dayCounter++;
      }
    }
    tempCalendarRows.push(row);
    if (dayCounter > daysInMonth) break;
  }

  calendarRows.value = tempCalendarRows;
};


const changeCalendar = async () => {
  resetAll()

  const tempBulan = values.month?.split('-')
  const year = Number(tempBulan[0])
  const monthIndex = Number(tempBulan[1]) - 1

  const daysInMonth = new Date(year, monthIndex + 1, 0).getDate()
  const firstDay = new Date(year, monthIndex, 1).getDay()

  let tempCalendarRows = []
  let dayCounter = 1

  for (let i = 0; i < 6; i++) {
    let row = []
    for (let j = 0; j < 7; j++) {
      if (i === 0 && j < firstDay) {
        row.push({ day: '', date: '' })
      } else if (dayCounter > daysInMonth) {
        break
      } else {
        const date = new Date(year, monthIndex, dayCounter)

        if (isToday(date)) {
          dateNow.value = formatDate(date)
        }

        if (
          (String(monthIndex + 1).padStart(2, '0') === dateNow.value?.split('-')[1] && isToday(date)) ||
          (String(monthIndex + 1).padStart(2, '0') !== dateNow.value?.split('-')[1] && dayCounter === 1)
        ) {
          values.date = formatDate(date)
          await getlabelDate(values.date)
        }

        row.push({
          day: dayCounter,
          date: date,
          isToday:
            (String(monthIndex + 1).padStart(2, '0') === dateNow.value?.split('-')[1] && isToday(date)) ||
            (String(monthIndex + 1).padStart(2, '0') !== dateNow.value?.split('-')[1] && dayCounter === 1)
        })

        dayCounter++
      }
    }
    tempCalendarRows.push(row)
    if (dayCounter > daysInMonth) break
  }

  calendarRows.value = tempCalendarRows
}


const isToday = (date) => {
  const today = new Date();
  return date.getFullYear() === today.getFullYear() && date.getMonth() === today.getMonth() && date.getDate() === today.getDate();
};

const formatDate = (date) => {
  const day = date.getDate();
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const year = date.getFullYear();
  return `${year}-${month}-${day}`;
};

const handleDateClick = async (date) => {
  if (date) {
    calendarRows.value.forEach(row => {
      row.forEach(cell => {
        cell.isToday = cell.date && cell.date.toDateString() === date.toDateString(); // Mengatur isToday berdasarkan tanggal yang diklik
      });
    });
    values.date = formatDate(date)
    await getlabelDate(values.date)
    console.log('Tanggal:', formatDate(date));
  }
};

const isDateSucs = ref(false)
const toggleLabel = async () => {
  isDateSucs.value = !isDateSucs.value; 
};

const labelArr = ref([])

async function getlabelDate(tanggal) {
  try {
    detailArr.value = []
    detailArrKary.value = []
    
    delete values.label
    delete values.m_group_kary_id
    const dataURL = `${store.server.url_backend}/operation/t_salary_borongan/get_label_by_date`
    isRequesting.value = true
    const params = { join: false, transform: false, date: tanggal }
    const fixedParams = new URLSearchParams(params)
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    })
    if (!res.ok) throw new Error("Failed when trying to read data")
    const resultJson = await res.json()
    // initialValues = resultJson.data
    if (resultJson.length > 1) {
      isDateSucs.value = true
      labelArr.value = resultJson
      console.log('LABEL',labelArr.value)
    } else if (resultJson.length == 1) {
      values.label = resultJson[0]?.label
      isDateSucs.value = true
      labelArr.value = resultJson
      
      await getDate(resultJson[0]?.label)
    } else {
      isDateSucs.value = false
    }
   } catch (err) {
    isDateSucs.value = false
    isBadForm.value = true
    swal.fire({
      icon: 'error',
      text: err,
    })
  }
  isRequesting.value = false
  tableKey.value++
  tableKeyKary.value++
}


async function getDate(labelParam = null) {
  let initialValues
  try {
    detailArr.value = []
    detailArrKary.value = []
    delete values.label
    delete values.m_group_kary_id
    const dataURL = `${store.server.url_backend}/operation/t_salary_borongan/get_by_date`
    isRequesting.value = true
    const params = { join: false, transform: false, date: values.date, label: labelParam }
    const fixedParams = new URLSearchParams(params)
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    })
    if (!res.ok) throw new Error("Failed when trying to read data")
    const resultJson = await res.json()
    initialValues = resultJson

    initialValues.t_salary_borongan_det?.forEach((items) => {
      console.log(items);
      items['m_tarif.nama'] = items['tarif_desc'];
      items['keterangan'] = items['keterangan'];
      detailArr.value = [items, ...detailArr.value];
    });

    initialValues.t_salary_borongan_det_kary?.forEach((items) => {
      items['nama_lengkap'] = items?.m_kary?.nama_lengkap
      items['kode'] = items?.m_kary?.kode
      items['m_dept_id'] = items?.m_kary?.m_dept_id
      items['m_dept.nama'] = items?.m_kary?.m_dept?.nama
      detailArrKary.value = [items, ...detailArrKary.value]
    })
  } catch (err) {
    isBadForm.value = true
    swal.fire({
      icon: 'error',
      text: err,
    })
  }
  isRequesting.value = false
  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
  tableKey.value++
  tableKeyKary.value++
}



// watchEffect(async () => {
//   if (values.label) {
//     const foundLabel = labelArr.value.find((item) => item.label === values.label);
//     if (foundLabel) {
//       if (detailArr.value.length === 0 && detailArrKary.value.length === 0) {
//         await new Promise((resolve) => setTimeout(resolve, 1000));
//       await getDate(foundLabel.label);
//       }

//     }
//   } else {
//     detailArr.value = [];
//     detailArrKary.value = [];
//   }
// });

async function getKaryGroup(groupId) {
  let initialValues
  try {
    detailArr.value = []
    detailArrKary.value = []
    delete values.label
    delete values.m_group_kary_id
    const dataURL = `${store.server.url_backend}/operation/t_salary_borongan/get_kary_by_group`
    isRequesting.value = true
    const params = { join: false, transform: false, m_kary_group_id: groupId }
    const fixedParams = new URLSearchParams(params)
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    })
    if (!res.ok) throw new Error("Failed when trying to read data")
    const resultJson = await res.json()
    initialValues = resultJson
    initialValues?.forEach((items) => {
      items['nama_lengkap'] = items?.m_kary?.nama_lengkap
      items['kode'] = items?.m_kary?.kode
      items['m_dept_id'] = items?.m_kary?.m_dept_id
      items['m_dept.nama'] = items?.m_kary?.m_dept?.nama
      detailArrKary.value = [items, ...detailArrKary.value]
    })
  } catch (err) {
    isBadForm.value = true
    swal.fire({
      icon: 'error',
      text: err,
    })
  }
  isRequesting.value = false
  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
  tableKey.value++
  tableKeyKary.value++
}

function resetAll() {
  isDateSucs.value = false
  detailArr.value = []
  detailArrKary.value = []
  delete values.label
  delete values.m_group_kary_id
  delete values.pic_id
  tableKey.value++
  tableKeyKary.value++
}

onMounted(() => {
  initializeCalendar();
});


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


const detailArr = ref([])

const tableKey = ref(0)
const tableKeyKary = ref(0)
const onDetailAdd = (e) => {
  e.forEach((row, i) => {
    row['keterangan'] = ''; 
    detailArr.value = [...detailArr.value, row];
  });
  tableKey.value++;
  tableKeyKary.value++;
};

const detailArrKary = ref([])
const onRetotal = () => {
  if (detailArr.value) {
    values.total_pendapatan = detailArr.value?.reduce((acm, item) => {
      return parseFloat(acm ?? 0) + parseFloat(item.subtotal ?? 0);
    }, 0);

    values.jml_kary = detailArrKary.value.length ?? 0
    values.pend_kary = values.jml_kary !== 0 ? values.total_pendapatan / values.jml_kary : 0
  }
}
watchEffect(() => {
  onRetotal()
})

const removeDetail = (index) => {
  detailArr.value = detailArr.value.filter((e) => e.id != index)
  tableKey.value++
  tableKeyKary.value++
}
const removeDetailKary = (index) => {
  detailArrKary.value = detailArrKary.value.filter((e) => e.id != index)
  tableKey.value++
  tableKeyKary.value++
}

const onDetailAddKary = (e) => {
  e.forEach((row, i) => {
    detailArrKary.value = [...detailArrKary.value, row]
    // detailArrKary.value.push(row)
  })

  tableKey.value++
  tableKeyKary.value++
}


async function tandaLabel(tanggal) {
  try {
    const dataURL = `${store.server.url_backend}/operation/t_salary_borongan/get_label_by_date`;
    isRequesting.value = true;
    const params = { join: false, transform: false, date: tanggal };
    const fixedParams = new URLSearchParams(params);
    
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    });

    if (!res.ok) throw new Error("Failed when trying to read data");
    const resultJson = await res.json();
    labelArr.value = resultJson;
    console.log('LABEL', labelArr.value);





  } catch (err) {
    isDateSucs.value = false;
    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: err,
    });
    return false;
  } finally {
    isRequesting.value = false;
  }
}

async function onSave() {
  try {
    let next = true;

    if (detailArr.value.length === 0) {
      swal.fire({
        icon: 'warning',
        text: `Data Tarif tidak boleh kosong`
      });
      return;
    }

    swal.fire({
      title: 'Menyimpan...',
      text: 'Mohon tunggu, sedang memproses data',
      allowOutsideClick: false,
      didOpen: () => {
        swal.showLoading();
      }
    });

    // await tandaLabel(values.date);
    // await new Promise(resolve => setTimeout(resolve, 1000));

    // if (labelArr.value.some(label => label.label === values.label)) {
    //   swal.fire({
    //     icon: 'error',
    //     text: 'Label sudah ada , silahkan tambahkan data dari Label yang sudah ada!'
    //   });

    //   await new Promise(resolve => setTimeout(resolve, 1000));
    //   await getlabelDate(values.date);
    //   return;
    // }

    // console.log(values.label);

    // Format tanggal jika ada
    if (values.date) {
      const [year, month, day] = values.date.split('-');
      values.date = `${year}-${month}-${day.padStart(2, '0')}`;
    }

    const dataURL = `${store.server.url_backend}/operation/t_salary_borongan/post`;
    values.is_active = values.is_active ? 1 : 0;
    values.t_salary_borongan_det = detailArr.value;
    values.t_salary_borongan_det_kary = detailArrKary.value;
    isRequesting.value = true;

    const res = await fetch(dataURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(values)
    });

    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json();
        formErrors.value = responseJson.errors || {};
        throw (responseJson.errors?.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data");
      } else {
        throw ("Failed when trying to post data");
      }
    }

    const responseJson = await res.json();

    await new Promise(resolve => setTimeout(resolve, 1000));
    await getlabelDate(values.date);

    swal.fire({
      icon: 'success',
      text: responseJson.message || "Data berhasil disimpan!"
    });

  } catch (err) {
    swal.fire({
      icon: 'error',
      text: err
    });

    // Tunggu 1 detik sebelum memuat ulang data meskipun gagal
    await new Promise(resolve => setTimeout(resolve, 1000));
    await getlabelDate(values.date);

  } finally {
    isRequesting.value = false;
  }
}





//  @endif -------------------------------------------------END
watchEffect(() => store.commit('set', ['isRequesting', isRequesting.value]))