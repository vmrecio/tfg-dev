# Trabajo de Fin de Grado - Víctor Recio

![LOGO UNIR](https://upload.wikimedia.org/wikipedia/commons/4/42/UNIR_Horizontal_Logo.png)

Bienvenido al repositorio de mi proyecto de fin de grado en Ingeniería Informática

## Aplicación integral de gestión de bodas (API + Panel)

Este Trabajo de Fin de Grado aborda el desafío de desarrollar una aplicación de gestión de bodas construida con Laravel 12, que expone una API REST autenticada por tokens (Sanctum) y un panel de administración con Filament. Incluye documentación de la API generada con Scribe.

Herramientas principales:
- PHP 8.2+, Laravel 12, Sanctum (tokens)
- Filament 4 (panel en `/admin`)
- Scribe 5 (docs en `/docs`)
- Vite/Tailwind para assets (opcional en desarrollo)

## Requisitos
- PHP 8.2+ con extensiones estándar (pdo, pdo_sqlite, pdo_mysql, etc.)
- Composer 2
- Node 18+ (solo si compilas assets con Vite)
- Opcional: Docker + Docker Compose (Laravel Sail)

## Puesta en marcha rápida (local, SQLite)
La configuración por defecto usa SQLite, por lo que no necesitas un servidor de base de datos.

1) Instalar dependencias
```
composer install
```

2) Configurar entorno
```
cp .env.example .env
php artisan key:generate
```

3) Crear BD SQLite y ejecutar migraciones/seeders
```
mkdir -p database
touch database/database.sqlite
php artisan migrate --seed
```

4) (Opcional) Enlaces de almacenamiento y cola
```
php artisan storage:link
php artisan queue:work  # en otra terminal, si usas colas (driver database)
```

5) Arrancar servidor
```
php artisan serve
```

Accesos rápidos:
- Panel de administración: http://localhost:8000/admin
- Documentación de la API: http://localhost:8000/docs (si no ves endpoints actualizados, ejecuta `php artisan scribe:generate`)

Usuarios de prueba (seed):
- victor@example.com / password
- felix@example.com / password
- cristina@example.com / password

## Despliegue con Docker (Sail + MySQL)
El repo incluye `docker-compose.yml` basado en Laravel Sail.

1) Instalar dependencias (necesario para que exista `vendor/` y la imagen de Sail)
```
composer install
```

2) Configurar entorno para MySQL
```
cp .env.example .env
php artisan key:generate

# Edita .env con estas variables (
# ejemplo recomendado para Sail):
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

3) Levantar contenedores
```
./vendor/bin/sail up -d
```

4) Migraciones y seeders dentro del contenedor
```
./vendor/bin/sail artisan migrate --seed
```

5) (Opcional) Worker de colas y generación de docs
```
./vendor/bin/sail artisan queue:work
./vendor/bin/sail artisan scribe:generate
```

Accesos rápidos (Sail):
- App/API: http://localhost
- Docs: http://localhost/docs
- Panel: http://localhost/admin

## Autenticación de la API
La API está bajo el prefijo `/api/v1` y protegida por Sanctum. Flujo típico:
1) Login: `POST /api/v1/auth/login` → devuelve `token` Bearer.
2) Enviar `Authorization: Bearer <token>` en el resto de peticiones.
3) Logout: `POST /api/v1/auth/logout`.

Ejemplo con cURL:
```
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H 'Content-Type: application/json' \
  -d '{"email":"user@example.com","password":"password"}'

# Luego usar el token devuelto
curl http://localhost:8000/api/v1/weddings \
  -H 'Accept: application/json' \
  -H 'Authorization: Bearer <TOKEN>'
```

## Endpoints principales
- POST `/api/v1/auth/login` (público)
- POST `/api/v1/auth/logout`
- CRUD `/api/v1/weddings` (listado paginado con filtros de fecha y ubicación)

## Desarrollo y utilidades
- Ejecutar todo en desarrollo (servidor, colas, logs y Vite) con un solo comando:
  ```
  composer dev
  ```
- Tests:
  ```
  composer test
  ```

## Variables de entorno destacadas
- `APP_URL`: base URL (usado también por Scribe).
- `DB_CONNECTION`: `sqlite` (por defecto) o `mysql` (Sail).
- `QUEUE_CONNECTION`: `database` (requiere worker activo para procesar jobs).
- `SCRIBE_AUTH_KEY`: token de ejemplo para probar endpoints en la UI de Scribe.

## Notas
- Si usas MySQL con Docker, asegúrate de que las credenciales de `.env` coinciden con las de `docker-compose.yml` (usuario/contraseña usados para inicializar MySQL).
- Da permisos de escritura a `storage/` y `bootstrap/cache/` en entornos Linux.
