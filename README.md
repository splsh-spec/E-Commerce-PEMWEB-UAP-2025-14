KELOMPOK 14
ANGGOTA
1. DIMAZ ARTA MAULIDA (245150600111023)
2. MOHAMMAD YAN HASBY (245150601111011)

# 📦 E-Commerce Platform Documentation

Dokumentasi ini menjelaskan fitur, alur kerja, dan peran pengguna dalam sistem e-commerce untuk menjual barang-barang olahraga yang dikembangkan menggunakan **Laravel**.

---

## 🌐 Arsitektur Umum Sistem

Sistem e-commerce ini terdiri dari tiga peran utama:

* **Admin** — Pengelola penuh sistem
* **Seller** — Pemilik toko yang menjual produk
* **Member** — Pelanggan yang membeli produk

Setiap peran memiliki antarmuka dan hak akses yang berbeda sesuai fungsinya.

---

# 👨‍💼 ADMIN PANEL

Admin memiliki akses penuh ke seluruh sistem. Fitur utama:

### 1. Manajemen Pengguna

* Melihat semua pengguna (admin, seller, member)
* Menambah, mengedit, atau menghapus akun

### 2. Manajemen Toko & Seller

* Mengelola data toko milik seller
* Validasi dan verifikasi seller

---

# 🏪 SELLER PANEL

Seller memiliki dashboard khusus untuk mengelola toko dan produk.

## 1. Kelola Produk

CRUD (Create, Read, Update, Delete) lengkap.

### Tambah Produk

* Input kategori, nama, deskripsi, kondisi, harga, berat, stok
* Upload gambar produk → tersimpan di `storage/app/public/products`
* Gambar ditampilkan melalui symlink `public/storage/products`

### Edit Produk

* Mengubah detail produk
* Mengganti gambar produk lama

### Hapus Produk

* Menghapus produk yang tidak ingin dijual lagi

---

# 👤 MEMBER / CUSTOMER

Member adalah pengguna yang membeli produk.

## 1. Homepage

* Menampilkan daftar semua produk aktif
* Fitur filter berdasarkan kategori

## 2. Halaman Detail Produk

Menampilkan:

* Deskripsi lengkap produk
* Gambar produk
* Review produk
* Nama toko
* Tombol **Beli**

## 3. Checkout

* Mengisi alamat pengiriman
* Memilih metode pengiriman (JNE, J&T, dll.)
* Perhitungan total pembayaran berdasarkan ongkir dan jumlah produk

## 4. Transaksi

* Melihat semua pesanan yang pernah dibuat
* Status pesanan (pending, paid, shipped, completed)

## 5. Dompet / Wallet

* Pengisian saldo
* Pembayaran pesanan menggunakan wallet

---

# 🔒 SISTEM AUTENTIKASI & ROLE

Sistem menggunakan middleware:

```php
'member' → hanya untuk customer
'seller' → hanya untuk penjual
'admin'  → hanya untuk admin
```

Terdapat juga **RedirectByRole Middleware** yang mengarahkan user ke dashboard sesuai peran:

* Admin → `/admin/dashboard`
* Seller → `/seller/dashboard`
* Member → `/home`

---

# 📤 Penyimpanan Gambar Produk

Gambar disimpan pada folder:

```
storage/app/public/products
```

Kemudian diakses melalui URL berikut:

```
/public/storage/products/namafile.jpg
```

Dengan symlink:

```
php artisan storage:link
```

---

# 🧩 Struktur URL Utama

| Role          | URL                 |
| ------------- | ------------------- |
| Admin         | `/admin/dashboard`  |
| Seller        | `/seller/dashboard` |
| Seller Produk | `/seller/products`  |
| Member        | `/` (home)          |
| Checkout      | `/checkout`         |

---

# ✔️ Kesimpulan

Platform e-commerce ini memiliki arsitektur:

* Sistem multirole (Admin, Seller, Member)
* Manajemen produk dan transaksi yang terstruktur
* Penyimpanan gambar produk yang aman
* Dashboard berbeda untuk setiap role
* CRUD produk lengkap untuk seller
* Sistem transaksi

