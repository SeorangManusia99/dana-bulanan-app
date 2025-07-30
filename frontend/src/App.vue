<template>
  <div id="app" class="container">
    <h1>💰 Pengelola Dana Bulanan</h1>
    
    <form @submit.prevent="simpanTransaksi" class="form-card">
      <h3>{{ isEditing ? 'Ubah Transaksi' : 'Tambah Transaksi Baru' }}</h3>
      <div class="form-group">
        <label>Deskripsi</label>
        <input type="text" v-model="form.deskripsi" placeholder="cth: Bayar kos" required>
      </div>
      <div class="form-group">
        <label>harga (Rp)</label>
        <input type="number" v-model.number="form.jumlah" placeholder="cth: 500000" required>
      </div>
      <div class="form-group">
        <label>Jenis</label>
        <select v-model="form.jenis" required>
          <option disabled value="">Pilih jenis...</option>
          <option value="pemasukan">Pemasukan</option>
          <option value="pengeluaran">Pengeluaran</option>
        </select>
      </div>
      <div class="form-group">
        <label>Tanggal</label>
        <input type="date" v-model="form.tanggal" required>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn-simpan">{{ isEditing ? 'Update' : 'Simpan' }}</button>
        <button type="button" @click="resetForm" v-if="isEditing" class="btn-batal">Batal</button>
      </div>
    </form>

    <div class="summary-card">
        <h3>Ringkasan Bulan Ini</h3>
        <p class="pemasukan">Total Pemasukan: <strong>Rp {{ formatRupiah(totalPemasukan) }}</strong></p>
        <p class="pengeluaran">Total Pengeluaran: <strong>Rp {{ formatRupiah(totalPengeluaran) }}</strong></p>
        <hr>
        <p>Saldo Akhir: <strong :class="saldoAkhir >= 0 ? 'pemasukan' : 'pengeluaran'">Rp {{ formatRupiah(saldoAkhir) }}</strong></p>
    </div>

    <h2>Riwayat Transaksi</h2>
    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Deskripsi</th>
          <th>Jumlah</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="transaksi.length === 0">
          <td colspan="4">Belum ada transaksi.</td>
        </tr>
        <tr v-for="trx in transaksi" :key="trx.id">
          <td>{{ trx.tanggal }}</td>
          <td>{{ trx.deskripsi }}</td>
          <td :class="trx.jenis === 'pemasukan' ? 'pemasukan' : 'pengeluaran'">
            {{ trx.jenis === 'pemasukan' ? '+' : '-' }} Rp {{ formatRupiah(trx.jumlah) }}
          </td>
          <td>
            <button @click="editTransaksi(trx)" class="btn-edit">✏️</button>
            <button @click="hapusTransaksi(trx.id)" class="btn-hapus">🗑️</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

// Menjadi URL backend Anda yang sudah online (ini hanya contoh):
const API_BASE_URL = 'https://domain-hosting-backend-anda.com/api'; 

export default {
  data() {
    return {
      transaksi: [],
      form: {
        id: null,
        deskripsi: '',
        jumlah: '',
        jenis: '',
        tanggal: new Date().toISOString().slice(0, 10),
      },
      isEditing: false,
    };
  },
  computed: {
    totalPemasukan() {
      return this.transaksi
        .filter(t => t.jenis === 'pemasukan')
        .reduce((sum, t) => sum + parseFloat(t.jumlah), 0);
    },
    totalPengeluaran() {
      return this.transaksi
        .filter(t => t.jenis === 'pengeluaran')
        .reduce((sum, t) => sum + parseFloat(t.jumlah), 0);
    },
    saldoAkhir() {
      return this.totalPemasukan - this.totalPengeluaran;
    },
  },
  methods: {
    // R: Read all transactions
    async ambilData() {
      try {
        const response = await axios.get(`${API_BASE_URL}/read.php`);
        this.transaksi = response.data;
      } catch (error) {
        console.error("Gagal mengambil data:", error);
      }
    },
    // C & U: Create or Update a transaction
    async simpanTransaksi() {
      try {
        const url = this.isEditing ? `${API_BASE_URL}/update.php` : `${API_BASE_URL}/create.php`;
        await axios.post(url, this.form);
        alert(`Transaksi berhasil ${this.isEditing ? 'diupdate' : 'disimpan'}!`);
        this.resetForm();
        this.ambilData();
      } catch (error) {
        console.error("Gagal menyimpan transaksi:", error);
      }
    },
    // D: Delete a transaction
    async hapusTransaksi(id) {
      if (confirm("Anda yakin ingin menghapus transaksi ini?")) {
        try {
          await axios.post(`${API_BASE_URL}/delete.php`, { id: id });
          alert("Transaksi berhasil dihapus!");
          this.ambilData();
        } catch (error) {
          console.error("Gagal menghapus transaksi:", error);
        }
      }
    },
    // Helper function to populate form for editing
    editTransaksi(trx) {
      this.isEditing = true;
      this.form = { ...trx };
      window.scrollTo(0, 0); // Gulir ke atas halaman
    },
    // Helper function to clear the form
    resetForm() {
      this.isEditing = false;
      this.form = {
        id: null,
        deskripsi: '',
        jumlah: '',
        jenis: '',
        tanggal: new Date().toISOString().slice(0, 10),
      };
    },
    // Helper function to format numbers to Rupiah
    formatRupiah(angka) {
      return new Intl.NumberFormat('id-ID').format(angka);
    },
  },
  mounted() {
    this.ambilData();
  },
};
</script>

<style>
:root {
  --primary-color: #4CAF50;
  --secondary-color: #f44336;
  --bg-color: #f5f5f5;
  --card-bg-color: #ffffff;
  --text-color: #333;
  --border-color: #ddd;
}
body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  background-color: var(--bg-color);
  color: var(--text-color);
  margin: 0;
}
.container {
  max-width: 800px;
  margin: 2rem auto;
  padding: 1rem;
}
h1, h2, h3 {
  color: var(--text-color);
  text-align: center;
}
.form-card, .summary-card {
  background: var(--card-bg-color);
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  padding: 1.5rem;
  margin-bottom: 2rem;
}
.form-group {
  margin-bottom: 1rem;
}
.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
}
input, select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid var(--border-color);
  border-radius: 4px;
  box-sizing: border-box;
}
.form-actions {
  display: flex;
  gap: 1rem;
}
button {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  color: white;
  font-weight: bold;
}
.btn-simpan { background-color: var(--primary-color); }
.btn-batal { background-color: #757575; }
.btn-edit { background-color: #ff9800; }
.btn-hapus { background-color: var(--secondary-color); }

.summary-card p {
  font-size: 1.1rem;
  margin: 0.5rem 0;
}
.pemasukan { color: #2e7d32; }
.pengeluaran { color: #d32f2f; }

table {
  width: 100%;
  border-collapse: collapse;
  background: var(--card-bg-color);
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
th, td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
}
th {
  background-color: #e0e0e0;
}
td:last-child {
  display: flex;
  gap: 0.5rem;
}
</style>