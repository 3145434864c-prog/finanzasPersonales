# 💰 Finanzas Personales

Sistema web para la **gestión de finanzas personales y metas de ahorro**, desarrollado con **Laravel** y **Filament**.

Este proyecto forma parte de mi proceso de formación como desarrollador y está abierto a **ideas, sugerencias y buenas prácticas** por parte de la comunidad.

![Finanzas Personales](finanzas%20personales.png)

> Banner visual del proyecto con fines estéticos. No corresponde a una vista real del sistema.

## 🚀 Características principales

- Registro y control de ingresos
- Registro y control de gastos
- Organización por categorías
- Gestión de **metas de ahorro**
- Panel administrativo construido con **Filament**
- Arquitectura preparada para escalar

## 🛠️ Tecnologías utilizadas

- PHP 8.2+
- Laravel 12
- Filament
- MySQL
- Composer
- Tailwind CSS

## 📦 Requisitos previos

- PHP >= 8.2
- Composer
- MySQL o MariaDB
- Servidor local (XAMPP, Laragon, Laravel Sail, etc.)
- Git

## ⚙️ Instalación

Sigue estos pasos para ejecutar el proyecto **en tu entorno local**:

1. Clona el repositorio y entra al proyecto:
```bash
git clone https://github.com/3145434864c-prog/finanzasPersonales.git
cd finanzaspersonales
Instala las dependencias de PHP:

bash
Copiar código
composer install
Configura el entorno y genera la clave de la aplicación:

bash
Copiar código
cp .env.example .env
php artisan key:generate
Crea la base de datos en MySQL llamada finanzaspersonales y edita .env:

env
Copiar código
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=finanzaspersonales
DB_USERNAME=root
DB_PASSWORD=
Ejecuta las migraciones para crear las tablas necesarias:

bash
Copiar código
php artisan migrate
Inicia el servidor de desarrollo:

bash
Copiar código
php artisan serve
Abre en tu navegador:

arduino
Copiar código
http://localhost:8000
✅ El proyecto debería estar corriendo correctamente.

🧪 Estado del proyecto
📌 En desarrollo. Se agradecen sugerencias sobre:

Arquitectura del proyecto

Buenas prácticas en Laravel

Seguridad

UX/UI

Escalabilidad

Todo feedback es bienvenido. Gracias por revisar este proyecto 🙏
