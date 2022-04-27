## Conexión a Navixy

Obtiene las ultimas posiciones de los vehiculos enviados a la api.
Para que pueda funcionar debe de existir un usuario de la aplicación para poder autenticarse a la api de navixy.

### Pasos a seguir

- composer install
- npm install
- npm run dev
- php artisan migrate
- Crear un usuario con contraseña nativo de la aplicación ingresando a la ruta de la api o usando el endpoint de la api **api/register,**
- Configurar el .env **cp .env.example .env**

### Configurar el env para la conexión a la api navixy
- API_NAVIXY=https://api.navixy.com/v2/
- API_NAVIXY_LOGIN=waltmart@eguard.mx
- API_NAVIXY_PASSWORD=123456

## Funcionalidad

La api de autenticación obtiene un token lo cual se debe configurar en las cabeceras de la petición. Una vez autenticado
se hace la peticion al endpoint de tracking **api/tracking**

### Tracking
- Enviar Cabeceras de token
- Enviar parametro de nombre trackers = [1,2,3,4,5,6.....]

Al hacer la peticion al endpoint de tracking, primero valida las credenciales del servicio para hacer la autenticación a navixy
una vez autenticado consulta la lista de vehiculos de navixy lo cual inserta en la tabla de vehicles, al mismo tiempo consulta el endpoint de navixy
enviandole los tracker_id al endpoint y obtiene la ultima posición y los datos se guarda en la tabla de vehicle_trackers, posterior se obtiene 
los resultados de la siguiente manera:


        {
            "success": true,
            "data": {
                "0": {
                    "id": 90,
                    "vehicle_id": 107325,
                    "source_id": 442052,
                    "lat": "19.612133",
                    "lng": "-99.059903",
                    "last_updated": "2022-04-27 10:56:41",
                    "movement_status": "moving",
                    "created_at": "2022-04-27T15:56:45.000000Z",
                    "updated_at": "2022-04-27T15:56:45.000000Z",
                    "vehicle": {
                        "id": 107325,
                        "icon_color": "1E96DC",
                        "tracker_id": 1016172,
                        "tracker_label": "66ap4z ram 2011 gasolina",
                        "label": "RAM",
                        "max_speed": null,
                        "model": "",
                        "type": "truck",
                        "subtype": null,
                        "color": null,
                        "additional_info": null,
                        "reg_number": "66AP4Z",
                        "vin": "",
                        "created_at": null,
                        "updated_at": null
                    }
                },
            },
            "message": "Tracking Vehicles retrieved successfully"
        }




## License

The Laravel framework is open-sourced software licensed under the **MIT license**.
