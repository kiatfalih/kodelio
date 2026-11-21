-- Tahap 6-7: aturan pengecekan latihan kode (dijalankan di server). Aman dijalankan ulang di MariaDB (XAMPP).
ALTER TABLE lessons ADD COLUMN IF NOT EXISTS check_rules TEXT NULL;
