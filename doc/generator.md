# Semart Api Generator

## Pengantar

Salah satu fitur utama dari Semart Api Skeleton adalah Semart Generator, yaitu sebuah alat bantu (*tool*) untuk membuat RESTApi serta dokumentasinya secara cepat.

Fitur utama Semart Generator antara lain:

>
> * Membuat RESTApi lengkap dengan *audit trail*-nya
>
> * Melakukan mapping menu dan hak akses terhadap RestApi yang di-*generate*
>
> * Men-*generate* beberapa *template class* seperti *form* dan *query extension class* agar mudah dimodifikasi
>

## Cara menggunakan Semart Generator

Anggaplah Kamu sudah membuat *interface* dan *entity* sebagaimana pada halaman [README.md](../README.md#cara-penggunaan), maka Kamu cukup menjalankan perintah sebagai berikut:

```bash
php bin/console semart:generate Test
```

Bila Kamu menggunakan Docker, maka Kamu dapat menjalankan perintah:

```bash
docker-compose exec app bash -c "php bin/console semart:generate Test"
```

Ketika muncul pertanyaan, Kamu cukup memilih `y` maka hasilnya adalah sebagai berikut:

![Semart Generator](assets/semart_generator_test.png)

Dari *output* tersebut terlihat jelas apa saja *file* yang di-*generate*. Selain itu juga, Semart Generator akan mendaftarkan menu baru sesuai dengan *entity* yang di-*generate* sebagai berikut:

![Semart Generator](assets/permission_generate.png)

Tidak hanya itu, Semart Generator juga secara otomatis membuatkan dokumentasi Api sesuai dengan *entity* yang di-*generate* sebagaimana tampak pada halaman README.

Sangat mudah sekali, bukan?
