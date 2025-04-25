# Vitacode Module Users System

Este paquete proporciona un sistema completo de autenticación y autorización para Laravel, incluyendo:

- Registro y login de usuarios utilizando Laravel Sanctum.
- Logout con invalidación de tokens.
- Gestión de roles y permisos.
- Asociación de permisos a roles.
- Asociación de roles a usuarios.
- Configuración personalizable desde el archivo `config/users_system.php`.

---

## 🤩 Requisitos

- PHP ^8.1
- Laravel ^10
- Laravel Sanctum ^3.0

---

## 🚀 Instalación

```bash
composer require vitacode/module-users-system
```

Luego, publica la configuración (opcional):

```bash
php artisan vendor:publish --tag=users-system-config
```

Y si deseas publicar migraciones (roles/permissions):

```bash
php artisan vendor:publish --tag=users-system-migrations
```

---

## ⚙️ Configuración

El archivo de configuración `config/users_system.php` te permite personalizar:

- El modelo de usuario.
- Los atributos visibles tras el login.
- Activar/desactivar rutas de autenticación.

```php
return [
    'user_model' => \App\Models\User::class,
    'user_visible_attributes' => ['id', 'name', 'email'],
    'auth_routes' => true,
];
```

---

## 🧑‍💻 Endpoints

| Método | Ruta                          | Descripción                        |
|--------|-------------------------------|------------------------------------|
| POST   | /api/register                 | Registrar nuevo usuario            |
| POST   | /api/login                    | Iniciar sesión                     |
| POST   | /api/logout                   | Cerrar sesión actual               |
| POST   | /api/roles                    | Crear rol                          |
| POST   | /api/permissions              | Crear permiso                      |
| POST   | /api/roles/{id}/permissions   | Asignar permisos a un rol          |
| POST   | /api/users/{id}/roles        | Asignar roles a un usuario         |

> 📌 Todos los endpoints están protegidos por Sanctum y deben ser usados con autenticación de tipo Bearer Token, excepto `register` y `login`.

---

## 🥪 Tests

Próximamente: se incluirán pruebas automatizadas con PHPUnit para endpoints principales.

---

## 💠 Roadmap

- Middleware para proteger rutas por permiso.
- Soporte para múltiples tipos de usuario.

---

## 📄 Licencia

MIT © Vitacode

