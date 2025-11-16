# Guía Rápida de Uso

## Iniciar el Proyecto

### Windows
```bash
setup.bat
```

### Manual
```bash
docker-compose up -d
docker-compose exec app php artisan migrate
```

## Probar la API

### 1. Crear un Pedido
```bash
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"customer_name\":\"Juan Pérez\",\"total_amount\":150.50}"
```

### 2. Listar Pedidos
```bash
curl http://localhost:8000/api/v1/orders \
  -H "Accept: application/json"
```

### 3. Procesar Pago (reemplazar {id} con el ID del pedido)
```bash
curl -X POST http://localhost:8000/api/v1/orders/{id}/payments \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### 4. Ver Pedido Específico
```bash
curl http://localhost:8000/api/v1/orders/{id} \
  -H "Accept: application/json"
```

## Ejecutar Tests
```bash
docker-compose exec app php artisan test
```

## Acceder a PHPMyAdmin
Abrir en navegador: http://localhost:8080
- Usuario: `laravel`
- Contraseña: `laravel_password`

## Detener el Proyecto
```bash
docker-compose down
```

## Ver Logs
```bash
# Logs de la aplicación
docker-compose logs -f app

# Logs de la base de datos
docker-compose logs -f db
```

## Problemas Comunes

### Puerto 8000 ya en uso
```bash
# Cambiar puerto en docker-compose.yml
ports:
  - "8001:80"  # Usar 8001 en lugar de 8000
```

### Permisos en Linux/Mac
```bash
sudo chown -R $USER:$USER .
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Base de datos no conecta
```bash
# Reiniciar contenedores
docker-compose restart

# Verificar que db esté corriendo
docker-compose ps
```
