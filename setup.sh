#!/bin/bash

# Colores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}=== Configurando Proyecto Laravel ===${NC}"

# Verificar si Laravel ya está instalado
if [ ! -f "composer.json" ]; then
    echo -e "${GREEN}Creando proyecto Laravel...${NC}"
    docker-compose run --rm app composer create-project laravel/laravel .
else
    echo -e "${GREEN}Laravel ya está instalado. Instalando dependencias...${NC}"
    docker-compose run --rm app composer install
fi

# Copiar archivo .env
if [ ! -f ".env" ]; then
    echo -e "${GREEN}Copiando archivo .env...${NC}"
    cp .env.example .env
fi

# Generar APP_KEY
echo -e "${GREEN}Generando APP_KEY...${NC}"
docker-compose run --rm app php artisan key:generate

# Levantar contenedores
echo -e "${GREEN}Levantando contenedores...${NC}"
docker-compose up -d

# Esperar a que la base de datos esté lista
echo -e "${GREEN}Esperando a que la base de datos esté lista...${NC}"
sleep 10

# Ejecutar migraciones
echo -e "${GREEN}Ejecutando migraciones...${NC}"
docker-compose exec app php artisan migrate

# Dar permisos
echo -e "${GREEN}Configurando permisos...${NC}"
docker-compose exec app chmod -R 775 storage bootstrap/cache

# Generar documentación de Swagger
echo -e "${GREEN}Generando documentación de Swagger...${NC}"
docker-compose exec app php artisan l5-swagger:generate

echo ""
echo -e "${BLUE}=== ¡Instalación completada! ===${NC}"
echo ""
echo -e "${GREEN}Servicios disponibles:${NC}"
echo -e "${GREEN}- API REST: http://localhost:8000${NC}"
echo -e "${GREEN}- Documentación Swagger: http://localhost:8000/${NC}"
echo -e "${GREEN}- PHPMyAdmin: http://localhost:8080${NC}"
echo ""
echo -e "Para regenerar la documentación de Swagger ejecuta:"
echo -e "docker-compose exec app php artisan l5-swagger:generate"
echo ""
