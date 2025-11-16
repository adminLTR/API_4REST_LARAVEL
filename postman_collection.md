# Colección Postman - API Orders & Payments

Esta colección contiene todos los endpoints de la API para facilitar las pruebas.

## Importar en Postman

1. Abrir Postman
2. Click en "Import"
3. Seleccionar el archivo `postman_collection.json`
4. Los endpoints estarán disponibles en tu workspace

## Endpoints Incluidos

### Orders
- GET List Orders
- POST Create Order
- GET Get Order

### Payments
- POST Process Payment
- GET List Payments by Order

## Variables de Entorno Sugeridas

```json
{
  "base_url": "http://localhost:8000/api/v1"
}
```
