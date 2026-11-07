//   javascript//   javascript

import { useRouter, useRoute, RouterLink } from 'vue-router';
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated, computed , watch } from 'vue';

const router = useRouter();
const route = useRoute();
const store = inject('store');
const swal = inject('swal');

const isRead = route.params.id && route.params.id !== 'create';
const actionText = ref(route.params.id === 'create' ? 'Tambah' : route.query.action);
const isBadForm = ref(false);
const isRequesting = ref(false);
const modulPath = route.params.modul;
const currentMenu = store.currentMenu;
const apiTable = ref(null);
const formErrors = ref({});
const ts = +(Date.parse(new Date()));
const tsId = ts;
let trx_dtl = reactive({ items: [] });
let detailKey = ref(0);
let modalOpen = ref(false);
let detailIdxSelected = ref(0);
let trx_dtl_sub = reactive({ items: [] });
let _id = ref(0);
let activeTabIndex = ref(0);

// ------------------------------ PERSIAPAN
const endpointApi = '/t_jadwal_kerja'
onBeforeMount(()=>{
  document.title = 'Jadwal Kerja'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS

const tabOpen = ref(1);
const tabs = ref([]);
let initialValues = {};
const changedValues = [];
const dataActive = reactive({ items: [] });
const values = reactive({
  status: 'DRAFT',
});

const selectedItems = ref([]);
const selectedIndexes = ref([]);
const isAllSelected = computed(() => {
  return dataActive.items.length === selectedIndexes.value.length;
});

const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedIndexes.value = [];
    selectedItems.value = [];
  } else {
    selectedIndexes.value = dataActive.items.map((_, i) => i);
    selectedItems.value = dataActive.items.map(item => item._id);
  }
};

const selectDel = async () => {
  if (selectedIndexes.value.length > 0) {
    const selectedDetails = selectedIndexes.value
      .filter(index => dataActive.items[index])
      .map(index => dataActive.items[index]);

    if (selectedDetails.length === 0) {
      swal.fire({
        icon: 'warning',
        text: 'Tidak ada item yang dipilih atau item yang dipilih telah dihapus',
      });
      return;
    }

    const deletePayload = selectedDetails.map(detailItem => ({
      id: detailItem.id,
      t_jadwal_kerja_det_hari_id: detailItem.t_jadwal_kerja_det_hari_id,
      t_jadwal_kerja_id: detailItem.t_jadwal_kerja_id,
      m_dir_id: detailItem.m_dir_id,
      m_divisi_id: detailItem.m_divisi_id,
      m_dept_id: detailItem.m_dept_id,
      m_kary_id: detailItem.m_kary_id
    }));

    const deleteURL = `${store.server.url_backend}/operation/t_jadwal_kerja/delete_jadwal_bulk`;

    try {
      const res = await fetch(deleteURL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`,
        },
        body: JSON.stringify({ items: deletePayload }),
      });

      if (res.ok) {
        loadData();
        selectedIndexes.value = [];

        swal.fire({
          icon: 'success',
          text: 'Data berhasil dihapus dari Database',
        });
      } else {
        throw new Error('Failed to delete data from Database');
      }
    } catch (error) {
      swal.fire({
        icon: 'error',
        text: error.message,
      });
    }
  } else {
    swal.fire({
      icon: 'warning',
      text: 'Tidak ada item yang dipilih',
    });
  }
};


// LOGIC PAGE
const itemsPerPage = 25;
const currentPage = ref(1);
const totalPages = computed(() => Math.ceil(filteredData.value.length / itemsPerPage));

const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredData.value.slice(start, start + itemsPerPage);
});

const pageRange = computed(() => {
  const range = [];

  if (totalPages.value <= 1) return range;

  range.push(1); 

  if (currentPage.value > 3) range.push('...'); 

  const startPage = Math.max(2, currentPage.value - 1);
  const endPage = Math.min(totalPages.value - 1, currentPage.value + 1);

  for (let i = startPage; i <= endPage; i++) {
    range.push(i);
  }

  if (currentPage.value < totalPages.value - 2) range.push('...'); 

  range.push(totalPages.value); 

  return range;
});

const goToPreviousPage = () => { if (currentPage.value > 1) currentPage.value--; };
const goToNextPage = () => { if (currentPage.value < totalPages.value) currentPage.value++; };
const goToPage = (page) => { if (page >= 1 && page <= totalPages.value) currentPage.value = page; };




// END LOGIC PAGE


// LOAD DATA API
onMounted(() => {
  activeTabIndex.value = 0;  
});

onBeforeMount(async () => {
  loadData();
});

async function loadData() {
  if (isRead) {
    try {
      const editedId = route.params.id;
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`;
      isRequesting.value = true;

      const params = { join: true };
      const fixedParams = new URLSearchParams(params);
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      });
      if (!res.ok) throw new Error("Failed when trying to read data");
      const resultJson = await res.json();
      initialValues = resultJson.data;
      const sortedData = resultJson.data?.t_jadwal_kerja_det_hari.sort((a, b) => a.day_num - b.day_num);
      tabs.value = sortedData;
      mapView(activeTabIndex.value);

    } catch (err) {
      isBadForm.value = true;
      swal.fire({
        icon: 'error',
        text: err,
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        router.back();
      });
    }
    isRequesting.value = false;
  }

  for (const key in initialValues) {
    values[key] = initialValues[key];
  }

  values?.generate_num_det?.forEach((v, i) => {
    v._id = i++;
  });
}


// FITUR SEARCH DAN FILTER
const searchQuery = ref('');
watch(activeTabIndex, () => {
  searchQuery.value = ''; 
});
const filteredData = computed(() => {
  const search = searchQuery.value.toLowerCase();
  const allNames = new Set();
  let filtered = [];

  const dataToFilter = tabs.value[activeTabIndex.value]?.t_jadwal_kerja_det ?? [];

  dataToFilter.forEach(item => {
    const name = item?.nama_lengkap?.toLowerCase();
    const deptName = item?.['m_dept.nama']?.toLowerCase();

    if ((name && name.includes(search) || deptName && deptName.includes(search)) && !allNames.has(item.nama_lengkap)) {
      filtered.push(item);
      allNames.add(item.nama_lengkap);
    }
  });

  return filtered.sort((a, b) => {
    const nameA = a.nama_lengkap.toLowerCase();
    const nameB = b.nama_lengkap.toLowerCase();
    if (nameA < nameB) return -1;
    if (nameA > nameB) return 1;
    return 0;
  });
});

const onSearch = () => {
  currentPage.value = 1; 
};

// MAPVIEW
const mapView = (i) => {
  activeTabIndex.value = i; 
  let data = tabs.value[i]?.t_jadwal_kerja_det ?? [];
  selectedItems.value = [];

  data.forEach((v) => {
    v.nama_lengkap = v['m_kary.nama_lengkap'] ?? v.nama_lengkap;
  });

  dataActive.items = filteredData.value;

  currentPage.value = 1; 
};

watch(filteredData, () => {
  currentPage.value = 1; 
});




let __id = 0;

const onDetailAdd = async (rows) => {
  console.log('oke', rows);
  const t_jadwal_kerja_det_hari_id = tabs.value[activeTabIndex.value]?.id;

  const mapped = rows.map(e => {
    return {
      m_kary_id: e.id,
      nama_lengkap: e.nama_lengkap, 
      t_jadwal_kerja_det_hari_id,
      'm_dept.nama': e['m_dept.nama'] || '',
      'm_divisi.nama': e['m_divisi.nama'] || '',
      'm_dir_id': e['m_dir_id'] || null,
      'm_divisi_id': e['m_divisi_id'] || null,
      'm_dept_id': e['m_dept_id'] || null,
      t_jadwal_kerja_id: parseInt(route.params.id),
    };
  });

  const payload = mapped.map(e => ({
    t_jadwal_kerja_det_hari_id: e.t_jadwal_kerja_det_hari_id,
    t_jadwal_kerja_id: e.t_jadwal_kerja_id,
    nama_lengkap: e.nama_lengkap, 
    m_dir_id: e.m_dir_id,
    m_divisi_id: e.m_divisi_id,
    m_dept_id: e.m_dept_id,
    m_kary_id: e.m_kary_id,
  }));

  const loadingSwal = swal.fire({
    title: 'Menunggu...',
    text: 'Sedang menyimpan data karyawan...',
    didOpen: () => {
      swal.showLoading();
    },
    allowOutsideClick: false,
    didClose: () => swal.hideLoading()
  });

  try {
    const response = await fetch(`${store.server.url_backend}/operation${endpointApi}/save_jadwal`, {
      method: "POST",
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(payload)
    });

    if (response.ok) {

      loadData();
      swal.fire("Success", "Karyawan Telah Ditambahkan Kedalam Jadwal", "success");
    } else {
      throw new Error("Failed to save data");
    }
  } catch (error) {
    console.error("Error:", error);
    swal.fire("Error", "Gagal Menyimpan Data Karyawan", "error");
  } finally {
    loadingSwal.close();
  }
};






function onBack() {
  router.replace('/t_jadwal_kerja/'+route.params.id)
}




//  @else----------------------- LANDING

const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status?.toUpperCase() !== 'POSTED',
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
      show: (row) => row.status?.toUpperCase() !== 'POSTED',
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
    return value === 'POSTED'
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">POSTED</span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">DRAFT</span>`
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