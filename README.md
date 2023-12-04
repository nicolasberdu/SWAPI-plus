# SWAPI-plus

SWAPI-plus es una extencion de la api basada en Star Wars SWAPI. En este documento se detallara los pasos para su instalacion, ejecucion y uso.



## Descarga 

Puedes [descargar](https://github.com/nicolasberdu/SWAPI-plus/archive/refs/heads/main.zip) el repositorio o clonarlo con 
```
git clone https://github.com/nicolasberdu/SWAPI-plus.git
```


## Instalacion 

> Requisitos: este proyecto fue desarrollado en Laravel v10, necesitara la version de php 8.0 o superior.


### Env
Una vez descargado, configure su archivo `.env` con los datos de conexion de base de datos. Y ademas agregue las variables de SWAPI.

```
SWAPI_BASE_URL=https://swapi.dev/api
SWAPI_CACHE_TIME=1800
```
Donde `SWAPI_CACHE_TIME` es el tiempo de vida en segundos del cache del servicio.


### Dependencias
Una vez configurado instale las dependencias por medio de composer.

```
composer install
```


### Migraciones y seeders

Ahora cree la base de datos y tablas ejecutando las migraciones y luego los seeders.
```
php artisan migrate
php artisan db:seed
```
> Nota: debera tener ya configurado su .env para que la aplicacion obtenga los datos de SWAPI.


### Ejecucion 

Una ves los seeders terminen su trabajo, ejecute el proyecto.

```
php artisan serve
```

## 