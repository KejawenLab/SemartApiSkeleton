# Permission pada Semart Api Skeleton

## Pengantar

Bila Kamu sudah pernah menggunakan [SemartSkeleton](https://github.com/KejawenLab/SemartSkeleton) sebelumnya, pasti tidak asing dengan konsep *permission* pada [SemartApiSkeleton](https://github.com/KejawenLab/SemartApiSkeleton) karena mengusung konsep yang sama.

Secara sederhana, konsep *permission* pada Semart Api Skeleton dapat digambarkan sebagai berikut:

![Permission](assets/permission.png)

Bila Kamu menggunakan [Semart Generator](generator.md), secara otomatis menu (sesuai dengan nama *entity*) akan ditambahkan pada *database*, kemudian *permission* akan ditambahkan pada semua *group* yang ada pada *database* dengan *permission* `false` per *action* sebagai *default*. 

Kecuali untuk *group Super Admin* maka akan di-*set* `true` pada semua *action*-nya. Untuk mengetahui *group Super Admin*, Kamu dapat melihatnya pada *file* `.env` yaitu nilai dari `APP_SUPER_ADMIN`.

## 
