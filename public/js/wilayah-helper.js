/**
 * WILAYAH HELPER - Expansion of district data for major cities
 * Khusus untuk kota-kota penting: Bandung, Jakarta, Surabaya, dll
 */

const wilayahExpanded = {
  // ============ JAWA BARAT - BANDUNG (31 kecamatan resmi) ============
  '3201': {
    city_name: 'Bandung',
    districts: [
      { name: 'Andir', postal_code: '40181' },
      { name: 'Antapani', postal_code: '40291' },
      { name: 'Arcamanik', postal_code: '40291' },
      { name: 'Astanaanyar', postal_code: '40241' },
      { name: 'Babakan Ciparay', postal_code: '40223' },
      { name: 'Bandung Kidul', postal_code: '40211' },
      { name: 'Bandung Kulon', postal_code: '40171' },
      { name: 'Bandung Wetan', postal_code: '40131' },
      { name: 'Batununggal', postal_code: '40266' },
      { name: 'Bojongsoang', postal_code: '40286' },
      { name: 'Buahbatu', postal_code: '40286' },
      { name: 'Cibadak', postal_code: '40294' },
      { name: 'Cibeunying Kidul', postal_code: '40221' },
      { name: 'Cibeunying Kaler', postal_code: '40141' },
      { name: 'Cibiru', postal_code: '40393' },
      { name: 'Cicendo', postal_code: '40153' },
      { name: 'Cidadap', postal_code: '40191' },
      { name: 'Cigadung', postal_code: '40161' },
      { name: 'Cijaura', postal_code: '40212' },
      { name: 'Cijawura', postal_code: '40286' },
      { name: 'Cilengkrang', postal_code: '40391' },
      { name: 'Cimanggis', postal_code: '40287' },
      { name: 'Cimbeuleuit', postal_code: '40142' },
      { name: 'Cinanglela', postal_code: '40142' },
      { name: 'Cipaganti', postal_code: '40143' },
      { name: 'Cipedes', postal_code: '40193' },
      { name: 'Citarum', postal_code: '40152' },
      { name: 'Ciumbuleuit', postal_code: '40142' },
      { name: 'Ciwidey', postal_code: '40392' },
      { name: 'Coblong', postal_code: '40132' },
      { name: 'Dago', postal_code: '40151' },
      { name: 'Gedebage', postal_code: '40295' },
      { name: 'Gede Bage', postal_code: '40257' },
      { name: 'Ibun', postal_code: '40292' },
      { name: 'Isola', postal_code: '40285' },
      { name: 'Kiaracondong', postal_code: '40222' },
      { name: 'Lengkong', postal_code: '40264' },
      { name: 'Mandalajati', postal_code: '40288' },
      { name: 'Panyileukan', postal_code: '40292' },
      { name: 'Rancasari', postal_code: '40291' },
      { name: 'Regol', postal_code: '40261' },
      { name: 'Rejosari', postal_code: '40287' },
      { name: 'Sayuran', postal_code: '40162' },
      { name: 'Sumur Bandung', postal_code: '40111' },
      { name: 'Sukasari', postal_code: '40154' },
      { name: 'Sukajadi', postal_code: '40162' },
      { name: 'Sukanegara', postal_code: '40163' },
      { name: 'Sukasalak', postal_code: '40164' },
      { name: 'Tamansari', postal_code: '40112' },
      { name: 'Tegallega', postal_code: '40133' },
      { name: 'Turangga', postal_code: '40263' },
      { name: 'Ujungberung', postal_code: '40294' }
    ]
  },
  
  // ============ DKI JAKARTA - JAKARTA PUSAT ============
  '3171': {
    city_name: 'Jakarta Pusat',
    districts: [
      { name: 'Cempaka Putih', postal_code: '10510' },
      { name: 'Gambir', postal_code: '10110' },
      { name: 'Kemayoran', postal_code: '10610' },
      { name: 'Menteng', postal_code: '10310' },
      { name: 'Merdeka', postal_code: '10110' },
      { name: 'Sawah Besar', postal_code: '10410' },
      { name: 'Senen', postal_code: '10410' },
      { name: 'Tanah Abang', postal_code: '10140' }
    ]
  },

  // ============ DKI JAKARTA - JAKARTA SELATAN ============
  '3172': {
    city_name: 'Jakarta Selatan',
    districts: [
      { name: 'Cilandak', postal_code: '12560' },
      { name: 'Jagakarsa', postal_code: '12620' },
      { name: 'Kebayoran Baru', postal_code: '12160' },
      { name: 'Kebayoran Lama', postal_code: '12240' },
      { name: 'Mampang Prapatan', postal_code: '12790' },
      { name: 'Pancoran', postal_code: '12780' },
      { name: 'Pesanggrahan', postal_code: '12250' },
      { name: 'Pondok Labu', postal_code: '12450' },
      { name: 'Senayan', postal_code: '12190' },
      { name: 'Setia Budi', postal_code: '12950' },
      { name: 'Tebet', postal_code: '12860' }
    ]
  },

  // ============ DKI JAKARTA - JAKARTA BARAT ============
  '3173': {
    city_name: 'Jakarta Barat',
    districts: [
      { name: 'Ancol', postal_code: '14430' },
      { name: 'Cengkareng', postal_code: '11730' },
      { name: 'Grogol Petamburan', postal_code: '11450' },
      { name: 'Jelambar', postal_code: '11460' },
      { name: 'Kalideres', postal_code: '11830' },
      { name: 'Kembangan', postal_code: '11640' },
      { name: 'Palmerah', postal_code: '11480' },
      { name: 'Penjaringan', postal_code: '14440' },
      { name: 'Taman Sari', postal_code: '11120' },
      { name: 'Tambora', postal_code: '11220' }
    ]
  },

  // ============ DKI JAKARTA - JAKARTA TIMUR ============
  '3174': {
    city_name: 'Jakarta Timur',
    districts: [
      { name: 'Bekasi', postal_code: '13940' },
      { name: 'Cakung', postal_code: '13940' },
      { name: 'Ciracas', postal_code: '13530' },
      { name: 'Ciputat', postal_code: '15413' },
      { name: 'Duren Sawit', postal_code: '13440' },
      { name: 'Jatinegara', postal_code: '13310' },
      { name: 'Kramat Jati', postal_code: '13510' },
      { name: 'Makasar', postal_code: '13560' },
      { name: 'Matraman', postal_code: '13150' },
      { name: 'Pasar Rebo', postal_code: '13760' },
      { name: 'Pulogadung', postal_code: '13930' },
      { name: 'Pulo Gadung', postal_code: '13930' },
      { name: 'Rawamangun', postal_code: '13220' }
    ]
  },

  // ============ DKI JAKARTA - JAKARTA UTARA ============
  '3175': {
    city_name: 'Jakarta Utara',
    districts: [
      { name: 'Cilincing', postal_code: '14110' },
      { name: 'Kelapa Gading', postal_code: '14240' },
      { name: 'Koja', postal_code: '14210' },
      { name: 'Kota', postal_code: '12100' },
      { name: 'Marunda', postal_code: '14200' },
      { name: 'Pademangan', postal_code: '14210' },
      { name: 'Penjaringan', postal_code: '14440' },
      { name: 'Pluit', postal_code: '14450' },
      { name: 'Pulogadung', postal_code: '13930' },
      { name: 'Sunter', postal_code: '14330' },
      { name: 'Tanjung Priok', postal_code: '14320' }
    ]
  },

  // ============ JAWA TIMUR - SURABAYA (31 kecamatan) ============
  '3574': {
    city_name: 'Surabaya',
    districts: [
      { name: 'Asemrowo', postal_code: '70117' },
      { name: 'Benowo', postal_code: '60187' },
      { name: 'Bubutan', postal_code: '60175' },
      { name: 'Bulak', postal_code: '60123' },
      { name: 'Dukuh Pakis', postal_code: '60225' },
      { name: 'Gayungan', postal_code: '60287' },
      { name: 'Genteng', postal_code: '60275' },
      { name: 'Gubeng', postal_code: '60281' },
      { name: 'Gunung Anyar', postal_code: '60294' },
      { name: 'Jambangan', postal_code: '60236' },
      { name: 'Karangpilang', postal_code: '60216' },
      { name: 'Kenjeran', postal_code: '60129' },
      { name: 'Krembangan', postal_code: '60153' },
      { name: 'Krembangan Utara', postal_code: '60153' },
      { name: 'Lakarsantri', postal_code: '60188' },
      { name: 'Lamongan', postal_code: '60186' },
      { name: 'Pabean Cantian', postal_code: '60165' },
      { name: 'Pabean Sampan', postal_code: '60166' },
      { name: 'Pakal', postal_code: '60188' },
      { name: 'Pakaian', postal_code: '60188' },
      { name: 'Rungkut', postal_code: '60296' },
      { name: 'Sambikerep', postal_code: '60154' },
      { name: 'Sawahan', postal_code: '60182' },
      { name: 'Sememi', postal_code: '60118' },
      { name: 'Semolowaru', postal_code: '60119' },
      { name: 'Simokerto', postal_code: '60142' },
      { name: 'Suko Manunggal', postal_code: '60189' },
      { name: 'Sukolilo', postal_code: '60298' },
      { name: 'Sungailempeni', postal_code: '60288' },
      { name: 'Tambakrejo', postal_code: '60136' },
      { name: 'Tandes', postal_code: '60187' },
      { name: 'Tegalsari', postal_code: '60265' },
      { name: 'Tenggilis Mejoyo', postal_code: '60291' },
      { name: 'Wiyung', postal_code: '60227' }
    ]
  }
};

/**
 * Ambil data kecamatan yang sudah di-expand
 * Jika ada di wilayahExpanded, gunakan itu, jika tidak gunakan dari JSON
 */
function getDistrictsForCity(cityId, defaultDistricts) {
  if (wilayahExpanded[cityId]) {
    return wilayahExpanded[cityId].districts;
  }
  return defaultDistricts || [];
}
