# Requerimientos minimos

    Apache 2.4
    PHP 7.4
    MySQL 5.7
    Lumen 8.3

# Instalación y configuracion

1.- Por practicidad se sugiere instalar Laragon 5.0

    Sitio de descarga oficial: https://laragon.org/download/index.html
    Tutorial de instalacion: https://www.youtube.com/watch?v=QRwuPHeagaQ

2.- Clonar el repositorio netwey-lumen a la carpeta www dentro del directorio de instalacion de Laragon

3.- Configurar Laragon
    
    Seleccionar Apache dentro de Laragon
    Seleccionar MySQL como motor de base de datos
    
    Tutorial de configuracion de Laragon: https://www.youtube.com/watch?v=KyKic5jTZkQ

4.- En el directorio del proyecto netwey-lumen configurar la conexion a la base de datos dentro del archivo .env

![ScreenHunter 2326](https://user-images.githubusercontent.com/11873645/148319357-52d8bedf-eb36-4895-a50b-61bc98ae572b.png)

5.- Iniciar todos los servicios en Laragon

6.- Revisar en el navegador que Lumen esta corriendo

![ScreenHunter 2323](https://user-images.githubusercontent.com/11873645/148319426-5f768f5f-66c6-46f3-a388-677e04a7f1bc.png)

7.- Abrir la terminal de Laragon y posicionarse en el directorio del proyecto

8.- Ejecutar las migraciones con el comando Artisan para crear la tabla de log y de users que estaran habilitados para usar la API por medio de la generacion de un token.

![ScreenHunter 2327](https://user-images.githubusercontent.com/11873645/148319577-ed8660be-c88a-45c1-bf1d-7f4b01a244af.png)

9.- Abrir la base de datos desde Laragon y copiar el siguiente script en el apartado Query

    CREATE TABLE IF NOT EXISTS `netwey-lumen`.`regions` (
    `id_reg` INT NOT NULL AUTO_INCREMENT COMMENT '', `description` VARCHAR(90)
    NOT NULL COMMENT '',
    `status` ENUM('A', 'I', 'trash') NOT NULL DEFAULT 'A' COMMENT '', PRIMARY KEY
    (`id_reg`) COMMENT '')
    ENGINE = MyISAM;

    CREATE TABLE IF NOT EXISTS `netwey-lumen`.`communes` (
    `id_com` INT NOT NULL AUTO_INCREMENT COMMENT '', `id_reg` INT NOT NULL
    COMMENT '',
    `description` VARCHAR(90) NOT NULL COMMENT '',
    `status` ENUM('A', 'I', 'trash') NOT NULL DEFAULT 'A' COMMENT '', PRIMARY KEY
    (`id_com`, `id_reg`) COMMENT '',
    INDEX `fk_communes_region_idx` (`id_reg` ASC) COMMENT '')
    ENGINE = MyISAM; 

    CREATE TABLE IF NOT EXISTS `netwey-lumen`.`customers` (
    `dni` VARCHAR(45) NOT NULL COMMENT 'Documento de Identidad',
    `id_reg` INT NOT NULL COMMENT '',
    `id_com` INT NOT NULL COMMENT '',
    `email` VARCHAR(120) NOT NULL COMMENT 'Correo Electrónico',
    `name` VARCHAR(45) NOT NULL COMMENT 'Nombre',
    `last_name` VARCHAR(45) NOT NULL COMMENT 'Apellido',
    `address` VARCHAR(255) NULL COMMENT 'Dirección',
    `date_reg` DATETIME NOT NULL COMMENT 'Fecha y hora del registro',
    `status` ENUM('A', 'I', 'trash') NOT NULL DEFAULT 'A' COMMENT 'estado del registro:\nA
    : Activo\nI : Desactivo\ntrash : Registro eliminado',
    PRIMARY KEY (`dni`, `id_reg`, `id_com`) COMMENT '',
    INDEX `fk_customers_communes1_idx` (`id_com` ASC, `id_reg` ASC) COMMENT '',
    UNIQUE INDEX `email_UNIQUE` (`email` ASC) COMMENT '')
    ENGINE = MyISAM; 

10.- Ejecutar el query

11.- Agregar registros a las tablas communes y regions para las pruebas

12.- Instalar y ejecutar Postman para probar el consumo de los recursos de la API

    Sitio de descarga oficial: https://www.postman.com/downloads/

# Consumo de la API

1.- Ejecutar Postman

2.- Crear usuarios que consumiran la API

![ScreenHunter 2316](https://user-images.githubusercontent.com/11873645/148294947-f8bacfd2-f6e4-47e9-ad99-2402ca3f479f.png)

3.- Loguear el usuario que consumira los servicios

![ScreenHunter 2317](https://user-images.githubusercontent.com/11873645/148294952-7b1e7357-491e-4307-8fc5-bc60d8268b9c.png)

4.- Desconectar el usuario una vez que se termine de usar la API

![ScreenHunter 2318](https://user-images.githubusercontent.com/11873645/148294954-436aa3b1-8ab1-4a34-a4b5-35834ca75e92.png)

5.- Crear un Customer con la api_token que devolvio la aplicacion al loguearse contemplando las reglas del documento de requerimientos

![ScreenHunter 2319](https://user-images.githubusercontent.com/11873645/148294955-a8ea8a07-7ce6-43a8-ac32-1bae8671ea56.png)

6.- Mostrar el Customer

![ScreenHunter 2321](https://user-images.githubusercontent.com/11873645/148294960-ceb0e32b-4d7f-437f-9952-954436e235e9.png)


7.- Borrar el Customer

![ScreenHunter 2320](https://user-images.githubusercontent.com/11873645/148294958-6b65e3b6-1ef3-4a3c-948f-3dbc968da36e.png)


