# CrashTrakr

Este proyecto utiliza Docker para facilitar el entorno de desarrollo. A continuación encontrarás las instrucciones necesarias para levantar el proyecto en tu máquina local.

## Requisitos Previos

Asegúrate de tener instalados los siguientes programas en tu sistema:
- [Docker](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Instrucciones para levantar el entorno

1. **Clonar el repositorio** (si aún no lo has hecho):
   ```bash
   git clone <url-del-repositorio> crashtrakr
   cd crashtrakr
   ```

2. **Configurar las variables de entorno**:
   Copia el archivo de ejemplo para crear tu propio archivo `.env`.
   ```bash
   cp .env.example .env
   ```
   *Nota: Asegúrate de que las credenciales de la base de datos en tu `.env` coincidan con las variables por defecto en el `docker-compose.yml` si decides no configurarlas (Postgres DB: `crashtrakr_db`, User: `darwin_quiroz`, Password: `admin_DB123`).*

3. **Construir y levantar los contenedores**:
   Ejecuta el siguiente comando para construir las imágenes y levantar los servicios (App, Nginx y PostgreSQL) en segundo plano:
   ```bash
   docker compose up -d --build
   ```

4. **Instalar dependencias de PHP (Composer)**:
   Una vez que los contenedores estén corriendo, instala las dependencias de Laravel:
   ```bash
   docker compose exec app composer install
   ```

5. **Generar la clave de la aplicación**:
   ```bash
   docker compose exec app php artisan key:generate
   ```

6. **Ejecutar las migraciones de la base de datos**:
   ```bash
   docker compose exec app php artisan migrate
   ```

7. **Instalar dependencias de Node y compilar assets (Vite)**:
   ```bash
   docker compose exec app npm install
   docker compose exec app npm run dev
   ```

## Acceso a la aplicación

Una vez completados los pasos anteriores, puedes acceder a la aplicación y servicios en las siguientes rutas:

- **Aplicación Web (Nginx)**: [http://localhost:8000](http://localhost:8000)
- **Vite (Hot Module Replacement)**: [http://localhost:5173](http://localhost:5173)
- **Base de Datos (PostgreSQL)**: `localhost:5432`

## Comandos Útiles

- **Detener los contenedores**:
  ```bash
  docker compose stop
  ```
- **Bajar los contenedores y eliminar redes**:
  ```bash
  docker compose down
  ```
- **Ver los logs de los contenedores**:
  ```bash
  docker compose logs -f
  ```
- **Acceder a la consola del contenedor de la aplicación**:
  ```bash
  docker compose exec app bash
  ```
