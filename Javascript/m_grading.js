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
const endpointApi = '/m_general'
onBeforeMount(()=>{
  document.title = 'Master Grade'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []
const values = reactive({
  is_active: true,
  key: 'GRADE',
  group: 'GRADING',
})





onBeforeMount(async () => {
  values.direktorat = store.user.data?.direktorat
  loadData();
  }
)
async function loadData() {
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true
      const params = {
        scopes : "GradeWithDetail",
        join: false,
        transform: false 
        }
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
      console.log(initialValues.treatments)
    initialValues.treatments.forEach((treatment) => {

      detailArr.value.push({
        ...treatment,
       day: treatment.day, 
     });
    });

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
}




const detailArr = ref([]);


const addDetail = () => {
  if (!values.value_2) {
    swal.fire({
      icon: "warning",
      title: "Peringatan",
      text: "Harap pilih TIPE terlebih dahulu sebelum menambahkan detail.",
    });
    return;
  }

  const detailGrade = {
    m_grade_id: values.id ?? null,
    type: "field",
    factor: "+",
    value_overtime: 0,
    big_event: false,
  };

  detailArr.value = [...detailArr.value, detailGrade];
};


const toggleBigEvent = (index) => {
  detailArr.value.forEach((detail, i) => {
    detail.big_event = i === index; 
  });
};

const removeDetail = (index) => {
  const detail = detailArr.value[index];

  const isDefault = 
    detail.m_grade_id === (values.id ?? null) &&
    detail.type === 'field' &&
    detail.factor === '+' &&
    detail.value_overtime === 0;

  const hasKeterangan = detail.keterangan && detail.keterangan.trim() !== '';

  const deleteFromDatabase = async (detailId) => {
    try {
      const dataURL = `${store.server.url_backend}/operation/m_grade_d/${detailId}`;
      const response = await fetch(dataURL, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`,
        },
      });

      if (!response.ok) {
        throw new Error(`Gagal menghapus dari database: ${response.statusText}`);
      }

      const loadingSwal = swal.fire({
        title: 'Menunggu...',
        text: 'Sedang memuat ulang data...',
        allowOutsideClick: false,
        didOpen: () => {
          swal.showLoading();
        },
        didClose: () => {
          swal.hideLoading();
        },
      });
      detailArr.value = [];
      await new Promise((resolve) => setTimeout(resolve, 2000));
      await loadData();
      swal.close();
      swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Item berhasil dihapus dan data diperbarui',
        timer: 1500,
        showConfirmButton: false,
      });
    } catch (error) {
      swal.fire({
        icon: 'error',
        title: 'Gagal Menghapus',
        text: error.message,
      });
    }
  };
  if (!detail.id) {
    detailArr.value.splice(index, 1);
    return;
  }
  if (isDefault && !hasKeterangan) {
    detailArr.value.splice(index, 1);
    return;
  }

  swal.fire({
    title: 'Ingin menghapus Item?',
    text: 'Item ini sudah tersimpan dalam Database. Apakah Anda yakin ingin menghapusnya?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya',
    cancelButtonText: 'Tidak',
  }).then((result) => {
    if (result.isConfirmed) {
      const detailId = detail.id; 
      detailArr.value.splice(index, 1);
      deleteFromDatabase(detailId);
    }
  });
};

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


async function onSave() {
  detailArr.value = detailArr.value.map(item => ({
    ...item,
    keterangan: item.keterangan ? item.keterangan.trim() : ''
  }));
  const udinPenyok = detailArr.value.some(item => !item.keterangan);

  if (udinPenyok) {
    swal.fire({
      icon: 'error',
      text: 'Gagal Simpan. Keterangan Wajib di isi.'
    });
    return;
  }
  values.treatments = detailArr.value;
  try {
    const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
    const dataURL = `${store.server.url_backend}/operation/m_grade_d/save`;
    values.is_active = values.is_active ? 1 : 0;
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
        throw responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data";
      } else {
        throw "Failed when trying to post data";
      }
    }

    router.replace('/' + modulPath + '?reload=' + Date.parse(new Date()));
  } catch (err) {
    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: err
    });
  }

  isRequesting.value = false;
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
      searchfield:'this.id, this.code, this.value',
      where: `this.group='GRADING'`
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
    field: 'code',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200']
  },
  { 
    headerName:'Nama Grade',
    field: 'value',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200']
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
  }},
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