# Tambah Tipe User

## Pengantar

Semart Api Skeleton menggunakan paradigma yang berbeda dalam memanage user. Hal ini dilakukan agar lebih fleksibel dalam memanage user. User utama pada Semart Api Skeleton adalah `KejawenLab\ApiSkeleton\Security\User`. Semua User pada aplikasi, nantinya akan diganti dengan *object* dari *class* tersebut.

Secara *state diagram*, kondisi saat ini dapat digambarkan sebagai berikut:

![User State](assets/user_state.png)

## Menambah Tipe User Baru

 Untuk menambahkan tipe user baru, langkah-langkahnya adalah sebagai berikut:
 
 - Buat class User dengan implement dari `KejawenLab\ApiSkeleton\Security\Model\AuthInterface`
 
 - Buat class UserProvider dengan implement dari `KejawenLab\ApiSkeleton\Security\Model\UserProviderInterface`
 
 - Daftarkan class UserProvider ke `services.yaml`
 
## Contoh

Untuk melihat contoh penggunaannya, kamu dapat melihat class `KejawenLab\ApiSkeleton\Entity\ApiClient` dan `KejawenLab\ApiSkeleton\ApiClient\UserProvider` serta bagaimana cara mendaftarkannya pada `services.yaml`
