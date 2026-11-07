import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watch, watchEffect, onActivated, computed } from 'vue'

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

// ------------------------------ PERSIAPAN
const endpointApi = '/t_kary_salary_det_tarif'
onBeforeMount(() => {
  document.title = 'Hasil Borongan'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const values = reactive({
  is_active: true,
  direktorat: store.user.data?.direktorat,
})

watchEffect(() => { });

const today = new Date();
const day = String(today.getDate()).padStart(2, '0');
const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
const year = today.getFullYear();
var formattedDate = `${day}/${month}/${year}`;

// FORMAT TGL
const tgl = (dateStr) => {
  const [year, month, day] = dateStr.split('-')
  return `${day}/${month}/${year}`
}

onBeforeMount(async () => {
  values.date = formattedDate
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/get_data_group_date_detail`
      isRequesting.value = true
      const params = {
        kary_id: route.query.karyId,
        date: route.query.tglNew,
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
      const initialValues = resultJson.data
      if (Array.isArray(initialValues) && initialValues.length > 0) {
        values.m_kary_id = route.query.karyId;
        values.date = tgl(initialValues[0].date);
        let idCounter = 0;
        initialValues.forEach(item => {
          const { m_kary, date, ...details } = item;
          const detailsWithId = { ...details, __id: idCounter++ };
          detailArr.value.push(detailsWithId);
        });
      }
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
    if (key === 'date') {
      values.value[key] = tgl(initialValues.value[key])
    } else {
      values.value[key] = initialValues.value[key]
    }
  }
})

// LOGIC DETAIL DISINI !
const detailArr = ref([]);
const totalAmount = ref(0);

console.log(detailArr.value)

const addDetail = () => {

  if(values.m_kary_id !== null && values.m_kary_id !== undefined) {
    let _id = detailArr.value.length - 1;

    const tempItem = {
      __id: ++_id,
      tarif: 0,
      qty: 1,
      subtotal: 0,
      keterangan: null
    };
    detailArr.value = [...detailArr.value, tempItem];
    console.log(detailArr.value)
    calculateTotal();
  } else {
    swal.fire({
      icon: 'warning',
      text: "Harap pilih Karyawan terlebih dahulu"
    })
  }
};

async function gantiTanggal(v) {
  console.log(v)
  var tglForm = v
  var karyawanId = values.m_kary_id
  var tglFormat = tglForm.split('/')[2] + '-' + tglForm.split('/')[1] + '-' + tglForm.split('/')[0]

  if(karyawanId !==null && karyawanId !==undefined) {
        try {
        const dataURL = `${store.server.url_backend}/operation${endpointApi}/get_data_group_date_detail`;
        const params = {
          kary_id: karyawanId,
          date: tglFormat
        };
        
        const fixedParams = new URLSearchParams(params);
        const res = await fetch(`${dataURL}?${fixedParams}`, {
          headers: {
            'Content-Type': 'application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        });
        if (!res.ok) {
          throw new Error(`HTTP error! Status: ${res.status}`);
        }
        const resultJson = await res.json();
        initialValues = resultJson.data;
        
        var _id = 0-1
        detailArr.value = [];
        initialValues.forEach((item) => {
            item.__id = ++_id;
            detailArr.value.push(item);
          });

        for (const key in initialValues) {
          if (initialValues.hasOwnProperty(key)) {
            values[key] = initialValues[key];
          }
        }
      } catch (err) {
        console.error('Fetch error:', err);
        isBadForm.value = true;
        swal.fire({
          icon: 'error',
          text: `Failed to fetch data: ${err.message}`,
          allowOutsideClick: false,
          confirmButtonText: 'Kembali',
        }).then(() => {
          router.back();
        });
      } finally {
        isRequesting.value = false;
      }
  }
}

async function gantiKaryawan(m_kary_id) {
  var tglForm = values.date
  var karyawanId = values.m_kary_id
  var tglFormat = tglForm.split('/')[2] + '-' + tglForm.split('/')[1] + '-' + tglForm.split('/')[0]

  try {
    const dataURL = `${store.server.url_backend}/operation${endpointApi}/get_data_group_date_detail`;
    const params = {
      kary_id: karyawanId,
      date: tglFormat
    };
    
    const fixedParams = new URLSearchParams(params);
    const res = await fetch(`${dataURL}?${fixedParams}`, {
      headers: {
        'Content-Type': 'application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    });
    if (!res.ok) {
      throw new Error(`HTTP error! Status: ${res.status}`);
    }
    const resultJson = await res.json();
    initialValues = resultJson.data;
    
    var _id = 0-1
    detailArr.value = [];
    initialValues.forEach((item) => {
        item.__id = ++_id;
        detailArr.value.push(item);
      });

    for (const key in initialValues) {
      if (initialValues.hasOwnProperty(key)) {
        values[key] = initialValues[key];
      }
    }
  } catch (err) {
    console.error('Fetch error:', err);
    isBadForm.value = true;
    swal.fire({
      icon: 'error',
      text: `Failed to fetch data: ${err.message}`,
      allowOutsideClick: false,
      confirmButtonText: 'Kembali',
    }).then(() => {
      router.back();
    });
  } finally {
    isRequesting.value = false;
  }
}

const removeDetail = (detailItem) => {
  swal.fire({
    icon: 'warning',
    text: 'Apakah Anda yakin untuk menghapus data ini?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      detailArr.value = detailArr.value.filter((e) => e.__id != detailItem.__id);
      calculateTotal();
    }
  })
};

const updateDetailTarif = (item, selectedItem) => {
  item.tarif = selectedItem.nominal;
  calculateSubTotal(item);
  calculateTotal();
};
const calculateSubTotal = (item) => {
  item.subtotal = item.tarif * item.qty;
};
const calculateTotal = () => {
  totalAmount.value = detailArr.value.reduce((sum, item) => sum + item.subtotal, 0);
};
const format_gaji = new Intl.NumberFormat('en-US', {
  style: 'decimal',
});
const gaji = computed(() => {
  return format_gaji.format(totalAmount.value);
});
watchEffect(() => {
  detailArr.value.forEach(item => {
    calculateSubTotal(item);
  });
  calculateTotal();
});

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
  values.is_active = (values.is_active === true) ? 1 : 0;
  values.detail = detailArr.value;

  if (values.detail.length < 1) {
    swal.fire({
      icon: 'warning',
      text: "Harap tambahkan data Detail terlebih dahulu"
    });
    return;
  }

  // Tampilkan dialog konfirmasi sebelum melanjutkan proses onSave
  const result = await swal.fire({
    title: 'Konfirmasi',
    text: 'Apakah Anda yakin ingin menyimpan data ini?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, Simpan',
    cancelButtonText: 'Batal',
    customClass: {
      confirmButton: 'bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px]',
      cancelButton: 'bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px]'
    }
  });

  if (result.isConfirmed) {
    // Jika pengguna mengonfirmasi, lanjutkan dengan proses penyimpanan
    
    const hasInvalidQty = values.detail.some(item => item.qty < 1);

    if (hasInvalidQty) {
      swal.fire({
        icon: 'warning',
        text: "Jumlah Item tidak boleh kurang dari 1, Silahkan diperiksa kembali"
      });
    } else {
      try {
        const isCreating = ['Create', 'Copy', 'Tambah'].includes(actionText.value);
        const dataURL = `${store.server.url_backend}/operation${endpointApi}/bulk_insert`;
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
            throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data");
          } else {
            throw ("Failed when trying to post data");
          }
        }

        swal.fire({
          icon: 'success',
          text: "Data Hasil Borongan berhasil tersimpan"
        });
        // router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())))
      } catch (err) {
        isBadForm.value = true;
        swal.fire({
          icon: 'error',
          text: err
        });
      }
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
              const dataURL = `${store.server.url_backend}/operation${endpointApi}/delete`
              isRequesting.value = true

              const payload = {
                kary_id: row.m_kary.id,
                date: row.date,
              };
              const res = await fetch(dataURL, {
                method: 'DELETE',
                headers: {
                  'Content-Type': 'application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                body: JSON.stringify(payload)
              });

              if (!res.ok) {
                const resultJson = await res.json();
                throw (resultJson.message || "Failed when trying to remove data");
              }
              apiTable.value.reload();
            } catch (err) {
              isBadForm.value = true;
              swal.fire({
                icon: 'error',
                text: err
              });
            }
            isRequesting.value = false;
          }
        });
      }
    },
    {
      icon: 'eye',
      title: "Read",
      class: 'bg-green-600 text-light-100',
      // show: (row) => (currentMenu?.can_read)||store.user.data.username==='developer',
      click(row) {
        const tglMin1 = row.date.split('/')[2] + '-' + row.date.split('/')[1] + '-' + row.date.split('/')[0]
        router.push({
          path: `${route.path}/${row.m_kary.id}`,
          query: {
            tsId: tsId,
            karyId: row.m_kary.id,
            tglNew: tglMin1,
          },
        });
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      // show: (row) => (currentMenu?.can_update)||store.user.data.username==='developer',
      click(row) {
        const tglMin = row.date.split('/')[2] + '-' + row.date.split('/')[1] + '-' + row.date.split('/')[0]
        router.push({
          path: `${route.path}/${row.m_kary.id}`,
          query: {
            action: 'Edit',
            tsId: tsId,
            karyId: row.m_kary.id,
            tglNew: tglMin,
          },
        });

      }
    },

  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      scopes: 'GetData',
      searchfield: 'this.date, this.nama_karyawan',
    },
    onsuccess(response) {
      console.log(response)
      response.page = response.current_page;
      response.hasNext = response.has_next;

      response.data = response.data.map(item => ({
        ...item,
        nama_karyawan: item.m_kary.nama_lengkap
      }));
      return response;
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
    headerName: 'Nama Karyawan',
    field: 'nama_karyawan',  // Updated field name
    filter: true,
    sortable: true,
    flex: 1,

    resizable: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Tanggal',
    field: 'date',
    filter: true,
    sortable: true,
    flex: 1,

    resizable: true,
    cellClass: ['border-r', '!border-gray-200', 'justify-start']
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