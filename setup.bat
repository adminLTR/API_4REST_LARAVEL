@echo off
echo === Configurando Proyecto Laravel ===

REM Verificar si Laravel ya esta instalado
if not exist "composer.json" (
    echo Creando proyecto Laravel...
    docker-compose run --rm app composer create-project laravel/laravel .
) else (
    echo Laravel ya esta instalado. Instalando dependencias...
    docker-compose run --rm app composer install
)

REM Copiar archivo .env
if not exist ".env" (
    echo Copiando archivo .env...
    copy .env.example .env
)

REM Generar APP_KEY
echo Generando APP_KEY...
docker-compose run --rm app php artisan key:generate

REM Levantar contenedores
echo Levantando contenedores...
docker-compose up -d

REM Esperar a que la base de datos este lista
echo Esperando a que la base de datos este lista...
timeout /t 15

REM Ejecutar migraciones
echo Ejecutando migraciones...
docker-compose exec app php artisan migrate

REM Dar permisos
echo Configurando permisos...
docker-compose exec app chmod -R 775 storage bootstrap/cache

REM Generar documentación de Swagger
echo Generando documentacion de Swagger...
docker-compose exec app php artisan l5-swagger:generate

echo.
echo === Instalacion completada ===
echo.
echo Servicios disponibles:
echo - API REST: http://localhost:8000
echo - Documentacion Swagger: http://localhost:8000/
echo - PHPMyAdmin: http://localhost:8080
echo.
echo Para regenerar la documentacion de Swagger ejecuta:
echo docker-compose exec app php artisan l5-swagger:generate
echo.

pause
