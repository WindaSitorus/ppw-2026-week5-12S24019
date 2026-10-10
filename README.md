# 📚 SIPUS-Del (Sistem Informasi Perpustakaan)

Proyek ini adalah pengembangan purwarupa Sistem Informasi Perpustakaan (SIPUS) berbasis web. Proyek ini dikembangkan sebagai pemenuhan **Tugas Mandiri Modul 5 Praktikum Pemrograman & Pengujian Web (PPW) 2026** di Institut Teknologi Del. 

Aplikasi ini berfokus pada implementasi arsitektur Model-View-Controller (MVC), interaksi *database* menggunakan Eloquent ORM, dan perancangan antarmuka pengguna yang rapi menggunakan Bootstrap 5.

## 👨‍💻 Informasi Pengembang
* **Nama**: Winda N.V. Sitorus
* **NIM**: 12S24019
* **Program Studi**: Sistem Informasi
* **Mata Kuliah**: Pemrograman & Pengujian Web 2026

## 🛠️ Spesifikasi Teknologi
* **Bahasa**: PHP 8.5
* **Framework**: Laravel 11
* **Database**: SQLite
* **Frontend**: HTML5, CSS3, Blade Templating
* **Styling & UI**: Bootstrap 5 (dengan kustomisasi warna Del Royal Purple `#4C1D95`)
* **Environment**: Windows, Laragon / VS Code

## ✨ Fitur & Konsep Utama yang Diimplementasikan
1. **Arsitektur MVC**: Pemisahan logika secara terstruktur antara `BukuController`, Model (`Buku`, `Kategori`), dan View (`buku/index.blade.php`).
2. **Eloquent ORM & Relasi Antar Tabel**: Implementasi relasi `belongsTo` dan `hasMany` antara entitas Buku dan Kategori.
3. **Eager Loading**: Optimalisasi *query database* untuk mencegah masalah *N+1 query* saat memuat data relasional.
4. **Database Seeder & Factory**: Otomatisasi pembuatan 50 data buku tiruan (*dummy data*) untuk keperluan pengujian tampilan (*testing*).
5. **Blade Components**: Penggunaan komponen layout yang dapat didaur ulang (`<x-layout>`) untuk konsistensi struktur halaman.
6. **Bootstrap 5 Pagination**: Sistem navigasi halaman otomatis (`Paginator::useBootstrapFive()`) yang dikonfigurasi melalui `AppServiceProvider` untuk menangani volume data yang besar.

## 🚀 Panduan Instalasi (Untuk Asisten Lab/Dosen)

Jika ingin menjalankan *repository* ini di komputer lain, ikuti langkah-langkah berikut:

**1. Clone Repository**
```bash
git clone [https://github.com/WindaSitorus/ppw-2026-week5-12S24019.git](https://github.com/WindaSitorus/ppw-2026-week5-12S24019.git)
cd ppw-2026-week5-12S24019