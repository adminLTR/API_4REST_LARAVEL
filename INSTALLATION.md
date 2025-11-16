# 🚀 Guía de Instalación Completa

Esta guía te llevará paso a paso por la instalación del proyecto Laravel de gestión de Pedidos y Pagos.

## 📋 Pre-requisitos

Antes de comenzar, asegúrate de tener instalado:

- ✅ **Docker Desktop** (Windows/Mac) o **Docker Engine** (Linux)
  - Descargar: https://www.docker.com/products/docker-desktop
- ✅ **Git** (opcional, para clonar el repositorio)
  - Descargar: https://git-scm.com/downloads

## 🔧 Instalación Paso a Paso

### Paso 1: Verificar Docker

Abre una terminal (PowerShell en Windows) y verifica que Docker está instalado:

```powershell
docker --version
docker-compose --version
```

Deberías ver algo como:
```
Docker version 24.0.0
Docker Compose version v2.20.0
```

### Paso 2: Obtener el Proyecto

Si tienes Git instalado:
```powershell
git clone <url-del-repositorio>
cd PruebaTecnica-Laravel
```

Si descargaste un ZIP:
1. Extrae el archivo ZIP
2. Abre PowerShell en la carpeta extraída

### Paso 3: Instalación Automática (RECOMENDADO)

Ejecuta el script de instalación:

```powershell
.\setup.bat
```

Este script hará automáticamente:
- ✅ Crear proyecto Laravel
- ✅ Copiar variables de entorno
- ✅ Generar APP_KEY
- ✅ Levantar contenedores Docker
- ✅ Ejecutar migraciones
- ✅ Configurar permisos

**¡Espera 2-3 minutos mientras se completa!**

### Paso 4: Verificar la Instalación

Una vez completado, abre tu navegador en:

- **API**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8080

Deberías ver un JSON con información de la API.

---

## 🔨 Instalación Manual (Alternativa)

Si prefieres hacer la instalación paso a paso:

### 1. Copiar Variables de Entorno
```powershell
copy .env.example .env
```

### 2. Levantar Contenedores
```powershell
docker-compose up -d
```

Espera a que se descarguen las imágenes (primera vez puede tardar).

### 3. Instalar Laravel

**Primera vez (proyecto nuevo):**
```powershell
docker-compose run --rm app composer create-project laravel/laravel .
```

**Si ya existe composer.json:**
```powershell
docker-compose run --rm app composer install
```

### 4. Generar Clave de Aplicación
```powershell
docker-compose exec app php artisan key:generate
```

### 5. Ejecutar Migraciones
```powershell
# Espera 10-15 segundos para que MySQL esté listo
timeout /t 15

# Ejecuta las migraciones
docker-compose exec app php artisan migrate
```

### 6. Configurar Permisos
```powershell
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

---

## ✅ Verificación de la Instalación

### 1. Verificar Contenedores Activos
```powershell
docker-compose ps
```

Deberías ver 4 contenedores corriendo:
- `laravel_app` (PHP)
- `laravel_nginx` (Servidor web)
- `laravel_db` (MySQL)
- `laravel_phpmyadmin` (PHPMyAdmin)

### 2. Verificar la API

**Probar endpoint raíz:**
```powershell
curl http://localhost:8000/api/v1/orders
```

Debería retornar:
```json
{
  "data": []
}
```

### 3. Crear un Pedido de Prueba

**En PowerShell:**
```powershell
$body = @{
    customer_name = "Juan Pérez"
    total_amount = 150.50
} | ConvertTo-Json

Invoke-RestMethod -Method Post -Uri "http://localhost:8000/api/v1/orders" -Body $body -ContentType "application/json"
```

**Con curl:**
```bash
curl -X POST http://localhost:8000/api/v1/orders ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"customer_name\":\"Juan Pérez\",\"total_amount\":150.50}"
```

Deberías recibir una respuesta con el pedido creado.

---

## 🧪 Ejecutar Tests

Para verificar que todo funciona correctamente:

```powershell
docker-compose exec app php artisan test
```

Deberías ver todos los tests en verde (PASSED).

---

## 📊 Acceder a la Base de Datos

### Opción 1: PHPMyAdmin (Interfaz Web)

1. Abrir: http://localhost:8080
2. Usuario: `laravel`
3. Contraseña: `laravel_password`
4. Servidor: `db`

### Opción 2: Cliente MySQL

Si tienes MySQL Workbench u otro cliente:

- Host: `localhost`
- Puerto: `3306`
- Usuario: `laravel`
- Contraseña: `laravel_password`
- Base de datos: `laravel_orders`

---

## 🔍 Comandos Útiles

### Ver Logs
```powershell
# Logs de la aplicación
docker-compose logs -f app

# Logs de todos los servicios
docker-compose logs -f
```

### Acceder al Contenedor
```powershell
docker-compose exec app bash
```

### Reiniciar Servicios
```powershell
# Reiniciar todos
docker-compose restart

# Reiniciar solo app
docker-compose restart app
```

### Detener el Proyecto
```powershell
docker-compose down
```

### Limpiar Todo (Incluye BD)
```powershell
docker-compose down -v
```

### Reconstruir Contenedores
```powershell
docker-compose up -d --build
```

---

## 🐛 Solución de Problemas

### Error: "Puerto 8000 ya está en uso"

**Solución**: Cambiar el puerto en `docker-compose.yml`

```yaml
nginx:
  ports:
    - "8001:80"  # Cambiar 8000 por 8001
```

Luego acceder a: http://localhost:8001

### Error: "Base de datos no conecta"

**Solución**:
```powershell
# Reiniciar contenedores
docker-compose restart

# Verificar que MySQL esté corriendo
docker-compose logs db
```

### Error: "Permission denied" en storage

**Solución**:
```powershell
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### Error al ejecutar migraciones

**Solución**:
```powershell
# Esperar más tiempo para que MySQL inicie
timeout /t 20

# Intentar nuevamente
docker-compose exec app php artisan migrate
```

### Los contenedores no inician

**Solución**:
```powershell
# Ver logs de errores
docker-compose logs

# Reconstruir desde cero
docker-compose down -v
docker-compose up -d --build
```

---

## 📚 Próximos Pasos

Una vez instalado correctamente:

1. **Leer la documentación completa**: `README.md`
2. **Ver guía rápida**: `QUICKSTART.md`
3. **Importar colección Postman**: `postman_collection.json`
4. **Explorar los tests**: `tests/Feature/`
5. **Revisar el código**: `app/`

---

## 🆘 ¿Necesitas Ayuda?

Si encuentras problemas:

1. Revisa los logs: `docker-compose logs`
2. Verifica que Docker Desktop esté corriendo
3. Asegúrate de tener puertos 8000, 8080 y 3306 disponibles
4. Intenta reiniciar Docker Desktop

---

## ✨ ¡Listo!

Si llegaste hasta aquí y todo funciona, ¡felicitaciones! 🎉

Tu proyecto Laravel está corriendo y listo para ser usado.

**Acceso rápido:**
- API: http://localhost:8000/api/v1/orders
- PHPMyAdmin: http://localhost:8080
- Documentación: README.md
