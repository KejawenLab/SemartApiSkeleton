# Sandbox Semart Api Skeleton

## Pengantar

>
> Semart Api Skeleton secara otomatis membuatkan Dokumentasi Api sesuai spesifikasi [OpenApi](https://swagger.io/) menggunakan [NelmioApiDoc](https://symfony.com/doc/master/bundles/NelmioApiDocBundle/index.html)
>

## Sandbox URL

>
> Untuk merubah default sandbox URL, Kamu dapat melakukannya dengan mengubah konfigurasi NelmioApiDoc pada file `config/packages/nelmio_api_doc.yaml` sebagai berikut:
>

```yaml
nelmio_api_doc:
    documentation:
        host: staging.kejawenlab.com
        schemes: [http, https]
```

## Menggunakan Sandbox

> 
> Untuk menggunakan cukup menekan tombol `Try it out` kemudian mengisikan parameter sesuai dengan yang tercantum pada Sandbox
>
> *NB:* Bila menemui kendala `401 Unauthorized` Kamu dapat melakukan login terlebih dahulu
>

## Request Format

![Api Doc](assets/request_format.png)

>
> NelmioApiDoc menggunakan annotation `@Swagger\Annotations\Parameter()` untuk membuat request format pada Sandbox
>
> *NB:* Kamu dapat membaca dan melihat penggunaan annotation tersebut pada folder `Controller`
>

## Response Format

>
> NelmioApiDoc menggunakan annotation `@Swagger\Annotations\Response()` untuk membuat request format pada Sandbox
>
> *NB:* 
>
> * Kamu dapat membaca dan melihat penggunaan annotation tersebut pada folder `Controller`
>
> * Pastikan response yang Kamu berikan sesuai dengan dokumentasi
>

## Extra

>
> Bila Kamu bingung menggunakan tentang parameter yang tersedia pada masing-masing annotation, coba kunjungi halaman [Spesifkasi OpenApi](https://swagger.io/docs/specification/about/)
>
