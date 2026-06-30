# Task 3: Pengelolaan Struktur Gaji Pokok

## Deskripsi
Membuat fungsionalitas untuk mendefinisikan struktur gaji dasar (basic salary) yang spesifik untuk setiap karyawan.

## Kebutuhan
1. **Struktur Database (Migration):**
   - `salary_structures`: `id` (PK), `employee_id` (FK ke employees), `basic_salary` (decimal 15,2), `effective_date` (date), `timestamps`
2. **Model & Relasi:** Membuat model `SalaryStructure` yang berelasi `belongsTo` dengan `Employee`.
3. **Logika CRUD & Otorisasi:** Fitur pengelolaan struktur gaji. Hak akses dan pengelolaan fitur ini **hanya untuk role `finance`**. Menyediakan validasi yang memastikan setiap karyawan memiliki satu struktur gaji aktif per periode (tidak boleh ganda untuk tanggal efektif yang sama).
4. **Data Dummy (Seeder):** `SalaryStructureSeeder` dengan contoh: Karyawan 'EMP-001' memiliki `basic_salary` Rp 8.000.000 dengan `effective_date` 2023-01-01.

## Kriteria Selesai (Definition of Done)
- [ ] Migration dan Model `SalaryStructure` dibuat dengan relasi yang tepat ke tabel `employees`.
- [ ] CRUD untuk manajemen gaji pokok beroperasi dengan baik.
- [ ] Terdapat validasi unique untuk `employee_id` dan `effective_date`.
- [ ] Modul ini dilindungi oleh otorisasi khusus role `finance`.
- [ ] Seeder berjalan dan mengenerate data gaji pokok contoh untuk beberapa karyawan.
