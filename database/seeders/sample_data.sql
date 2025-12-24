-- Tambah Sample Data Pelanggan (jika belum ada)
INSERT INTO pelanggans (nama, email, telepon, alamat, created_at, updated_at) 
SELECT 'Toko Berkah', 'berkah@email.com', '081234567890', 'Jl. Merdeka No. 1', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM pelanggans WHERE nama = 'Toko Berkah');

INSERT INTO pelanggans (nama, email, telepon, alamat, created_at, updated_at) 
SELECT 'PT Sinar Jaya', 'sinar@email.com', '082345678901', 'Jl. Sudirman No. 2', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM pelanggans WHERE nama = 'PT Sinar Jaya');

INSERT INTO pelanggans (nama, email, telepon, alamat, created_at, updated_at) 
SELECT 'Restoran Mewah', 'restoran@email.com', '083456789012', 'Jl. Ahmad Yani No. 3', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM pelanggans WHERE nama = 'Restoran Mewah');

-- Tambah Sample Data Status Pesanan (jika belum ada)
INSERT INTO status_pesanans (nama_status, created_at, updated_at)
SELECT 'Pending', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM status_pesanans WHERE nama_status = 'Pending');

INSERT INTO status_pesanans (nama_status, created_at, updated_at)
SELECT 'Desain Disetujui', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM status_pesanans WHERE nama_status = 'Desain Disetujui');

-- Tambah Sample Data Pesanan
INSERT INTO pesanans (pelanggan_id, status_pesanan_id, tanggal_pesanan, created_at, updated_at)
SELECT 
  (SELECT id FROM pelanggans WHERE nama = 'Toko Berkah' LIMIT 1),
  (SELECT id FROM status_pesanans WHERE nama_status = 'Desain Disetujui' LIMIT 1),
  NOW(),
  NOW(),
  NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM pesanans WHERE pelanggan_id = (SELECT id FROM pelanggans WHERE nama = 'Toko Berkah' LIMIT 1)
);

INSERT INTO pesanans (pelanggan_id, status_pesanan_id, tanggal_pesanan, created_at, updated_at)
SELECT 
  (SELECT id FROM pelanggans WHERE nama = 'PT Sinar Jaya' LIMIT 1),
  (SELECT id FROM status_pesanans WHERE nama_status = 'Desain Disetujui' LIMIT 1),
  NOW(),
  NOW(),
  NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM pesanans WHERE pelanggan_id = (SELECT id FROM pelanggans WHERE nama = 'PT Sinar Jaya' LIMIT 1)
);

INSERT INTO pesanans (pelanggan_id, status_pesanan_id, tanggal_pesanan, created_at, updated_at)
SELECT 
  (SELECT id FROM pelanggans WHERE nama = 'Restoran Mewah' LIMIT 1),
  (SELECT id FROM status_pesanans WHERE nama_status = 'Desain Disetujui' LIMIT 1),
  NOW(),
  NOW(),
  NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM pesanans WHERE pelanggan_id = (SELECT id FROM pelanggans WHERE nama = 'Restoran Mewah' LIMIT 1)
);
