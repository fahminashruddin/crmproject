# 📋 SETUP LENGKAP JADWAL PRODUKSI & INVENTORY

## ✅ Yang Sudah Dibuat:

### 1. **Routes** (routes/web.php)
```php
Route::get('/jadwal-produksi', [DesainController::class, 'jadwalProduksi'])->name('desain.jadwal-produksi');
Route::post('/jadwal-produksi', [DesainController::class, 'storeJadwalProduksi'])->name('desain.jadwal-produksi.store');
Route::get('/inventory', [DesainController::class, 'inventory'])->name('desain.inventory');
Route::post('/inventory', [DesainController::class, 'storeInventory'])->name('desain.inventory.store');
```

### 2. **Controller Methods** (DesainController)
- `jadwalProduksi()` - Tampil list jadwal produksi
- `storeJadwalProduksi()` - Simpan jadwal produksi baru
- `inventory()` - Tampil list inventory
- `storeInventory()` - Simpan inventory baru

### 3. **Database Migrations**
- Updated: `produksis` table (tambah kolom `status_produksi`)
- Sudah ada: `inventorys` table

### 4. **Views (Blade Templates)**
- `desain/jadwal-produksi.blade.php` - Dengan form modal untuk input
- `desain/inventory.blade.php` - Dengan form modal untuk input

### 5. **Seeder**
- `ProduksiSeeder` - Untuk generate sample data

---

## 🚀 SETUP STEPS:

### **Step 1: Jalankan Migration**
```bash
php artisan migrate
```

Output diharapkan:
```
Migrating: 2025_12_24_create_inventorys_table
Migrated:  2025_12_24_create_inventorys_table (123ms)
```

### **Step 2: Jalankan Seeder (Generate Data Sample)**
```bash
php artisan db:seed --class=ProduksiSeeder
```

Output diharapkan:
```
Database seeding completed successfully.
ProduksiSeeder berhasil dijalankan!
```

### **Step 3: Cek Database**

Buka MySQL dan jalankan:
```sql
-- Cek tabel produksis
SELECT * FROM produksis LIMIT 5;

-- Cek tabel inventorys
SELECT * FROM inventorys LIMIT 5;
```

Seharusnya ada data sample.

---

## 📊 ALUR DATA LENGKAP:

### **Jadwal Produksi Flow:**
```
1. Admin membuat Pesanan di /admin/orders
   ↓
2. Pesanan masuk tabel: pesanans
   ↓
3. Desainer (User dengan role Desain) login ke /desain
   ↓
4. Klik menu "Jadwal Produksi"
   ↓
5. Lihat list jadwal dari tabel: produksis
   ↓
6. Klik "Tambah Jadwal"
   ↓
7. Pilih Pesanan + Tanggal + Status → Klik Simpan
   ↓
8. Data tersimpan di tabel: produksis
   ↓
9. List otomatis terupdate
```

### **Inventory Flow:**
```
1. Desainer (atau Admin) login ke /desain
   ↓
2. Klik menu "Inventory"
   ↓
3. Lihat list stok dari tabel: inventorys
   ↓
4. Status otomatis dihitung:
   - Jumlah > 10 → Tersedia (Hijau)
   - Jumlah ≤ 10 → Menipis (Kuning)
   - Jumlah ≤ 0 → Habis (Merah)
   ↓
5. Klik "Tambah Stok"
   ↓
6. Isi Form: Nama Produk, Jumlah, Satuan, Lokasi
   ↓
7. Klik Simpan
   ↓
8. Data tersimpan di tabel: inventorys
   ↓
9. List otomatis terupdate dengan status
```

---

## 📝 TABEL STRUCTURE:

### **Tabel: produksis**
```
id                  INT (Primary Key)
pesanan_id          INT (Foreign Key → pesanans.id)
tanggal_mulai       DATE
tanggal_selesai     DATE
status_produksi     ENUM ('pending', 'berjalan', 'selesai', 'tertunda')
catatan             TEXT
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

### **Tabel: inventorys**
```
id                  INT (Primary Key)
produksi_id         INT (Foreign Key → produksis.id) [OPTIONAL]
nama_produk         VARCHAR(255)
jumlah              INT
satuan              VARCHAR(50)
lokasi              VARCHAR(255)
keterangan          TEXT
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

---

## 🧪 TESTING:

### **Test Jadwal Produksi:**

1. Login dengan akun Desain: `desain@percetakan.com`
2. Klik menu "Jadwal Produksi"
3. Klik "Tambah Jadwal"
4. Isi form:
   - Pesanan: (pilih dari dropdown)
   - Tanggal Mulai: 2025-12-25
   - Tanggal Selesai: 2025-12-31
   - Status: berjalan
   - Catatan: "Test input"
5. Klik "Simpan"
6. Seharusnya redirect ke halaman jadwal dengan pesan sukses
7. Data baru tampil di tabel

### **Test Inventory:**

1. Masih login, klik menu "Inventory"
2. Klik "Tambah Stok"
3. Isi form:
   - Nama Produk: Kertas F4 80 gr
   - Jumlah: 25
   - Satuan: ream
   - Lokasi: Rak A3
   - Keterangan: "Test stock baru"
4. Klik "Simpan"
5. Seharusnya redirect dengan pesan sukses
6. Data baru tampil dengan status "Tersedia" (hijau)
7. Coba tambah stok dengan jumlah 5 → Status berubah jadi "Menipis" (kuning)

---

## 🛠️ TROUBLESHOOTING:

### ❌ Error: "SQLSTATE[42S02]"
**Solusi:** Jalankan migration:
```bash
php artisan migrate
```

### ❌ Dropdown pesanan kosong
**Solusi:** Pastikan sudah ada data di tabel pesanans:
```bash
php artisan db:seed
```

### ❌ Form tidak muncul
**Solusi:** Cek browser console (F12) untuk error JavaScript. Pastikan Lucide Icons ter-load dengan baik.

### ❌ Data tidak tersimpan
**Solusi:** Cek file `.env`, pastikan DB_* config benar.

---

## 📦 NEXT STEPS (Opsional Enhancements):

1. **Edit & Delete Jadwal Produksi**
   - Tambah route: `PATCH /jadwal-produksi/{id}`, `DELETE /jadwal-produksi/{id}`
   - Tambah method di controller

2. **Edit & Delete Inventory**
   - Tambah route untuk update & delete

3. **Update Status Jadwal**
   - Tambah button "Mulai Produksi" / "Selesaikan"
   - Update status_produksi via AJAX

4. **Notifikasi Stok Menipis**
   - Auto email ke admin ketika jumlah ≤ 10

5. **Export Report**
   - Export jadwal produksi ke Excel
   - Export inventory ke Excel

---

## 📚 FILES YANG DIMODIFIKASI:

1. `routes/web.php` - Tambah routes
2. `app/Http/Controllers/DesainController.php` - Tambah methods
3. `database/migrations/2025_10_24_151903_create_produksis_table.php` - Update schema
4. `resources/views/desain/jadwal-produksi.blade.php` - Create view
5. `resources/views/desain/inventory.blade.php` - Create view
6. `database/seeders/ProduksiSeeder.php` - Update seeder

---

## ✨ NOTES:

- Form menggunakan **Modal (Pop-up)** agar tidak reload halaman
- Status dihitung otomatis di view (tidak di database)
- Validasi form dilakukan di server-side
- Semua error/success messages ditampilkan

Sekarang siap untuk ditest! 🚀
