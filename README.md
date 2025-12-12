# 📘 Proyecto Web CRUD en PHP

**Sistema modular de gestión para Clientes, Productos, Proveedores,
Compras y Ventas**

Este proyecto es una aplicación web desarrollada en **PHP** con una
arquitectura modular, enfocada en la implementación de operaciones
**CRUD (Create, Read, Update, Delete)** para la administración de
recursos empresariales.

Incluye autenticación, manejo de imágenes, scripts de mantenimiento y
una base sólida para crecer hacia un sistema más completo (MVC, roles,
reportes, dashboards, etc.).

------------------------------------------------------------------------

# 📁 Estructura General del Proyecto

    PROYECTOWEB/
    │
    ├── auth/                  
    │   ├── login.php
    │   ├── logout.php
    │   └── procesar_login.php
    │
    ├── config/
    │   └── config.php
    │
    ├── imagen/
    │   └── ...
    │
    ├── modules/
    │   ├── clientes/
    │   │   ├── createcliente.php
    │   │   ├── deletecliente.php
    │   │   ├── readcliente.php
    │   │   └── updatecliente.php
    │   │
    │   ├── compras/
    │   │   ├── createcompra.php
    │   │   ├── deletecompra.php
    │   │   ├── readcompra.php
    │   │   └── updatecompra.php
    │   │
    │   ├── productos/
    │   │   ├── createproducto.php
    │   │   ├── deleteproducto.php
    │   │   ├── readproducto.php
    │   │   └── updateproducto.php
    │   │
    │   ├── proveedores/
    │   │   ├── createproveedor.php
    │   │   ├── deleteproveedor.php
    │   │   ├── readproveedor.php
    │   │   └── updateproveedor.php
    │   │
    │   └── ventas/
    │       ├── createventas.php
    │       ├── deleteventas.php
    │       ├── readventas.php
    │       └── updateventas.php
    │
    ├── scripts/
    │   ├── create_default_image.php
    │   └── fix_proveedores_imagen.php
    │
    ├── sql/
    │   ├── crudphp1.sql
    │   ├── erd.png
    │   └── crudphp.db
    │
    ├── views/
    │   ├── dashboard.php
    │   ├── footer.php
    │   └── header.php
    |   
    ├── index.php
    │
    └── README.md

------------------------------------------------------------------------

# 🚀 Funcionalidades del Sistema

## 🔐 Autenticación

-   Inicio y cierre de sesión.
-   Validación segura.
-   Protección de módulos internos.

## 📦 CRUD por Módulos

Incluye módulos completos para: 
- Clientes\
- Compras\
- Productos\
- Proveedores\
- Ventas

------------------------------------------------------------------------

# 🖼️ Manejo de Imágenes

Scripts para generar imágenes por defecto y reparar imágenes faltantes.

------------------------------------------------------------------------

# 🗃️ Base de Datos

Incluye `crudphp1.sql`, `erd.png` y `crudphp.db`.

------------------------------------------------------------------------

# 🔧 Requisitos Técnicos

-   PHP 7.4+
-   MySQL/MariaDB o SQLite
-   Servidor Apache (XAMPP, WAMP, Laragon)

------------------------------------------------------------------------

# 🛠️ Instalación

1.  Clonar repositorio\
2.  Importar SQL\
3.  Configurar `config/config.php`\
4.  Ejecutar en navegador

------------------------------------------------------------------------

🧰 Buenas prácticas aplicadas

✔ Arquitectura modular por entidad
✔ Separación clara de vistas, lógica y scripts
✔ Conexión centralizada
✔ Archivos CRUD individuales para mantenimiento óptimo
✔ Scripts de reparación para evitar errores por imágenes
✔ Código comentado para comprensión rápida

------------------------------------------------------------------------

📌 Posibles mejoras futuras

Estas mejoras pueden implementarse fácilmente:
Migración a arquitectura MVC real
Sistemas de permisos y roles (admin, empleado)
Reportes PDF o Excel
Dashboards con gráficas
Integración con Bootstrap o Tailwind
API REST para conexión con apps móviles

------------------------------------------------------------------------

# 👨‍💻 Autor

Jaime Ramírez Miranda

------------------------------------------------------------------------

📄 Licencia

Este proyecto puede adaptarse y reutilizarse libremente.
