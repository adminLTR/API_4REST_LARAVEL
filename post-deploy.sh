#!/bin/bash

echo "🚀 Ejecutando configuración post-deploy..."

# Esperar a que la base de datos esté lista
echo "⏳ Esperando conexión a la base de datos..."
sleep 10

# Limpiar cache
echo "🧹 Limpiando cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Generar documentación de Swagger
echo "📚 Generando documentación de Swagger..."
php artisan l5-swagger:generate

# Establecer permisos
echo "🔐 Configurando permisos..."
chmod -R 775 storage bootstrap/cache

echo "✅ Configuración completada!"
