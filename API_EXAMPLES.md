# 🔥 Ejemplos de Uso de la API

Esta guía contiene ejemplos prácticos de cómo usar todos los endpoints de la API.

## 📋 Configuración Inicial

**Base URL:** `http://localhost:8000/api/v1`

**Headers requeridos:**
```
Content-Type: application/json
Accept: application/json
```

---

## 1️⃣ Crear un Pedido

### Request
```http
POST /api/v1/orders
Content-Type: application/json

{
  "customer_name": "Juan Pérez",
  "total_amount": 150.50
}
```

### cURL
```bash
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"customer_name":"Juan Pérez","total_amount":150.50}'
```

### PowerShell
```powershell
$body = @{
    customer_name = "Juan Pérez"
    total_amount = 150.50
} | ConvertTo-Json

Invoke-RestMethod -Method Post -Uri "http://localhost:8000/api/v1/orders" `
  -Body $body -ContentType "application/json"
```

### Response (201 Created)
```json
{
  "message": "Order created successfully",
  "data": {
    "id": 1,
    "customer_name": "Juan Pérez",
    "total_amount": "150.50",
    "status": "pending",
    "payment_attempts": 0,
    "payments": [],
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

---

## 2️⃣ Listar Todos los Pedidos

### Request
```http
GET /api/v1/orders
Accept: application/json
```

### cURL
```bash
curl http://localhost:8000/api/v1/orders \
  -H "Accept: application/json"
```

### PowerShell
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/v1/orders" `
  -Headers @{"Accept"="application/json"}
```

### Response (200 OK)
```json
{
  "data": [
    {
      "id": 1,
      "customer_name": "Juan Pérez",
      "total_amount": "150.50",
      "status": "pending",
      "payment_attempts": 0,
      "payments": [],
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    },
    {
      "id": 2,
      "customer_name": "María García",
      "total_amount": "200.00",
      "status": "paid",
      "payment_attempts": 1,
      "payments": [
        {
          "id": 1,
          "amount": "200.00",
          "status": "success",
          "transaction_id": "txn_123456",
          "error_message": null,
          "created_at": "2024-01-15T10:35:00.000000Z",
          "updated_at": "2024-01-15T10:35:00.000000Z"
        }
      ],
      "created_at": "2024-01-15T10:32:00.000000Z",
      "updated_at": "2024-01-15T10:35:00.000000Z"
    }
  ]
}
```

---

## 3️⃣ Obtener un Pedido Específico

### Request
```http
GET /api/v1/orders/1
Accept: application/json
```

### cURL
```bash
curl http://localhost:8000/api/v1/orders/1 \
  -H "Accept: application/json"
```

### PowerShell
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/v1/orders/1" `
  -Headers @{"Accept"="application/json"}
```

### Response (200 OK)
```json
{
  "data": {
    "id": 1,
    "customer_name": "Juan Pérez",
    "total_amount": "150.50",
    "status": "pending",
    "payment_attempts": 0,
    "payments": [],
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

### Response (404 Not Found)
```json
{
  "message": "Order not found"
}
```

---

## 4️⃣ Procesar un Pago

### Request
```http
POST /api/v1/orders/1/payments
Content-Type: application/json
Accept: application/json
```

### cURL
```bash
curl -X POST http://localhost:8000/api/v1/orders/1/payments \
  -H "Content-Type: application/json" \
  -H "Accept: application/json"
```

### PowerShell
```powershell
Invoke-RestMethod -Method Post `
  -Uri "http://localhost:8000/api/v1/orders/1/payments" `
  -ContentType "application/json" `
  -Headers @{"Accept"="application/json"}
```

### Response - Pago Exitoso (201 Created)
```json
{
  "success": true,
  "message": "Payment processed successfully",
  "data": {
    "id": 1,
    "amount": "150.50",
    "status": "success",
    "transaction_id": "txn_abc123xyz",
    "error_message": null,
    "created_at": "2024-01-15T10:35:00.000000Z",
    "updated_at": "2024-01-15T10:35:00.000000Z"
  }
}
```

### Response - Pago Fallido (422 Unprocessable Entity)
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

### Response - Pedido No Válido (422)
```json
{
  "success": false,
  "message": "Order cannot receive payments. Current status: paid",
  "data": null
}
```

---

## 5️⃣ Listar Pagos de un Pedido

### Request
```http
GET /api/v1/orders/1/payments
Accept: application/json
```

### cURL
```bash
curl http://localhost:8000/api/v1/orders/1/payments \
  -H "Accept: application/json"
```

### PowerShell
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/v1/orders/1/payments" `
  -Headers @{"Accept"="application/json"}
```

### Response (200 OK)
```json
{
  "data": [
    {
      "id": 1,
      "amount": "150.50",
      "status": "failed",
      "transaction_id": null,
      "error_message": "Card declined",
      "created_at": "2024-01-15T10:35:00.000000Z",
      "updated_at": "2024-01-15T10:35:00.000000Z"
    },
    {
      "id": 2,
      "amount": "150.50",
      "status": "success",
      "transaction_id": "txn_xyz789",
      "error_message": null,
      "created_at": "2024-01-15T10:40:00.000000Z",
      "updated_at": "2024-01-15T10:40:00.000000Z"
    }
  ]
}
```

---

## 📝 Escenarios Completos

### Escenario 1: Flujo Exitoso Completo

```bash
# 1. Crear pedido
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -d '{"customer_name":"Ana López","total_amount":300.00}'
# Respuesta: {"data":{"id":5,...}}

# 2. Procesar pago (exitoso)
curl -X POST http://localhost:8000/api/v1/orders/5/payments \
  -H "Content-Type: application/json"
# Respuesta: {"success":true,...}

# 3. Verificar pedido (debería estar "paid")
curl http://localhost:8000/api/v1/orders/5
# Respuesta: {"data":{"status":"paid",...}}
```

### Escenario 2: Reintento de Pago Fallido

```bash
# 1. Crear pedido
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -d '{"customer_name":"Carlos Ruiz","total_amount":450.00}'
# Respuesta: {"data":{"id":6,...}}

# 2. Primer intento de pago (puede fallar)
curl -X POST http://localhost:8000/api/v1/orders/6/payments \
  -H "Content-Type: application/json"
# Respuesta: {"success":false,...} (si falla)

# 3. Verificar estado del pedido
curl http://localhost:8000/api/v1/orders/6
# Respuesta: {"data":{"status":"failed","payment_attempts":1,...}}

# 4. Segundo intento de pago
curl -X POST http://localhost:8000/api/v1/orders/6/payments \
  -H "Content-Type: application/json"
# Respuesta: {"success":true,...} (si tiene éxito)

# 5. Verificar pedido actualizado
curl http://localhost:8000/api/v1/orders/6
# Respuesta: {"data":{"status":"paid","payment_attempts":2,...}}

# 6. Ver historial de pagos
curl http://localhost:8000/api/v1/orders/6/payments
# Respuesta: {"data":[{...primer intento...},{...segundo intento...}]}
```

### Escenario 3: Validación de Errores

```bash
# 1. Intentar crear pedido sin datos
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -d '{}'
# Respuesta (422): Errores de validación

# 2. Intentar crear pedido con monto negativo
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -d '{"customer_name":"Test","total_amount":-50.00}'
# Respuesta (422): Error de validación

# 3. Intentar obtener pedido inexistente
curl http://localhost:8000/api/v1/orders/999
# Respuesta (404): Order not found

# 4. Intentar pagar pedido inexistente
curl -X POST http://localhost:8000/api/v1/orders/999/payments \
  -H "Content-Type: application/json"
# Respuesta (404): Order not found
```

---

## 🧪 Testing con Postman

### Importar Colección
1. Abrir Postman
2. Click en "Import"
3. Seleccionar `postman_collection.json`

### Variables de Entorno en Postman
```json
{
  "base_url": "http://localhost:8000/api/v1",
  "order_id": "1"
}
```

### Uso de Variables
```
{{base_url}}/orders
{{base_url}}/orders/{{order_id}}/payments
```

---

## 💡 Tips para Testing

### 1. Crear Múltiples Pedidos
```bash
for i in {1..5}; do
  curl -X POST http://localhost:8000/api/v1/orders \
    -H "Content-Type: application/json" \
    -d "{\"customer_name\":\"Cliente $i\",\"total_amount\":$((100 + i * 50))}"
  echo ""
done
```

### 2. Monitorear Logs en Tiempo Real
```bash
docker-compose logs -f app
```

### 3. Verificar Base de Datos
Acceder a PHPMyAdmin: http://localhost:8080
- Ver tabla `orders`
- Ver tabla `payments`
- Verificar cambios de estado

### 4. Ejecutar Tests
```bash
docker-compose exec app php artisan test --filter PaymentTest
```

---

## 🐛 Debugging

### Ver Respuesta Completa con cURL
```bash
curl -v http://localhost:8000/api/v1/orders
```

### Pretty Print JSON
```bash
curl http://localhost:8000/api/v1/orders | python -m json.tool
```

### Guardar Respuesta en Archivo
```bash
curl http://localhost:8000/api/v1/orders > orders.json
```

---

## 📊 Códigos de Estado HTTP

| Código | Significado | Cuándo se Usa |
|--------|-------------|---------------|
| 200 | OK | GET exitoso |
| 201 | Created | POST exitoso |
| 404 | Not Found | Recurso no existe |
| 422 | Unprocessable Entity | Error de validación o lógica |
| 500 | Server Error | Error interno del servidor |

---

## ✅ Checklist de Testing Manual

- [ ] Crear pedido con datos válidos
- [ ] Crear pedido con datos inválidos (verificar errores)
- [ ] Listar todos los pedidos
- [ ] Obtener pedido específico existente
- [ ] Obtener pedido inexistente (404)
- [ ] Procesar pago exitoso
- [ ] Procesar pago fallido
- [ ] Reintentar pago en pedido fallido
- [ ] Intentar pagar pedido ya pagado (debe rechazar)
- [ ] Listar pagos de un pedido
- [ ] Verificar cambios de estado en BD

---

**🎉 ¡Ahora estás listo para usar la API!**

Para más información, consulta:
- README.md - Documentación completa
- QUICKSTART.md - Inicio rápido
- postman_collection.json - Colección Postman
