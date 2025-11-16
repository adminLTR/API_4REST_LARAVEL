# 📁 Estructura de Archivos del Proyecto

```
PruebaTecnica-Laravel/
│
├── 📁 app/                                    # Código de la aplicación
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   ├── 📁 Api/
│   │   │   │   ├── OrderController.php       # Controlador de pedidos
│   │   │   │   └── PaymentController.php     # Controlador de pagos
│   │   │   └── Controller.php                # Controlador base
│   │   ├── 📁 Requests/
│   │   │   └── CreateOrderRequest.php        # Validación crear pedido
│   │   └── 📁 Resources/
│   │       ├── OrderResource.php             # Transformación datos pedido
│   │       └── PaymentResource.php           # Transformación datos pago
│   ├── 📁 Models/
│   │   ├── Order.php                         # Modelo Pedido
│   │   └── Payment.php                       # Modelo Pago
│   ├── 📁 Repositories/
│   │   ├── OrderRepository.php               # Acceso a datos pedidos
│   │   └── PaymentRepository.php             # Acceso a datos pagos
│   ├── 📁 Services/
│   │   ├── OrderService.php                  # Lógica negocio pedidos
│   │   ├── PaymentService.php                # Lógica negocio pagos
│   │   └── ExternalPaymentService.php        # Integración API externa
│   └── 📁 Providers/
│       └── AppServiceProvider.php            # Registro de servicios
│
├── 📁 bootstrap/
│   ├── 📁 cache/
│   │   └── .gitignore
│   └── app.php                               # Bootstrap aplicación
│
├── 📁 config/                                 # Archivos de configuración
│   ├── app.php                               # Config general aplicación
│   ├── database.php                          # Config base de datos
│   └── services.php                          # Config servicios externos
│
├── 📁 database/
│   ├── 📁 factories/
│   │   ├── OrderFactory.php                  # Factory para testing
│   │   └── PaymentFactory.php                # Factory para testing
│   └── 📁 migrations/
│       ├── 2024_01_01_000001_create_orders_table.php
│       └── 2024_01_01_000002_create_payments_table.php
│
├── 📁 docker/                                 # Configuración Docker
│   └── 📁 nginx/
│       ├── default.conf                      # Config básica Nginx
│       └── nginx.conf                        # Config completa Nginx
│
├── 📁 public/                                 # Punto de entrada público
│   └── index.php                             # Entry point de la app
│
├── 📁 routes/                                 # Definición de rutas
│   ├── api.php                               # Rutas API REST
│   ├── web.php                               # Rutas web
│   └── console.php                           # Comandos Artisan
│
├── 📁 storage/                                # Archivos generados
│   └── 📁 logs/
│       └── .gitignore
│
├── 📁 tests/                                  # Tests automatizados
│   ├── 📁 Feature/
│   │   ├── OrderTest.php                     # Tests de pedidos (8 tests)
│   │   └── PaymentTest.php                   # Tests de pagos (9 tests)
│   └── TestCase.php                          # Clase base para tests
│
├── 📄 .dockerignore                          # Archivos ignorados por Docker
├── 📄 .env.example                           # Plantilla variables entorno
├── 📄 .gitignore                             # Archivos ignorados por Git
├── 📄 artisan                                # CLI de Laravel
├── 📄 composer.json                          # Dependencias PHP
├── 📄 docker-compose.yml                     # Orquestación contenedores
├── 📄 Dockerfile                             # Imagen Docker PHP
├── 📄 phpunit.xml                            # Config PHPUnit testing
├── 📄 setup.bat                              # Script instalación Windows
├── 📄 setup.sh                               # Script instalación Linux/Mac
│
├── 📚 Documentación/
│   ├── 📄 README.md                          # ⭐ Documentación principal
│   ├── 📄 INSTALLATION.md                    # Guía de instalación
│   ├── 📄 QUICKSTART.md                      # Inicio rápido
│   ├── 📄 API_EXAMPLES.md                    # Ejemplos de uso API
│   ├── 📄 PROJECT_SUMMARY.md                 # Resumen del proyecto
│   ├── 📄 CHANGELOG.md                       # Historial de cambios
│   ├── 📄 postman_collection.json            # Colección Postman
│   └── 📄 postman_collection.md              # Info colección Postman
│
└── 📄 FILE_STRUCTURE.md                      # Este archivo

```

## 📊 Estadísticas del Proyecto

### Archivos por Categoría
- **Código fuente (app/):** 13 archivos
- **Configuración:** 5 archivos
- **Base de datos:** 4 archivos
- **Tests:** 3 archivos
- **Docker:** 4 archivos
- **Rutas:** 3 archivos
- **Documentación:** 9 archivos
- **Scripts:** 2 archivos
- **Config general:** 6 archivos

**Total:** ~50 archivos

### Líneas de Código (aproximado)
- **PHP (lógica negocio):** ~1,500 líneas
- **Tests:** ~600 líneas
- **Configuración:** ~400 líneas
- **Documentación:** ~3,000 líneas

**Total:** ~5,500 líneas

## 🎯 Archivos Principales a Revisar

### Para Entender la Lógica de Negocio
1. `app/Services/PaymentService.php` - Procesamiento de pagos
2. `app/Services/OrderService.php` - Gestión de pedidos
3. `app/Services/ExternalPaymentService.php` - Integración API externa

### Para Entender la API
1. `routes/api.php` - Definición de endpoints
2. `app/Http/Controllers/Api/OrderController.php` - Endpoints pedidos
3. `app/Http/Controllers/Api/PaymentController.php` - Endpoints pagos

### Para Entender los Modelos
1. `app/Models/Order.php` - Modelo de pedido
2. `app/Models/Payment.php` - Modelo de pago
3. `database/migrations/` - Estructura de BD

### Para Entender los Tests
1. `tests/Feature/OrderTest.php` - Tests de pedidos
2. `tests/Feature/PaymentTest.php` - Tests de pagos
3. `phpunit.xml` - Configuración de tests

### Para la Instalación
1. `README.md` - Documentación completa ⭐
2. `INSTALLATION.md` - Guía paso a paso
3. `setup.bat` - Script automatizado
4. `docker-compose.yml` - Configuración servicios

## 🗂️ Directorios Clave

### `/app` - Código de la Aplicación
Contiene toda la lógica de la aplicación organizada por responsabilidades.

### `/database` - Base de Datos
Migraciones, factories y seeders para la base de datos.

### `/tests` - Tests Automatizados
Tests feature que validan la funcionalidad completa del sistema.

### `/docker` - Configuración Docker
Archivos de configuración para contenedores y servicios.

### `/config` - Configuración
Archivos de configuración de Laravel y servicios externos.

### `/routes` - Rutas
Definición de todas las rutas de la aplicación (API, Web, Console).

## 📝 Archivos de Configuración Importantes

| Archivo | Propósito |
|---------|-----------|
| `.env.example` | Plantilla de variables de entorno |
| `docker-compose.yml` | Orquestación de contenedores |
| `Dockerfile` | Imagen Docker de la aplicación |
| `phpunit.xml` | Configuración de tests |
| `composer.json` | Dependencias PHP |
| `config/services.php` | Config API externa |
| `config/database.php` | Config base de datos |

## 🧪 Archivos de Testing

| Archivo | Tests | Descripción |
|---------|-------|-------------|
| `OrderTest.php` | 8 | Tests de funcionalidad de pedidos |
| `PaymentTest.php` | 9 | Tests de procesamiento de pagos |
| `TestCase.php` | - | Clase base para todos los tests |

## 📚 Documentación

| Archivo | Propósito |
|---------|-----------|
| `README.md` | Documentación completa del proyecto |
| `INSTALLATION.md` | Guía detallada de instalación |
| `QUICKSTART.md` | Inicio rápido y comandos útiles |
| `API_EXAMPLES.md` | Ejemplos de uso de todos los endpoints |
| `PROJECT_SUMMARY.md` | Resumen ejecutivo del proyecto |
| `CHANGELOG.md` | Historial de cambios y versiones |
| `FILE_STRUCTURE.md` | Este archivo |

## 🔍 Buscar Archivos Específicos

### Por Funcionalidad

**Crear Pedidos:**
- `app/Http/Controllers/Api/OrderController.php::store()`
- `app/Services/OrderService.php::createOrder()`
- `app/Repositories/OrderRepository.php::create()`

**Procesar Pagos:**
- `app/Http/Controllers/Api/PaymentController.php::store()`
- `app/Services/PaymentService.php::processPayment()`
- `app/Services/ExternalPaymentService.php::processPayment()`

**Validaciones:**
- `app/Http/Requests/CreateOrderRequest.php`

**Transformaciones:**
- `app/Http/Resources/OrderResource.php`
- `app/Http/Resources/PaymentResource.php`

### Por Capa

**Capa de Presentación (API):**
- `app/Http/Controllers/Api/`
- `app/Http/Resources/`
- `routes/api.php`

**Capa de Negocio:**
- `app/Services/`

**Capa de Datos:**
- `app/Repositories/`
- `app/Models/`
- `database/migrations/`

**Capa de Testing:**
- `tests/Feature/`

## 🚀 Inicio Rápido

Para empezar a explorar el código:

1. **Leer primero:** `README.md`
2. **Entender la API:** `routes/api.php`
3. **Ver un flujo completo:** 
   - `OrderController::store()` → 
   - `OrderService::createOrder()` → 
   - `OrderRepository::create()` → 
   - `Order::create()`
4. **Explorar tests:** `tests/Feature/PaymentTest.php`
5. **Probar API:** `API_EXAMPLES.md`

## ✅ Checklist de Archivos Esenciales

### Desarrollo
- [x] Modelos (Order, Payment)
- [x] Migraciones
- [x] Factories
- [x] Repositorios
- [x] Servicios
- [x] Controladores API
- [x] Validaciones
- [x] Resources
- [x] Rutas API

### Infraestructura
- [x] Dockerfile
- [x] docker-compose.yml
- [x] Configuración Nginx
- [x] Variables de entorno

### Testing
- [x] PHPUnit config
- [x] Feature tests
- [x] Test base class

### Documentación
- [x] README completo
- [x] Guía de instalación
- [x] Ejemplos de API
- [x] Colección Postman

---

**Última actualización:** Enero 2024  
**Versión:** 1.0.0
