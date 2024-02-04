
# ApiRest Ful Futbol 

Api que hace CRUD a una tabla de equipos de futbol con verificacion por Token JWT




## Poner en producción

Para poner en produccion este proyecto, es necesario:

Instalar las dependencias de composer
```bash
  composer update
```
Ejecutar el SQL de la base de datos

Cambiar el BASE_URL si ubica el proyecto fuera de la raiz de htdocs (en xampp)

## API Reference

#### Mostrar todos los equipos

```http
  GET /equipo
```

#### Devolver los datos de un equipo en específico

```http
  GET /equipo/{id}
```
#### Crear un nuevo equipo

```http
  POST /equipo
```

#### Borrar un equipo que existe
```http
  DELETE /equipo/{id}
```
#### Modificar los datos de un equipo que ya existe
```http
  PUT /equipo/{id}
```


