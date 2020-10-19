# Menu pada Semart Api Skeleton

## Pengantar

Menu adalah sebuah hal yang pasti ada disemua aplikasi baik website, desktop maupun mobile.

Pada Semart Api Skeleton, untuk menambahkan menu sangatlah mudah. Karena tidak perlu lagi edit code namun langsung lewat menu GUI "Menu".

## Menu Semart Api Skeleton

Sebelum membahas lebih jauh, untuk menambahkan menu pada Semart Api Skeleton. Anda bisa memilih *Ditampilkan* atau *Tidak*, *Hanya Admin* atau semua.

*) Khusus yang menggunakan 'Semart Generator', menu otomatis ditambahkan.

Cara menambahkan menu :

1. Login ke aplikasi  https://localhost:8000/admin atau sesuai alamat yang tertera ketika menjalankan perintah symfony server:start.
2. Klik Administrator.
3. Klik Menu.
4. Klik Tambah Baru.
5. Pilih menu Induk jika ada.
6. Masukkan kode menu.
7. Masukkan nama menu (nama menu yang akan ditampilkan).
8. Masukkan nama rute, khusus untuk nama rute, jika dicentang *Khusus Admin?*, maka yang dipilih route admin bukan route api. Untuk melihat daftar route dapat menggunakan.

```bash
php bin/console debug:router
```
Nama rute API :

![rute API](https://i.ibb.co/PzRF8Tw/3.png "Nama rute API")

Nama rute yang digunakan tanpa *__invoke*, contoh *kejawenlab_apiskeleton_user_getall*.

Nama rute Admin :

![rute Admin](https://i.ibb.co/5TrdJWn/2.png "Nama rute Admin")

Nama rute admin ditandai dengan adanya kata "Admin", sedangkan untuk nama rute API tidak ada.

Nama rute yang digunakan *kejawenlab_apiskeleton_admin_user_getall__invoke*, sampai *__invoke*.

Contoh, ubah menu USER, maka nama rute yang Anda temukan adalah *kejawenlab_apiskeleton_user_getall*, jika ingin *Khusus Admin?* dicentang atau diaktifkan. Maka diganti dengan *kejawenlab_apiskeleton_admin_user_getall__invoke*.
 
 9. Pilih urutan, ini akan mempengaruhi tata letak atau urutan menu.
 10. Centang *Tampilkan?*, jika ingin menonaktifkan menu tinggal centang dihilangkan.
 11. Simpan.
 
![Menu](https://i.ibb.co/GTvK008/1.png "Menu") 

