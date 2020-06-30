>   Smart Risk Management For Receiving Web-Based Assistance <br>
>   Kelas B, Group 2
<br>

## Kelompok
-   Khibar Pusaka
-   Reza Zulfan Azmi
-   Andi Purwanto
-   Ismi Nururrizqi (17090042)
<br>

## HomeWork Sing Uwis
### 8th Homework: 
#### Migration & Seeding
1. Migration for ALL TABLES. **[OK][SEMUA]**
2. Seeding for ALL TABLES. **[OK][SEMUA]**
3. Seeding at least 25 rows per Tables **[OK][SEMUA]**
<br>

### 9th Homework 
#### Authentication
1. Registration **[OK]**
2. Email Verification **[OK]**
3. Login **[OK]**
4. Logout **[OK]**
5. Change Password **[OK]**
6. Forgot Password **[OK]**
<br>

### 10th Homework
#### A. Image Upload
1. Image upload in Create Page in every CRUD. Only JPEG, JPG, PNG, & GIF are allowed.
2. Image upload in Edit Page in every CRUD. Only JPEG, JPG, PNG, & GIF are allowed.
3. Show the uploaded image in Show Page in every CRUD.
4. Show the uploaded image in Edit Page in every CRUD.
    -   Category **[OK]**
    -   Donasi **[OK]**
    -   Pengurus
    -   Donatur
    -   Penerima
#### B. PDF Upload
1. PDF upload in Create Page in every CRUD. Only PDF are allowed.
2. PDF upload in Edit Page in every CRUD. Only PDF are allowed.
3. Show link of the uploaded PDF in Show Page in every CRUD.
4. Show link of the uploaded PDF in Edit Page in every CRUD.
    -   Category **[OK]**
    -   Donasi **[OK]**
    -   Pengurus
    -   Donatur
    -   Penerima
<br>

### 11th Homework
#### Other Topic
-   Topik Sistem ("Social Login")
<br>


## Deskripsi Sistem
### Role dan Peran Masing2
1.  Admin 
    -   Management Pengurus user account
    -   Management Donatur user account
    -   Activity log all role
    -   Profile, change pwd
2.  Pengurus
    -   Management Penerima
    -   Management Donatur without user account
    -   Management Donasi
    -   Management Kategori Donasi
    -   Dukungan Layanan for Donatur
    -   Profile, change pwd
3.  Donatur
    -   Donasi Donatur
    -   Dukungan Layanan from Pengurus
    -   Profile, change pwd
<br>

### Login
-   Admin
    -   Email: admin@gmail.com
    -   Password: admin
-   Pengurus, Donatur
    -   Email: **liat database users sesuai role**
    -   Password: **sama sesuai email**
<br>

# Cara Install
## Clone Dari Github
-   Open terminal / git bash
-   git clone [url_github]
-   cd [nama_repo]
-   composer install
-   cp .env.example .env
-   setting database and email konfigurasi di .env
-   php artisan key:generate
-   php artisan migrate:fresh --seed
<br>

## Perbaharui Repo Lokal Dr Remote
-   git pull [nama_remote] [nama_branch]
-   php artisan migrate:fresh --seed
<br>

# DEMO
http://laravel-b2.tegalian.com/

tester1@mailinator.com : 123
