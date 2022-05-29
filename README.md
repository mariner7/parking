## Parking test

## Introducción

<a href="https://documenter.getpostman.com/view/15185242/Uz5CLHvC">Documentación en Postman</a>

Requiere Docker y Composer

Para levantar el proyecto, ejecutar en la terminal:
        <code>. vendor/bin/sail up -d</code>

o, con composer
        <code>docker-compose up -d</code>

## Crear un usuario

en Postman, crear un usuario, para obtener el <em>Auth Token</em>

<code>127.0.0.1/api/register</code>
datos necesarios:
- nombre
- email
- password
- password_confirmation

Al recibir la respuesta, copiar el token a todas la rutas que requieren autorización como
<em>Bearer Token</em> en la sección de Autorización de Postman

## Rutas que requieren autorización

    POST <code>/vehicle/<tipo de vechiculo></code>
    PUT  <code>/vehicle/<id></code>
    DELETE <code>/vehicle/<id></code>
    GET <code>/update/<id>/<tiempo a acumular></code> (En Postman debe ser PUT)
    PUT <code>/vehicle/reset/all</code>

    POST <code>/access/check_in</code>
    PUT <code>/access/check_out/<id></code>
    GET <code>/report</code>

    POST <code>/types</code>
    PUT <code>/types/<nombre del tipo></code>
    DELETE <code>/types/<nombre del tipo></code>


Administrar el acceso de vehículos a un estacionamiento de pago. El estacionamiento no se encuentra automatizado, por lo que existe un empleado encargado de registrar las entradas y salidas de vehículos.

Los vehículos oficiales no pagan, pero se registran sus estancias para llevar el control. (Una estancia consiste en una hora de entrada y una de salida)
- Los residentes pagan a final de mes a razón de MXN$0.05 el minuto. La aplicación irá acumulando el tiempo (en minutos) que han permanecido estacionados.
- Los no residentes pagan a la salida del estacionamiento a razón de MXN$0.5 por minuto. Se prevé que en el futuro puedan incluirse nuevos tipos de vehículos, por lo que la aplicación desarrollada deberá ser fácilmente extensible en ese aspecto.

## Resumen

Caso de uso "Registra entrada" 

1. El empleado elige la opción "registrar entrada" e introduce el número de placa del coche que entra. 

2. La aplicación apunta la hora de entrada del vehículo. 

Caso de uso "Registra salida" 

1. El empleado elige la opción "registrar salida" e introduce el número de placa del coche que sale. 

2. La aplicación realiza las acciones correspondientes al tipo de vehículo:

- Oficial: asocia la estancia (hora de entrada y hora de salida) con el vehículo 
- Residente: suma la duración de la estancia al tiempo total acumulado 
- No residente: obtiene el importe a pagar

 
## Caso de uso "Da de alta vehículo oficial" 

1. El empleado elige la opción "dar de alta vehículo oficial" e introduce su número de placa. 

2. La aplicación añade el vehículo a la lista de vehículos oficiales 

## Caso de uso "Da de alta vehículo de residente" 

1. El empleado elige la opción "dar de alta vehículo de residente" e introduce su número de placa. 

2. La aplicación añade el vehículo a la lista de vehículos de residentes. 

## Caso de uso "Comienza mes" 

1. El empleado elige la opción "comienza mes". 

2. La aplicación elimina las estancias registradas en los coches oficiales y pone a cero el tiempo estacionado por los vehículos de residentes. 

## Caso de uso "Pagos de residentes" 

1. El empleado elige la opción "genera informe de pagos de residentes" e introduce el nombre del archivo en el que quiere generar el informe. 

2. La aplicación genera un archivo que detalla el tiempo estacionado y el dinero a pagar por cada uno de los vehículos de residentes. El formato del archivo será el mostrado a continuación: 

La aplicación contará con un programa principal basado en un menú que permitirá al empleado interactuar con la aplicación (dicho programa principal no forma parte de este ejercicio 

## Persistencia de datos 

La información de cada una de las estancias de los vehículos será almacenada en una base de datos. Se recomienda usar MySQL. 

## Authentication

Bearer Token

## RUTAS EN POSTMAN
POST

127.0.0.1/api/vehicles/create

Inserta un nuevo vehículo


Example Request
127.0.0.1/api/vehicles/create

curl --location --request POST '127.0.0.1/api/vehicles/create' \
--data-raw ''

GET

Get all vehicles
127.0.0.1/api/vehicles
HEADERS
Accept
application/json


422 - email existente
Example Request

curl --location --request POST 'localhost/api/register' \
--header 'Accept: application/json' \
--data-urlencode 'name=admin' \
--data-urlencode 'email=me@yo.com' \
--data-urlencode 'password=$ym940ny' \
--data-urlencode 'password_confirmation=$ym940ny'

Example Response
422 Unprocessable Content
Body
Header(10)

{
  "message": "The email has already been taken.",
  "errors": {
    "email": [
      "The email has already been taken."
    ]
  }
}

POST

Add a vehicle
127.0.0.1/api/vehicles/create
HEADERS
Accept
application/json
BODYraw

{
    "license_plate":"123ABC",
    "make":"Chrysler",
    "type":"2"
}



201 - Add a resident vehicle
Example Request

curl --location --request POST 'localhost/api/vehicles/resident' \
--header 'Accept: application/json' \
--data-raw '{
    "license_plate":"A36RFG",
    "make":"Nissan"
}'

Example Response
201 Created
Body
Header(10)

{
  "license_plate": "A36RFG",
  "make": "Nissan",
  "type": "2",
  "updated_at": "2022-05-29T04:19:52.000000Z",
  "created_at": "2022-05-29T04:19:52.000000Z",
  "id": 9
}

POST

Check-In
127.0.0.1/api/check_in
AUTHORIZATIONBearer Token
Token
<token>
Bodyurlencoded
license_plate
123ABC


201 - Creado
Example Request

curl --location --request POST '127.0.0.1/api/access/check_out' \
--header 'Accept: application/json' \
--data-urlencode 'license_plate=123ABC98'

Example Response
201 Created
Body
Header(10)

{
  "license_plate": "123ABC",
  "check_in": "2022-05-28 12:05:51",
  "updated_at": "2022-05-28T17:14:51.000000Z",
  "created_at": "2022-05-28T17:14:51.000000Z",
  "id": 4
}

PUT

Check-Out
127.0.0.1/api/access/check_out/6
AUTHORIZATIONBearer Token
Token
<token>
HEADERS
Accept
application/json


201 - Actualizado correctamente
Example Request

curl --location --request PUT '127.0.0.1/api/access/check_out/5' \
--header 'Accept: application/json'

Example Response
200 OK
Body
Header(10)

{
  "id": 5,
  "license_plate": "123ABC",
  "check_in": "2022-05-28 12:05:32",
  "check_out": "2022-05-28 12:31:47",
  "cumulative_month": null,
  "created_at": "2022-05-28T17:30:32.000000Z",
  "updated_at": "2022-05-28T17:31:47.000000Z"
}

GET

Reset time counter
127.0.0.1/api/vehicles/reset
AUTHORIZATIONBearer Token
Token
<token>
HEADERS
Accept
application/json


Example Request
200 - Reset time counter

curl --location --request PUT '127.0.0.1/api/vehicles/reset/all'

Example Response
200 OK
Body
Header(10)
View More

[
  {
    "id": 1,
    "license_plate": "796UNK",
    "make": "Ford",
    "type": 1,
    "cumulative_time": 0,
    "updated_at": null,
    "created_at": null
  },
  {

GET

Generate Report
No request URL found. It will show up here once added.


Example Request
200 - Generate Report

curl --location --request GET '127.0.0.1/api/report'

Example Response
200 OK
Body
Header(10)

381QMq                   0                        0
567HGE                   0                        0
123ABC                   623                      31.15
566XPH                   0                        0
G87BNM                   0                        0


GET

Search vehicle by ID
localhost/api/vehicles/3


Example Request
200 - Search vehicle by ID

curl --location --request GET 'localhost/api/vehicles/3'

Example Response
200 OK
Body
Header(10)

{
  "id": 3,
  "license_plate": "567HGE",
  "make": "Nissan",
  "type": 2,
  "updated_at": "2022-05-27T23:10:38.000000Z",
  "created_at": null
}