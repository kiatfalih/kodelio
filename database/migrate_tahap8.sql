-- Tahap 8-9: tipe soal mini game. Aman dijalankan ulang di MariaDB (XAMPP).
-- kind: choice | arrange | match | findbug. payload (JSON) dipakai oleh arrange/match/findbug.
ALTER TABLE questions ADD COLUMN IF NOT EXISTS kind VARCHAR(12) NOT NULL DEFAULT 'choice', ADD COLUMN IF NOT EXISTS payload TEXT NULL;
