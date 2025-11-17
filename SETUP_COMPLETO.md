# Setup Completo - Configuración de Base de Datos

## 📋 Resumen

Este documento detalla los pasos realizados para configurar completamente el proyecto después de clonarlo en una nueva máquina.

## 🔧 Pasos Realizados

### 1. Configuración del Entorno

```bash
# Copiar archivo de configuración
Copy-Item .env.example .env
```

### 2. Instalación de Dependencias

```bash
# Instalar dependencias de Composer
docker-compose exec app composer install
```

### 3. Generación de Clave de Aplicación

```bash
# Generar APP_KEY
docker-compose exec app php artisan key:generate
```

### 4. Creación de Base de Datos de Testing

```bash
# Crear base de datos para tests
docker-compose exec db mysql -uroot -plaravel_password -e "CREATE DATABASE IF NOT EXISTS laravel_orders_test; GRANT ALL PRIVILEGES ON laravel_orders_test.* TO 'laravel'@'%';"
```

### 5. Ejecución de Migraciones

```bash
# Ejecutar migraciones en base de datos principal
docker-compose exec app php artisan migrate
```

## 🐛 Problemas Resueltos

### 1. Base de Datos No Creada

**Problema:** Las tablas `orders` y `payments` no existían en la base de datos.

**Solución:** 
- Se creó el archivo `.env` copiando desde `.env.example`
- Se ejecutó `php artisan migrate` para crear las tablas

### 2. Tests Fallando con SQLite

**Problema:** Los tests intentaban usar SQLite en memoria pero no estaba configurado correctamente.

**Solución:**
- Se modificó `phpunit.xml` para usar MySQL en lugar de SQLite
- Se creó la base de datos `laravel_orders_test` para los tests
- Se agregó `RefreshDatabase` trait en `TestCase.php` para que las migraciones se ejecuten automáticamente en cada test

### 3. Error en PaymentController

**Problema:** Cuando un pago fallaba la validación, se intentaba acceder a la propiedad `id` de un objeto nulo.

**Solución:**
- Se modificó `PaymentController::store()` para verificar que el pago exista antes de crear el recurso

### 4. Test de Retry Fallando

**Problema:** El test `test_complete_payment_flow_with_retry` fallaba porque creaba dos mocks separados.

**Solución:**
- Se refactorizó el test para usar un solo mock con dos llamadas configuradas en secuencia

## ✅ Estado Actual

### Base de Datos Principal
- **Nombre:** `laravel_orders`
- **Tablas creadas:**
  - ✅ `migrations`
  - ✅ `orders`
  - ✅ `payments`

### Base de Datos de Testing
- **Nombre:** `laravel_orders_test`
- **Configuración:** Se recrea automáticamente en cada test con `RefreshDatabase`

### Tests
```
✅ 14 tests pasando
✅ 114 aserciones
✅ 0 fallos
```

**Tests de Orders:**
- ✅ can create order successfully
- ✅ create order requires validation
- ✅ can list all orders
- ✅ can get specific order
- ✅ returns 404 when order not found
- ✅ order amount must be positive
- ✅ order amount has maximum limit

**Tests de Payments:**
- ✅ can process payment successfully
- ✅ failed payment marks order as failed
- ✅ failed order can receive new payment attempts
- ✅ paid order cannot receive new payments
- ✅ can list payments for order
- ✅ returns 404 when creating payment for nonexistent order
- ✅ complete payment flow with retry

## 🚀 Cómo Usar la API

### Crear un Pedido

```powershell
$body = @{
    customer_name = "Juan Pérez"
    total_amount = 150.50
} | ConvertTo-Json

Invoke-WebRequest -Uri "http://localhost:8000/api/v1/orders" `
    -Method POST `
    -Headers @{"Content-Type"="application/json"; "Accept"="application/json"} `
    -Body $body
```

### Listar Pedidos

```powershell
Invoke-WebRequest -Uri "http://localhost:8000/api/v1/orders" -Method GET
```

### Procesar un Pago

```powershell
# Reemplazar {orderId} con el ID del pedido
Invoke-WebRequest -Uri "http://localhost:8000/api/v1/orders/{orderId}/payments" `
    -Method POST `
    -Headers @{"Content-Type"="application/json"; "Accept"="application/json"}
```

### Listar Pagos de un Pedido

```powershell
# Reemplazar {orderId} con el ID del pedido
Invoke-WebRequest -Uri "http://localhost:8000/api/v1/orders/{orderId}/payments" -Method GET
```

## 📊 Servicios Disponibles

| Servicio | URL | Credenciales |
|----------|-----|--------------|
| API REST | http://localhost:8000 | - |
| PHPMyAdmin | http://localhost:8080 | usuario: `laravel`, password: `laravel_password` |

## 🔄 Comandos Útiles

### Ejecutar Tests

```bash
# Todos los tests
docker-compose exec app php artisan test

# Solo tests Feature
docker-compose exec app php artisan test --testsuite=Feature

# Test específico
docker-compose exec app php artisan test --filter test_can_create_order_successfully
```

### Ver Rutas

```bash
docker-compose exec app php artisan route:list
```

### Limpiar Base de Datos y Recrear

```bash
# Rollback de todas las migraciones
docker-compose exec app php artisan migrate:rollback

# Recrear todas las tablas
docker-compose exec app php artisan migrate
```

### Ver Logs

```bash
# Logs de la aplicación
docker-compose logs -f app

# Logs de Nginx
docker-compose logs -f nginx

# Logs de MySQL
docker-compose logs -f db
```

## 📝 Archivos Modificados

1. **`.env`** - Creado desde `.env.example`
2. **`phpunit.xml`** - Configurado para usar MySQL en lugar de SQLite
3. **`tests/TestCase.php`** - Agregado trait `RefreshDatabase`
4. **`app/Http/Controllers/Api/PaymentController.php`** - Corregido manejo de respuesta cuando el pago falla
5. **`tests/Feature/PaymentTest.php`** - Refactorizado test de retry para usar un solo mock

## 🎯 Requerimientos Cumplidos

✅ Crear pedidos con nombre del cliente, monto total y estado inicial "pending"
✅ Registrar pagos asociados a un pedido existente
✅ Cada intento de pago es por el monto total del pedido
✅ Conexión con API externa simulada para confirmar transacción
✅ Si el pago es exitoso, el pedido pasa a estado "paid"
✅ Si el pago falla, el pedido pasa a estado "failed"
✅ Un pedido en estado "failed" puede recibir nuevos intentos de pago
✅ Listar pedidos mostrando estado actual, intentos de pago y pagos asociados
✅ Tests completos que validan funcionalidades clave
✅ Documentación completa del proyecto

## 📚 Documentación Adicional

- [README.md](README.md) - Documentación principal del proyecto
- [API_EXAMPLES.md](API_EXAMPLES.md) - Ejemplos de uso de la API
- [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) - Resumen del proyecto
- [INSTALLATION.md](INSTALLATION.md) - Guía de instalación
- [QUICKSTART.md](QUICKSTART.md) - Guía rápida de inicio
