# Changelog

Todas las modificaciones importantes a este proyecto se documentarán en este archivo.

Formato basado en [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [0.1.0] - 2025-04-26
### Agregado
- Primera versión estable del paquete.
- Implementación de login y logout usando Laravel Sanctum.
- Soporte de modelo de usuario personalizado vía configuración.
- Optimización de respuesta de login (campos configurables).
- Integración con Spatie Laravel Permission.
- API para:
  - Crear roles.
  - Listar roles.
  - Crear permisos.
  - Listar permisos.
  - Asignar permisos a roles.
  - Asignar roles a usuarios.
- Middleware configurable para las rutas.
- Configuración flexible publicada.

### Mejorado
- Optimización de consultas al buscar usuarios en el login.

### Compatibilidad
- Laravel 10.x
- Sanctum 3.x y 4.x
- Spatie Laravel Permission 6.x

---

## [Pendiente]
### Por hacer
- Eventos personalizables para login/logout.
- Soporte para múltiples guards.
- Opciones para publicar y sobreescribir controladores.
- Tests unitarios y de integración.

---

# 📅 Mantenido por [Vitacode](https://github.com/vitacodesas)

---

Este archivo se mantendrá actualizado conforme avance el desarrollo del paquete.