# API REST - Sistema de Gestión de Pedidos y Pagos

API REST desarrollada en Laravel para gestionar Pedidos (Orders) y Pagos (Payments) con integración a una API externa simulada para el procesamiento de pagos.

## 📋 Tabla de Contenidos

- [Características](#características)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Arquitectura del Proyecto](#arquitectura-del-proyecto)
- [API Endpoints](#api-endpoints)
- [Tests](#tests)
- [Decisiones Técnicas](#decisiones-técnicas)

## ✨ Características

- ✅ Crear pedidos con nombre del cliente, monto total y estado inicial "pending"
- ✅ Registrar pagos asociados a un pedido existente
- ✅ Cada intento de pago es por el monto total del pedido
- ✅ Integración con API externa para confirmar transacciones
- ✅ Gestión automática de estados:
  - Pedido pasa a "paid" si el pago es exitoso
  - Pedido pasa a "failed" si el pago falla
  - Pedidos en estado "failed" pueden recibir nuevos intentos de pago
- ✅ Listar pedidos con su estado actual, intentos de pago y pagos asociados
- ✅ Tests feature completos que validan funcionalidades clave
- ✅ **Documentación interactiva con Swagger UI** (OpenAPI 3.0)

## 🔧 Requisitos

- Docker Desktop instalado
- Docker Compose
- Git (opcional)

## 🚀 Instalación

### Opción 1: Instalación Automática (Windows)

```bash
# Ejecutar el script de instalación
setup.bat
```

Este script automáticamente:
- ✅ Copia el archivo .env
- ✅ Instala dependencias de Composer
- ✅ Genera la clave de aplicación
- ✅ Levanta los contenedores Docker
- ✅ Ejecuta las migraciones
- ✅ **Genera la documentación de Swagger**
- ✅ Configura permisos

### Opción 2: Instalación Manual

1. **Clonar el repositorio**
```bash
git clone <url-del-repositorio>
cd API_4REST_LARAVEL
```

2. **Copiar archivo de configuración**
```bash
copy .env.example .env
```

3. **Instalar dependencias**
```bash
docker-compose run --rm app composer install
```

4. **Generar la clave de aplicación**
```bash
docker-compose run --rm app php artisan key:generate
```

5. **Levantar los contenedores Docker**
```bash
docker-compose up -d
```

6. **Ejecutar las migraciones**
```bash
docker-compose exec app php artisan migrate
```

7. **⚠️ IMPORTANTE: Generar documentación de Swagger**
```bash
docker-compose exec app php artisan l5-swagger:generate
```

8. **Configurar permisos**
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### 🔄 Regenerar Documentación de Swagger

Si actualizas los controladores o las anotaciones de Swagger, debes regenerar la documentación:

```bash
docker-compose exec app php artisan l5-swagger:generate
```

## ⚙️ Configuración

### Variables de Entorno

Las principales variables de entorno en el archivo `.env`:

```env
# Aplicación
APP_NAME="Orders & Payments API"
APP_URL=http://localhost:8000

# Base de Datos
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_orders
DB_USERNAME=laravel
DB_PASSWORD=laravel_password

# API Externa de Pagos
PAYMENT_API_URL=https://reqres.in/api
PAYMENT_API_TIMEOUT=30
PAYMENT_API_SUCCESS_ENDPOINT=/users
PAYMENT_API_RETRY_ATTEMPTS=3
```

### Servicios Disponibles

Una vez levantados los contenedores:

- **API REST**: http://localhost:8000
- **📚 Documentación Swagger**: http://localhost:8000 (redirecciona automáticamente)
  - También disponible en: http://localhost:8000/api/documentation
- **PHPMyAdmin**: http://localhost:8080
  - Usuario: `laravel`
  - Contraseña: `laravel_password`

## 🏗️ Arquitectura del Proyecto

### Estructura de Directorios

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── OrderController.php
│   │       └── PaymentController.php
│   ├── Requests/
│   │   └── CreateOrderRequest.php
│   └── Resources/
│       ├── OrderResource.php
│       └── PaymentResource.php
├── Models/
│   ├── Order.php
│   └── Payment.php
├── Repositories/
│   ├── OrderRepository.php
│   └── PaymentRepository.php
├── Services/
│   ├── OrderService.php
│   ├── PaymentService.php
│   └── ExternalPaymentService.php
└── Providers/
    └── AppServiceProvider.php

database/
├── migrations/
│   ├── 2024_01_01_000001_create_orders_table.php
│   └── 2024_01_01_000002_create_payments_table.php
└── factories/
    ├── OrderFactory.php
    └── PaymentFactory.php

tests/
└── Feature/
    ├── OrderTest.php
    └── PaymentTest.php
```

### Patrones de Diseño Implementados

#### 1. Repository Pattern
Abstrae la capa de acceso a datos, facilitando testing y mantenimiento:
- `OrderRepository`: Gestiona operaciones de base de datos para pedidos
- `PaymentRepository`: Gestiona operaciones de base de datos para pagos

#### 2. Service Layer Pattern
Encapsula la lógica de negocio:
- `OrderService`: Lógica de negocio de pedidos
- `PaymentService`: Procesamiento de pagos y coordinación con servicios externos
- `ExternalPaymentService`: Integración con API externa

#### 3. Dependency Injection
Todos los servicios y repositorios están registrados en el contenedor de Laravel para facilitar testing y desacoplamiento.

### Modelo de Datos

#### Tabla: `orders`
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Identificador único |
| customer_name | varchar(255) | Nombre del cliente |
| total_amount | decimal(10,2) | Monto total del pedido |
| status | enum | Estado: pending, paid, failed |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

#### Tabla: `payments`
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | Identificador único |
| order_id | bigint | FK a orders |
| amount | decimal(10,2) | Monto del pago |
| status | enum | Estado: pending, success, failed |
| transaction_id | varchar(255) | ID de transacción externa |
| response_data | json | Respuesta de la API externa |
| error_message | text | Mensaje de error si falla |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Fecha de actualización |

## 📡 API Endpoints

### 📚 Documentación Interactiva

**La forma más fácil de explorar y probar la API es usando Swagger UI:**

👉 **http://localhost:8000** (redirecciona automáticamente a la documentación)

Swagger UI te permite:
- ✅ Ver todos los endpoints disponibles
- ✅ Probar cada endpoint directamente desde el navegador
- ✅ Ver ejemplos de request y response
- ✅ Consultar los modelos de datos
- ✅ Ver códigos de error y validaciones

Para más información sobre Swagger, consulta [SWAGGER_SETUP.md](SWAGGER_SETUP.md)

### Base URL
```
http://localhost:8000/api/v1
```

### Orders (Pedidos)

#### 1. Crear Pedido
```http
POST /orders
Content-Type: application/json

{
  "customer_name": "Juan Pérez",
  "total_amount": 150.50
}
```

**Respuesta exitosa (201):**
```json
{
  "message": "Order created successfully",
  "data": {
    "id": 1,
    "customer_name": "Juan Pérez",
    "total_amount": "150.50",
    "status": "pending",
    "payment_attempts": 0,
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

#### 2. Listar Todos los Pedidos
```http
GET /orders
```

**Respuesta exitosa (200):**
```json
{
  "data": [
    {
      "id": 1,
      "customer_name": "Juan Pérez",
      "total_amount": "150.50",
      "status": "pending",
      "payment_attempts": 2,
      "payments": [...],
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ]
}
```

#### 3. Obtener Pedido Específico
```http
GET /orders/{id}
```

**Respuesta exitosa (200):**
```json
{
  "data": {
    "id": 1,
    "customer_name": "Juan Pérez",
    "total_amount": "150.50",
    "status": "paid",
    "payment_attempts": 1,
    "payments": [
      {
        "id": 1,
        "amount": "150.50",
        "status": "success",
        "transaction_id": "txn_123456",
        "error_message": null,
        "created_at": "2024-01-15T10:35:00.000000Z"
      }
    ],
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:35:00.000000Z"
  }
}
```

### Payments (Pagos)

#### 4. Procesar Pago
```http
POST /orders/{orderId}/payments
```

**Respuesta exitosa (201):**
```json
{
  "success": true,
  "message": "Payment processed successfully",
  "data": {
    "id": 1,
    "amount": "150.50",
    "status": "success",
    "transaction_id": "txn_123456",
    "error_message": null,
    "created_at": "2024-01-15T10:35:00.000000Z",
    "updated_at": "2024-01-15T10:35:00.000000Z"
  }
}
```

**Respuesta de fallo (422):**
```json
{
  "success": false,
  "message": "Insufficient funds",
  "data": {
    "id": 2,
    "amount": "150.50",
    "status": "failed",
    "transaction_id": null,
    "error_message": "Payment gateway rejected the transaction",
    "created_at": "2024-01-15T10:40:00.000000Z",
    "updated_at": "2024-01-15T10:40:00.000000Z"
  }
}
```

#### 5. Listar Pagos de un Pedido
```http
GET /orders/{orderId}/payments
```

**Respuesta exitosa (200):**
```json
{
  "data": [
    {
      "id": 1,
      "amount": "150.50",
      "status": "failed",
      "transaction_id": null,
      "error_message": "Card declined",
      "created_at": "2024-01-15T10:35:00.000000Z"
    },
    {
      "id": 2,
      "amount": "150.50",
      "status": "success",
      "transaction_id": "txn_789012",
      "error_message": null,
      "created_at": "2024-01-15T10:40:00.000000Z"
    }
  ]
}
```

### Códigos de Estado HTTP

| Código | Descripción |
|--------|-------------|
| 200 | Solicitud exitosa (GET) |
| 201 | Recurso creado exitosamente (POST) |
| 404 | Recurso no encontrado |
| 422 | Error de validación o lógica de negocio |
| 500 | Error interno del servidor |

## 🧪 Tests

### Ejecutar Tests

```bash
# Ejecutar todos los tests
docker-compose exec app php artisan test

# Ejecutar tests con cobertura
docker-compose exec app php artisan test --coverage

# Ejecutar tests específicos
docker-compose exec app php artisan test --filter OrderTest
docker-compose exec app php artisan test --filter PaymentTest
```

### Cobertura de Tests

#### OrderTest (8 tests)
- ✅ Creación exitosa de pedidos
- ✅ Validación de campos requeridos
- ✅ Listado de todos los pedidos
- ✅ Obtención de pedido específico
- ✅ Manejo de errores 404
- ✅ Validación de montos positivos
- ✅ Validación de límites máximos

#### PaymentTest (9 tests)
- ✅ Procesamiento exitoso de pagos
- ✅ Manejo de pagos fallidos
- ✅ Reintentos en pedidos fallidos
- ✅ Bloqueo de pagos en pedidos ya pagados
- ✅ Listado de pagos por pedido
- ✅ Manejo de errores 404
- ✅ Flujo completo con reintentos

### Comandos Útiles

```bash
# Limpiar base de datos y ejecutar tests
docker-compose exec app php artisan migrate:fresh
docker-compose exec app php artisan test

# Ver logs de la aplicación
docker-compose logs -f app

# Acceder a la consola del contenedor
docker-compose exec app bash

# Ejecutar comandos artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
```

## 💡 Decisiones Técnicas

### 1. Arquitectura en Capas
**Decisión**: Implementar Repository y Service Layer patterns

**Razón**: 
- Separación clara de responsabilidades
- Facilita testing mediante mocking
- Código más mantenible y escalable
- Facilita cambios futuros (ej: cambiar ORM)

### 2. Docker y Docker Compose
**Decisión**: Containerizar toda la aplicación

**Razón**:
- Entorno reproducible en cualquier máquina
- Evita problemas de "funciona en mi máquina"
- Fácil de escalar y desplegar
- Incluye todos los servicios necesarios (MySQL, PHPMyAdmin)

### 3. API REST con Recursos
**Decisión**: Usar Laravel API Resources para transformar datos

**Razón**:
- Formato consistente de respuestas
- Control sobre qué datos exponer
- Facilita versionado de API
- Documentación clara de la estructura

### 4. API Externa Simulada
**Decisión**: Usar reqres.in con lógica de simulación

**Razón**:
- No requiere configuración adicional
- Permite simular tanto éxitos como fallos (30% de probabilidad de fallo)
- Realista para demostración
- Fácilmente reemplazable por API real

### 5. Estados del Pedido
**Decisión**: Tres estados (pending, paid, failed) con reglas claras

**Razón**:
- Flujo simple y comprensible
- Permite reintentos solo cuando tiene sentido
- Previene pagos duplicados
- Facilita auditoría

### 6. Transacciones de Base de Datos
**Decisión**: Usar DB::transaction() en operaciones críticas

**Razón**:
- Garantiza consistencia de datos
- Si falla el pago externo, se rollback todo
- Evita estados inconsistentes

### 7. Logging Completo
**Decisión**: Registrar todas las operaciones importantes

**Razón**:
- Facilita debugging
- Auditoría de transacciones
- Monitoreo de fallos
- Historial completo

### 8. Validación de Requests
**Decisión**: Form Requests dedicados con mensajes personalizados

**Razón**:
- Validación antes de llegar al controlador
- Reutilizable
- Mensajes claros para usuarios
- Facilita testing

### 9. Factories para Testing
**Decisión**: Implementar factories completas con estados

**Razón**:
- Tests más legibles
- Generación fácil de datos de prueba
- Reutilización en múltiples tests
- Permite testing de casos edge

### 10. Testing Feature vs Unit
**Decisión**: Priorizar Feature Tests

**Razón**:
- Prueban flujos completos
- Más valor en detectar bugs
- Simulan comportamiento real de usuarios
- Cubren integración entre componentes

## 📝 Notas Adicionales

### API Externa (reqres.in)
La aplicación está configurada para usar reqres.in como API simulada. El servicio:
- Siempre responde exitosamente (200)
- Incluimos lógica adicional para simular fallos (30% probabilidad)
- En producción, reemplazar por gateway real de pagos

### Seguridad
Para producción, considerar:
- Implementar autenticación (Laravel Sanctum/Passport)
- Rate limiting en endpoints
- CORS configurado apropiadamente
- Variables de entorno en secretos
- HTTPS obligatorio

### Escalabilidad
El diseño permite:
- Migrar a microservicios si es necesario
- Implementar caché (Redis)
- Queue para procesamiento asíncrono de pagos
- Replicación de base de datos

## 👨‍💻 Desarrollo

### Detener los Contenedores
```bash
docker-compose down
```

### Eliminar Volúmenes (Limpieza completa)
```bash
docker-compose down -v
```

### Rebuild de Contenedores
```bash
docker-compose up -d --build
```

## 📄 Licencia

Este proyecto es una prueba técnica y está disponible para fines educativos.

---

**Desarrollado con ❤️ usando Laravel 11, Docker, MySQL y buenas prácticas de desarrollo**
