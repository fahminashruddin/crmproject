-- Insert sample data untuk Jadwal Produksi

-- Pastikan ada status pesanan
INSERT INTO status_pesanans (nama_status, created_at, updated_at) VALUES 
('Pending', NOW(), NOW()),
('Diproses', NOW(), NOW()),
('Selesai', NOW(), NOW())
ON DUPLICATE KEY UPDATE nama_status = nama_status;

-- Insert sample pelanggan
INSERT INTO pelanggans (nama, alamat, no_telepon, email, created_at, updated_at) VALUES 
('Toko Berkah', 'Jalan Merdeka 123', '081234567890', 'toko@berkah.com', NOW(), NOW()),
('PT Sinar Jaya', 'Jalan Sudirman 456', '082345678901', 'info@sinarjaya.com', NOW(), NOW()),
('Restoran Mewah', 'Jalan Gatot Subroto 789', '083456789012', 'manager@restoran.com', NOW(), NOW())
ON DUPLICATE KEY UPDATE nama = nama;

-- Insert sample pengguna (untuk pesanan)
INSERT INTO penggunas (nama, email, password, role_id, created_at, updated_at) VALUES 
('Admin Pesanan', 'admin@pesanan.com', '$2y$10$dummyhashpassword1234', 1, NOW(), NOW()),
('Staff Pesanan', 'staff@pesanan.com', '$2y$10$dummyhashpassword1234', 2, NOW(), NOW())
ON DUPLICATE KEY UPDATE nama = nama;

-- Insert sample pesanan
INSERT INTO pesanans (tanggal_pesanan, catatan, pelanggan_id, pengguna_id, status_pesanan_id, created_at, updated_at) VALUES 
('2025-01-15', 'Pesanan regular dengan printing custom', 1, 1, 1, NOW(), NOW()),
('2025-01-16', 'Pesanan rush dengan deadline ketat', 2, 2, 1, NOW(), NOW()),
('2025-01-17', 'Pesanan besar dengan multiple item', 3, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE tanggal_pesanan = tanggal_pesanan;
