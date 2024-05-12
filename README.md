## Setup

1. **Pastikan XAMPP Sudah Berjalan:**

Pastikan XAMPP sudah dijalankan di komputer Anda. XAMPP dibutuhkan untuk menjalankan server web lokal, yang diperlukan untuk menjalankan aplikasi.

2. **Git Clone:**

Buka terminal atau command prompt, lalu arahkan ke direktori `htdocs` di folder instalasi XAMPP. Kemudian jalankan perintah berikut untuk mengunduh kode aplikasi dari repositori Git:
```
git clone https://github.com/supermancyber/supermancyber.git
```

3. **Jalankan Aplikasi:**

Setelah proses pengunduhan selesai, buka browser web Anda dan ketikkan URL `localhost/supermancyber` di bilah alamat. Ini akan mengarahkan Anda ke aplikasi web "supermancyber".

4. **Import Database:**

Buka browser web Anda dan navigasikan ke `localhost/phpmyadmin`. Masuk ke phpMyAdmin menggunakan kredensial default (biasanya "root" tanpa kata sandi). Setelah itu, buka database tersebut dan pilih opsi "Import". Pilih file `db.sql` untuk di-import di phpMyAdmin. Setelah import selesai, skema database akan siap digunakan oleh aplikasi.