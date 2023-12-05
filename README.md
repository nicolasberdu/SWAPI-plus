# SWAPI-plus

SWAPI-plus es una extension de la api basada en Star Wars SWAPI. En este documento se detallara los pasos para su instalacion, ejecucion y uso.



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

## Documentacion de Api

Al tratarse de una extension de SWAPI, SWAPI-plus conserva el comportamiento de las apis documentadas en https://swapi.dev/documentation. Por lo cual se dara una documentacion resumida, dandole mas importancia a la extension. 


### Limitación de velocidad
Para evitar la limitacion de velocidad nomal de SWAPI, esta aplicacion cuenta con una configuracion de cache que hara persistir los resultados de las ultimas consultas, por un tiempo determinado.

### Autenticación

Al igual que Swapi, esta es una API completamente abierta. Por lo cual no necesita agregar encabezados de authenticacion.


### Buscando
Todos los recursos admiten un searchparámetro que filtra el conjunto de recursos devueltos. Esto le permite realizar consultas como:

http://tu.dominio/api/people/?search=r2


### Resources

#### Root
Proporciona las rutas a los demas recursos

Solicitud de ejemplo:

```
http http://tu.dominio/api/
```

Respuesta de ejemplo:
```
    HTTP/1.0 200 OK
    Content-Type: application/json
    {
        "films": "http://tu.dominio/api/films/",
        "people": "http://tu.dominio/api/people/",
        "planets": "http://tu.dominio/api/planets/",
        "species": "http://tu.dominio/api/species/",
        "starships": "http://tu.dominio/api/starships/",
        "vehicles": "http://tu.dominio/api/vehicles/"
    }

```

#### Starships
Recurso de naves espaciales de la saga.

Endpoints:
- GET `/starships/` obtener todos las naves
- GET `/starships/:id/` obtener una nave específica
- PUT `/starships/:id/update` actualizar la cantidad de naves existentes para un modelo especifico
  - count : int min:0|max:4294967294 
  - Solicitud de ejemplo:
    ```
        PUT /starships/24
        {
            "count": 1000000000
        }
    ```
  - Respuesta de ejemplo:  
    ```
        HTTP/1.0 200 OK
        Content-Type: application/json
        {
            "detail": "Created"
        }
    ```
  - Ejemplo de error:
    ```
        HTTP/1.0 400 Bad request
        Content-Type: application/json
        {
            "error": "Bad request",
            "detail": "The increase by field must be an integer."
        }
    ```

- PUT `/starships/:id/increase` incrementar en un valor x la cantidad de naves existentes de un modelo
  - increaseBy : int min:0|max:4294967294 - {valor_acual}
  - Solicitud de ejemplo:
    ```
        PUT /starships/24/increase
        {
            "increaseBy": 1000000000
        }
    ```
  - Respuesta de ejemplo:  
    ```
        HTTP/1.0 200 OK
        Content-Type: application/json
        {
            "detail": "Created"
        }
    ```
  - Ejemplo de error:
    ```
        HTTP/1.0 400 Bad request
        Content-Type: application/json
        {
            "error": "Bad request",
            "detail": "The increase by field must not be greater than 45063383."
        }
    ```
- PUT `/starships/:id/decrease` decrementar en un valor x la cantidad de naves existentes de un modelo
  - increaseBy : int min:0|max:{valor_acual}
  - Solicitud de ejemplo:
    ```
        PUT /starships/24/decrease
        {
            "decreaseBy": 1000000000
        }
    ```
  - Respuesta de ejemplo:  
    ```
        HTTP/1.0 200 OK
        Content-Type: application/json
        {
            "detail": "Created"
        }
    ```
  - Ejemplo de error:
    ```
        HTTP/1.0 400 Bad request
        Content-Type: application/json
        {
            "error": "Bad request",
            "detail": "The decrease by field is required."
        }
    ```

Solicitud de ejemplo:
```
http http://tu.dominio/api/starships/9/
```
Respuesta de ejemplo:
```
HTTP/1.0 200 OK
Content-Type: application/json
{
    "MGLT": "10 MGLT",
    "cargo_capacity": "1000000000000",
    "consumables": "3 years",
    "cost_in_credits": "1000000000000",
    "created": "2014-12-10T16:36:50.509000Z",
    "crew": "342953",
    "edited": "2014-12-10T16:36:50.509000Z",
    "hyperdrive_rating": "4.0",
    "length": "120000",
    "manufacturer": "Imperial Department of Military Research, Sienar Fleet Systems",
    "max_atmosphering_speed": "n/a",
    "model": "DS-1 Orbital Battle Station",
    "name": "Death Star",
    "passengers": "843342",
    "films": [
        "http://tu.dominio/api/films/1/"
    ],
    "pilots": [],
    "starship_class": "Deep Space Mobile Battlestation",
    "url": "http://tu.dominio/api/starships/9/"
}
```
#### Vehicles
Recurso de vehiculos de la saga.

Endpoints:
- GET `/vehicles/` obtener todos los vehiculos
- GET `/vehicles/:id/` obtener un vehiculo específico
- PUT `/vehicles/:id/update` actualizar la cantidad de vehiculos existentes para un modelo especifico
  - count : int min:0|max:4294967294 
  - Solicitud de ejemplo:
    ```
        PUT /vehicles/24
        {
            "count": 1000000000
        }
    ```
  - Respuesta de ejemplo:  
    ```
        HTTP/1.0 200 OK
        Content-Type: application/json
        {
            "detail": "Created"
        }
    ```
  - Ejemplo de error:
    ```
        HTTP/1.0 400 Bad request
        Content-Type: application/json
        {
            "error": "Bad request",
            "detail": "The increase by field must be an integer."
        }
    ```

- PUT `/vehiculos/:id/increase` incrementar en un valor x la cantidad de vehiculos existentes de un modelo
  - increaseBy : int min:0|max:4294967294 - {valor_acual}
  - Solicitud de ejemplo:
    ```
        PUT /vehicles/24/increase
        {
            "increaseBy": 1000000000
        }
    ```
  - Respuesta de ejemplo:  
    ```
        HTTP/1.0 200 OK
        Content-Type: application/json
        {
            "detail": "Created"
        }
    ```
  - Ejemplo de error:
    ```
        HTTP/1.0 400 Bad request
        Content-Type: application/json
        {
            "error": "Bad request",
            "detail": "The increase by field must not be greater than 45063383."
        }
    ```
- PUT `/vehiculos/:id/decrease` decrementar en un valor x la cantidad de vehiculos existentes de un modelo
  - increaseBy : int min:0|max:{valor_acual}
  - Solicitud de ejemplo:
    ```
        PUT /vehicles/24/decrease
        {
            "decreaseBy": 1000000000
        }
    ```
  - Respuesta de ejemplo:  
    ```
        HTTP/1.0 200 OK
        Content-Type: application/json
        {
            "detail": "Created"
        }
    ```
  - Ejemplo de error:
    ```
        HTTP/1.0 400 Bad request
        Content-Type: application/json
        {
            "error": "Bad request",
            "detail": "The decrease by field is required."
        }
    ```

Solicitud de ejemplo:
```
http http://tu.dominio/api/vehiculos/9/
```
Respuesta de ejemplo:
```
HTTP/1.0 200 OK
Content-Type: application/json

{
    "cargo_capacity": "50000",
    "consumables": "2 months",
    "cost_in_credits": "150000",
    "created": "2014-12-10T15:36:25.724000Z",
    "crew": "46",
    "edited": "2014-12-10T15:36:25.724000Z",
    "length": "36.8",
    "manufacturer": "Corellia Mining Corporation",
    "max_atmosphering_speed": "30",
    "model": "Digger Crawler",
    "name": "Sand Crawler",
    "passengers": "30",
    "pilots": [],
    "films": [
        "http://tu.dominio/api/films/1/"
    ],
    "url": "http://tu.dominio/api/vehicles/4/",
    "vehicle_class": "wheeled"
}
```


#### People

Recurso de personajes de la saga.

Endpoints:
- `/people/` obtener todos los recursos humanos
- `/people/:id/` obtener un recurso de personas específicas

Solicitud de ejemplo:

```
http http://tu.dominio/api/people/1/
```

Respuesta de ejemplo:
```
HTTP/1.0 200 OK
Content-Type: application/json
{
    "birth_year": "19 BBY",
    "eye_color": "Blue",
    "films": [
        "http://tu.dominio/api/films/1/",
        ...
    ],
    "gender": "Male",
    "hair_color": "Blond",
    "height": "172",
    "homeworld": "http://tu.dominio/api/planets/1/",
    "mass": "77",
    "name": "Luke Skywalker",
    "skin_color": "Fair",
    "created": "2014-12-09T13:50:51.644000Z",
    "edited": "2014-12-10T13:52:43.172000Z",
    "species": [
        "http://tu.dominio/api/species/1/"
    ],
    "starships": [
        "http://tu.dominio/api/starships/12/",
        ...
    ],
    "url": "http://tu.dominio/api/people/1/",
    "vehicles": [
        "http://tu.dominio/api/vehicles/14/"
        ...
    ]
}
```

#### Films
Recurso de los films de la saga.

Endpoints:
- `/films/` obtener todos los films
- `/films/:id/` obtener un film especifico

Solicitud de ejemplo:
```
http http://tu.dominio/api/films/1/
```

Respuesta de ejemplo:
```
HTTP/1.0 200 OK
Content-Type: application/json
{
    "characters": [
        "http://tu.dominio/api/people/1/",
        ...
    ],
    "created": "2014-12-10T14:23:31.880000Z",
    "director": "George Lucas",
    "edited": "2014-12-12T11:24:39.858000Z",
    "episode_id": 4,
    "opening_crawl": "It is a period of civil war.\n\nRebel spaceships, striking\n\nfrom a hidden base, have won\n\ntheir first victory against\n\nthe evil Galactic Empire.\n\n\n\nDuring the battle, Rebel\n\nspies managed to steal secret\r\nplans to the Empire's\n\nultimate weapon, the DEATH\n\nSTAR, an armored space\n\nstation with enough power\n\nto destroy an entire planet.\n\n\n\nPursued by the Empire's\n\nsinister agents, Princess\n\nLeia races home aboard her\n\nstarship, custodian of the\n\nstolen plans that can save her\n\npeople and restore\n\nfreedom to the galaxy....",
    "planets": [
        "http://tu.dominio/api/planets/1/",
        ...
    ],
    "producer": "Gary Kurtz, Rick McCallum",
    "release_date": "1977-05-25",
    "species": [
        "http://tu.dominio/api/species/1/",
        ...
    ],
    "starships": [
        "http://tu.dominio/api/starships/2/",
        ...
    ],
    "title": "A New Hope",
    "url": "http://tu.dominio/api/films/1/",
    "vehicles": [
        "http://tu.dominio/api/vehicles/4/",
        ...
    ]
}
```


#### Species
Todas las especies de la saga.

Endpoints:
- `/specie/` obtener todos los recursos de especies
- `/specie/:id/` obtener un recurso de una especie específica

Solicitud de ejemplo:
```
http http://tu.dominio/api/species/3/
```
Respuesta de ejemplo:
```
HTTP/1.0 200 OK
Content-Type: application/json

{
    "average_height": "2.1",
    "average_lifespan": "400",
    "classification": "Mammal",
    "created": "2014-12-10T16:44:31.486000Z",
    "designation": "Sentient",
    "edited": "2014-12-10T16:44:31.486000Z",
    "eye_colors": "blue, green, yellow, brown, golden, red",
    "hair_colors": "black, brown",
    "homeworld": "http://tu.dominio/api/planets/14/",
    "language": "Shyriiwook",
    "name": "Wookie",
    "people": [
        "http://tu.dominio/api/people/13/"
    ],
    "films": [
        "http://tu.dominio/api/films/1/",
        "http://tu.dominio/api/films/2/"
    ],
    "skin_colors": "gray",
    "url": "http://tu.dominio/api/species/3/"
}
```

#### Planets
Planetas de la saga.

Endpoints:
- /planets/ obtener todos los recursos del planetas
- /planets/:id/ obtener un recurso planetario específico

Solicitud de ejemplo:

```
http http://tu.dominio/api/planets/1/
```
Respuesta de ejemplo:

```
HTTP/1.0 200 OK
Content-Type: application/json
{
    "climate": "Arid",
    "created": "2014-12-09T13:50:49.641000Z",
    "diameter": "10465",
    "edited": "2014-12-15T13:48:16.167217Z",
    "films": [
        "http://tu.dominio/api/films/1/",
        ...
    ],
    "gravity": "1",
    "name": "Tatooine",
    "orbital_period": "304",
    "population": "120000",
    "residents": [
        "http://tu.dominio/api/people/1/",
        ...
    ],
    "rotation_period": "23",
    "surface_water": "1",
    "terrain": "Dessert",
    "url": "http://tu.dominio/api/planets/1/"
}
```