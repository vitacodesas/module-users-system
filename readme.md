# Vitacode Module Users System

**Un paquete modular para Laravel que proporciona APIs listas para autenticación (login, logout) y gestión de roles y permisos.**

🔹 **Compatibilidad:**  
- Laravel 10.x  
- Laravel Sanctum 3.x y 4.x  
- Spatie Laravel Permission 6.x

---

## Instalación

Requiere PHP >=8.1 y Laravel >=10.

```bash
composer require vitacode/module-users-system
```

**Importante:** Tu proyecto debe tener instalado `laravel/sanctum` y `spatie/laravel-permission`.

---

## Configuración

Después de instalar el paquete, publica la configuración:

```bash
php artisan vendor:publish --provider="Vitacode\ModuleUsersSystem\ModuleUsersSystemServiceProvider" --tag="config"
```

Esto generará un archivo de configuración en:

```
config/users_system.php
```

Configuraciones disponibles:

| Clave | Descripción | Valor por defecto |
|:------|:------------|:------------------|
| `route_prefix` | Prefijo para las rutas de autenticación | `api/auth` |
| `middleware` | Middleware aplicado a las rutas | `['api']` |
| `user_model` | Modelo que usará el login | `App\Models\User` |
| `user_fields` | Campos que se retornan al hacer login | `['id', 'name', 'email']` |
| `routes.login` | Habilitar/Deshabilitar ruta de login | `true` |
| `routes.logout` | Habilitar/Deshabilitar ruta de logout | `true` |

---

## Rutas disponibles

**Prefijo:** `/api/auth`

| Método | Ruta | Descripción |
|:-------|:-----|:------------|
| `POST` | `/login` | Iniciar sesión |
| `POST` | `/logout` | Cerrar sesión (requiere token Sanctum) |

**Prefijo:** `/api/roles`

| Método | Ruta | Descripción |
|:-------|:-----|:------------|
| `POST` | `/` | Crear un nuevo rol |
| `GET` | `/` | Listar todos los roles |

**Prefijo:** `/api/permissions`

| Método | Ruta | Descripción |
|:-------|:-----|:------------|
| `POST` | `/` | Crear un nuevo permiso |
| `GET` | `/` | Listar todos los permisos |

**Prefijo:** `/api/assignments`

| Método | Ruta | Descripción |
|:-------|:-----|:------------|
| `POST` | `/role-to-permission` | Asignar un permiso a un rol |
| `POST` | `/role-to-user` | Asignar un rol a un usuario |

---

## Ejemplo de login

```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Respuesta exitosa:**

```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  }
}
```

---

## Cómo funciona internamente

- Usa el modelo configurado (`user_model`) para autenticación.
- Retorna solo los campos necesarios (`user_fields`) para optimizar la consulta.
- Genera el token de sesión usando Laravel Sanctum.
- Administra roles y permisos usando Spatie Laravel Permission.

---

## Roadmap

- [x] Autenticación básica (Login/Logout)
- [x] Gestión de roles y permisos
- [ ] Personalización avanzada de eventos de login/logout
- [ ] Soporte multi-guard
- [ ] Publicación de controladores para sobreescritura

---

## Contribuciones

¡Las contribuciones son bienvenidas!  
Haz un fork, crea una rama con tus cambios y envía un pull request.

---

## Licencia

MIT © Vitacode

---

# 🚀 Instalación rápida para desarrollo local

1. Clona el paquete localmente.
2. En tu `composer.json` del proyecto principal agrega el repositorio:

```json
"repositories": [
    {
      "type": "path",
      "url": "../ruta-al-paquete/vitacode-module-users-system"
    }
]
```

3. Requiere el paquete normalmente:

```bash
composer require vitacode/module-users-system:dev-main
```




TODO:: mejorar los require del paquete