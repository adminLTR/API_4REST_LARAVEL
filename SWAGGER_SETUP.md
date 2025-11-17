# 📚 Documentación Swagger - Orders & Payments API

## ✅ Configuración Completada

Se ha integrado **Swagger UI** (usando L5-Swagger) para documentar la API de forma interactiva.

## 🌐 Acceso a la Documentación

### Ruta Principal (Recomendada)
```
http://localhost:8000/
```
→ **Redirige automáticamente a la documentación de Swagger**

### Ruta Directa
```
http://localhost:8000/api/documentation
```

## 📋 Características de la Documentación

### Endpoints Documentados

#### 🛒 Orders (Pedidos)
1. **GET** `/api/v1/orders` - Listar todos los pedidos
2. **POST** `/api/v1/orders` - Crear un nuevo pedido
3. **GET** `/api/v1/orders/{id}` - Obtener un pedido específico

#### 💳 Payments (Pagos)
1. **POST** `/api/v1/orders/{orderId}/payments` - Procesar un pago
2. **GET** `/api/v1/orders/{orderId}/payments` - Listar pagos de un pedido

### Información Incluida en la Documentación

Para cada endpoint se documenta:
- ✅ Descripción detallada del funcionamiento
- ✅ Parámetros requeridos y opcionales
- ✅ Formato del cuerpo de la petición (Request Body)
- ✅ Ejemplos de respuestas exitosas
- ✅ Códigos de estado HTTP
- ✅ Ejemplos de errores de validación
- ✅ Esquemas de datos (Models)

### Schemas Definidos

- **Order**: Modelo completo de un pedido con sus propiedades
- **Payment**: Modelo completo de un pago con sus propiedades

## 🧪 Probar la API desde Swagger UI

Swagger UI permite:

1. **Ver todos los endpoints** organizados por categorías (Tags)
2. **Explorar los modelos de datos** (Schemas)
3. **Probar endpoints directamente** desde el navegador:
   - Click en el endpoint
   - Click en "Try it out"
   - Completar los parámetros requeridos
   - Click en "Execute"
   - Ver la respuesta en tiempo real

## 🔄 Regenerar la Documentación

Si agregas nuevos endpoints o modificas las anotaciones:

```bash
docker-compose exec app php artisan l5-swagger:generate
```

## 📝 Ejemplo de Uso desde Swagger UI

### 1. Crear un Pedido
```
POST /api/v1/orders
```
Body:
```json
{
  "customer_name": "Juan Pérez",
  "total_amount": 150.50
}
```

### 2. Procesar un Pago
```
POST /api/v1/orders/1/payments
```
(No requiere body, solo el ID del pedido en la URL)

### 3. Listar Pedidos
```
GET /api/v1/orders
```

## 🛠️ Configuración Técnica

### Archivos Modificados

1. **Controller.php**
   - Agregadas anotaciones OpenAPI base
   - Definidos schemas de Order y Payment
   - Configurados tags y servers

2. **OrderController.php**
   - Documentados 3 endpoints de Orders
   - Incluidos ejemplos de request/response
   - Agregados códigos de error

3. **PaymentController.php**
   - Documentados 2 endpoints de Payments
   - Incluida lógica de estados del pedido
   - Documentados casos de error

4. **routes/web.php**
   - Configurada redirección desde `/` a `/api/documentation`

### Paquetes Instalados

- `darkaonline/l5-swagger` (v9.0.1)
- `zircote/swagger-php` (v5.7.1)
- `swagger-api/swagger-ui` (v5.30.2)

### Archivos de Configuración

- `config/l5-swagger.php` - Configuración de L5-Swagger
- `storage/api-docs/api-docs.json` - Documentación generada en formato JSON

## 📖 Estándares OpenAPI

La documentación sigue el estándar **OpenAPI 3.0** (anteriormente Swagger), que es el estándar de la industria para documentar APIs REST.

### Anotaciones Principales Usadas

- `@OA\Info` - Información general de la API
- `@OA\Server` - Servidores disponibles
- `@OA\Tag` - Categorización de endpoints
- `@OA\Get/Post` - Definición de endpoints
- `@OA\Parameter` - Parámetros de ruta/query
- `@OA\RequestBody` - Cuerpo de la petición
- `@OA\Response` - Respuestas posibles
- `@OA\Schema` - Modelos de datos
- `@OA\Property` - Propiedades de los modelos

## 🎨 Personalización

### Cambiar el Título o Descripción

Edita las anotaciones en `app/Http/Controllers/Controller.php`:

```php
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Tu Título Aquí",
 *     description="Tu descripción aquí"
 * )
 */
```

### Agregar Más Servidores

```php
/**
 * @OA\Server(
 *     url="https://api.production.com",
 *     description="Servidor de producción"
 * )
 */
```

### Agregar Autenticación

```php
/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */
```

## 📚 Referencias

- [L5-Swagger Documentation](https://github.com/DarkaOnLine/L5-Swagger)
- [OpenAPI Specification](https://swagger.io/specification/)
- [Swagger UI](https://swagger.io/tools/swagger-ui/)

## ✨ Ventajas de Swagger

1. **Documentación Interactiva**: Los desarrolladores pueden probar la API sin herramientas externas
2. **Siempre Actualizada**: La documentación vive en el código
3. **Generación Automática**: Se genera a partir de las anotaciones
4. **Estándar de la Industria**: OpenAPI es ampliamente adoptado
5. **Generación de Clientes**: Puedes generar SDKs automáticamente
6. **Testing Simplificado**: Prueba endpoints sin Postman

## 🎯 Próximos Pasos

1. ✅ Swagger está instalado y funcionando
2. ✅ La documentación se muestra en la URL raíz
3. ✅ Todos los endpoints están documentados
4. ✅ Los tests siguen pasando

**¡La API está completamente documentada y lista para usar!** 🚀
