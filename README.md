# Challenge Laravel

Este proyecto es una aplicación web construida con Laravel 10, utilizando Blade como motor de plantillas y Alpine.js para la interactividad en el frontend.

## Requisitos

- PHP >= 8.1
- Composer
- Node.js y npm
- MySQL o cualquier otro sistema de gestión de bases de datos compatible

## Instalación

1. Clona el repositorio:

    ```bash
    git clone https://github.com/dennisbr91/laravel_challenge.git
    ```

2. Instala las dependencias de PHP:

    ```bash
    composer install
    ```

3. Instala las dependencias de JavaScript:

    ```bash
    npm install
    ```

4. Copia el archivo de configuración de entorno y configura tus variables de entorno:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. Configura la base de datos en el archivo `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db
    DB_USERNAME=user
    DB_PASSWORD=password
    ```

6. Ejecuta las migraciones para crear las tablas en la base de datos:

    ```bash
    php artisan migrate
    ```

## Uso

1. Inicia el servidor de desarrollo de Laravel:

    ```bash
    php artisan serve
    ```

2. Compila los assets de frontend:

    ```bash
    npm run dev
    ```

3. Abre tu navegador y navega a `http://localhost:8000` para ver la aplicación en funcionamiento.

## Estructura del Proyecto

- `resources/views`: Contiene las vistas Blade.
- `resources/js`: Contiene los archivos JavaScript, incluyendo los componentes de Alpine.js.
- `routes/web.php`: Define las rutas de la aplicación.
- `routes/api.php`: Define las rutas para la API de la aplicación.
