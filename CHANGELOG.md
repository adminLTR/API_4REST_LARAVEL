# Changelog

Todas las cambios notables en este proyecto serán documentados aquí.

## [1.0.0] - 2024-01-15

### Agregado
- ✨ Sistema completo de gestión de pedidos (Orders)
- ✨ Sistema de procesamiento de pagos (Payments)
- ✨ Integración con API externa simulada (reqres.in)
- ✨ API REST con endpoints completos
- ✨ Tests feature para Orders y Payments
- ✨ Dockerización completa del proyecto
- ✨ MySQL como base de datos
- ✨ PHPMyAdmin para gestión de BD
- ✨ Documentación completa en README
- ✨ Colección Postman incluida
- ✨ Scripts de instalación automatizada

### Características
- 🔄 Gestión de estados de pedidos (pending, paid, failed)
- 🔄 Permitir reintentos de pago en pedidos fallidos
- 🔄 Bloqueo de pagos duplicados en pedidos ya pagados
- 📊 Tracking completo de intentos de pago
- 🔒 Transacciones de BD para consistencia de datos
- 📝 Logging completo de operaciones
- ✅ Validación robusta de datos
- 🧪 Cobertura completa de tests

### Decisiones Técnicas
- Patrón Repository para acceso a datos
- Service Layer para lógica de negocio
- API Resources para respuestas consistentes
- Dependency Injection para facilitar testing
- Docker Compose para entorno reproducible
