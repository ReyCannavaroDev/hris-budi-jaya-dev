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
const endpointApi = '/t_kary_salary'
onBeforeMount(()=>{
  document.title = 'Komponen Gaji'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
let initialValues2 = {}
let initialValues3 = {}
let ketGrad = [];  
const changedValues = []

const values = reactive({
  tipe : 'HARIAN',
  is_active:true,
  direktorat: store.user.data?.direktorat,
})

watchEffect(() => {});

async function ubah(m_kary_id) {
  try {
    isRequesting.value = true; 
    // URL PERTAMA
    const dataURL1 = `${store.server.url_backend}/operation${endpointApi}/kary`;
    const params1 = new URLSearchParams({
      m_kary_id: values.m_kary_id,
      view_gaji: true,
      simplest: true,
    });
    const res1 = await fetch(`${dataURL1}?${params1}`, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });
    if (!res1.ok) throw new Error(`HTTP error! Status: ${res1.status}`);
    const resultJson1 = await res1.json();
    const initialValues = resultJson1.data;
    detailArr.value = [];
    const salaryDetails = initialValues?.t_kary_salary_det || [];
    if (salaryDetails.length === 0) {
      salaryDetails.push({
        nominal: 0,
        keterangan: 'Gaji Pokok',
      });
    }
    salaryDetails.forEach((item) => {
      item.__id = ++_id;
      detailArr.value.push(item);
    });
    for (const key in initialValues) {
      if (Object.hasOwnProperty.call(initialValues, key)) {
        values[key] = initialValues[key];
      }
    }

    // URL 2
    const id = m_kary_id?.id ?? values.m_kary_id ?? null;
    const dataURL2 = `${store.server.url_backend}/operation/m_kary/${id}`;
    const params2 = new URLSearchParams({ join: true });
    const res2 = await fetch(`${dataURL2}?${params2}`, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });
    if (!res2.ok) throw new Error(`HTTP error! Status: ${res2.status}`);
      const resultJson2 = await res2.json();
      const initialValues2 = resultJson2.data;
      if (!initialValues2.grading_id) {
        const result = await swal.fire({
          icon: 'warning',
          title: 'Grading Tidak Ditemukan',
          text: 'Apakah Anda yakin ingin melanjutkan?',
          showCancelButton: true,
          confirmButtonText: 'IYA',
          cancelButtonText: 'TIDAK',
        });
        if (result.isConfirmed) {
          values.grading = null;
          return;
        } else {
          router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())));
          return;
        }
      }
    values.grading = initialValues2['grading.value'] ?? null;

    // URL 3
    const idGrade = initialValues2.grading_id;
    const dataURL3 = `${store.server.url_backend}/operation/m_general/${idGrade}`;
    const params3 = new URLSearchParams({
      scopes: 'GradeWithDetail',
      join: false,
    });
    const res3 = await fetch(`${dataURL3}?${params3}`, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });
    if (!res3.ok) throw new Error(`HTTP error! Status: ${res3.status}`);
    const resultJson3 = await res3.json();
    const initialValues3 = resultJson3.data;
    ketGrad = initialValues3.treatments.filter((item) => item.type === 'field');
    ketGrad.forEach((item) => {
      const dt_ket = item.keterangan
        .toLowerCase()
        .split(' ')
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
      const sudahAda = detailArr.value.some((sudah_ada) => sudah_ada.keterangan === dt_ket);
      if (!sudahAda) {
        detailArr.value.push({
          __id: ++_id,
          nominal: 0,
          keterangan: dt_ket,
        });
      }
    });
  } catch (err) {
    console.log('Fetch error:', err);
    isBadForm.value = true;
    await swal.fire({
      icon: 'error',
      text: `Gagal Fetch Data Karena ${err.message}`,
      allowOutsideClick: false,
      confirmButtonText: 'Kembali',
    });
    router.back();
  } finally {
    isRequesting.value = false;
  }
}

let _id = 0;
const detailArr = ref([]);
const addDetail = () => {
  const tempItem = {
    __id: ++_id,
    nominal : 0 ,
  };
  detailArr.value.push(tempItem); 
};

const removeDetail = (detailItem) => {

  if (detailItem.__id === detailArr.value[0]?.__id) {
    swal.fire({
      icon: 'error',
      title: 'Gagal Hapus',
      text: 'Tidak dapat menghapus item pertama.',
    });
    return;
  }

  const isGradeItem = ketGrad.some((item) => {
    const formattedName = item.keterangan
      .toLowerCase()
      .split(' ')
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
      .join(' ');

    return formattedName === detailItem.keterangan;
  });

  if (isGradeItem) {
    swal.fire({
      icon: 'error',
      title: 'Gagal Hapus',
      text: 'Item dari Grading hanya bisa dihapus melalui Form Grading.',
    });
    return;
  }
  const existingItem = detailArr.value.find((item) => item.__id === detailItem.__id);
  if (!existingItem) {
    swal.fire({
      icon: 'error',
      title: 'Item Tidak Ditemukan',
      text: 'Item yang ingin dihapus tidak ditemukan.',
    });
    return;
  }

  detailArr.value = detailArr.value.filter((e) => e.__id !== detailItem.__id);

  swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Item berhasil dihapus.',
  });
};

watchEffect(() => {
  reTotal();
});

function reTotal() {
  const detailItems = detailArr.value;

  if (detailItems.length > 0) {
    values.total = detailItems.reduce((total, item) => total + parseFloat(item.nominal || 0), 0);
  } else {
    values.total = 0;
  }
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

onBeforeMount(async () => {
    values.m_kary_id = route.params.id
    console.log( 'udin' ,values.m_kary_id)
    await ubah ()
});


async function onSave() {
  // if (!values.total || values.total <= 0) {
  //   swal.fire({
  //     icon: 'error',
  //     title: 'Gagal Simpan',
  //     text: 'Nilai Total harus lebih dari 0.'
  //   });
  //   return;
  // }
try {
    values.is_active = 1;
    // Logic On Save disini
    values.t_kary_salary_det = detailArr.value;

    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/t_kary_salary/save`;
    isRequesting.value = true;
    console.log("Sending data:", values);
    const confirmResult = await swal.fire({
        icon: 'question',
        title: 'Simpan',
        text: 'Apakah Data Sudah Benar?',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',

    });
    if (!confirmResult.isConfirmed) {
        throw "Simpan dibatalkan";
    }
    const res = await fetch(dataURL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: JSON.stringify(values)
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
    await swal.fire({
        icon: 'success',
        title: 'Simpan Berhasil',
        confirmButtonText: 'OK'
    });
  router.replace('/' + modulPath + '?reload='+(Date.parse(new Date())))
} catch (err) {
    isBadForm.value = true;
    swal.fire({
        icon: 'error',
        title: 'Gagal Simpan',
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
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&`+tsId)
      }
    },

  ],
  api: {
    url: `${store.server.url_backend}/operation/m_kary`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
params: {
  view_gaji: true,
  simplest: true,
  searchfield: 'this.nama_lengkap, m_divisi.nama , m_dept.nama ',
  where: 'this.is_active = true'
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
    headerName: 'Nama Lengkap',
    field: 'nama_lengkap',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
{
    headerName: 'Divisi',
    field: 'm_divisi.nama',
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
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },

{
  headerName: 'Gaji',
  field: 'gaji',
  filter: true,
  sortable: true,
  flex: 1,
  resizable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-end'],
  valueFormatter: params => {
    // Format number to Rupiah currency
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(params.value);
  },
},
  
{
  headerName: 'Update Terakhir',
  field: 'gaji_last_updated_at',
  filter: true,
  sortable: true,
  flex: 1,
  resizable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  valueFormatter: (params) => {
    if (params.value) {
      const date = new Date(params.value);
      const day = String(date.getDate()).padStart(2, '0');
      const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
      ];
      const month = monthNames[date.getMonth()]; // Get the month name
      const year = date.getFullYear();
      return `${day} ${month} ${year}`;
    }
    return 'No Data'; // Display message if value is empty
  }
}


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