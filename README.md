# HB · Hi Branding (`hibranding.net`)

**Quiet Luxury Digital Platform** para descubrir espacios de trabajo, consultar membresías, reportar pagos, solicitar asesorías y conectar con una comunidad de fundadores y profesionales.

Hi Branding reúne en una sola experiencia digital el ecosistema de espacios, eventos, membresías y panel administrativo directivo de HB.

## Stack tecnológico

- PHP 8.3+ y Laravel 13.
- Blade para layouts y vistas de servidor (público + panel directivo `/admin`).
- Tailwind CSS 4 mediante `@tailwindcss/vite`, además de CSS editorial propio.
- Vite 8 y JavaScript ES modules.
- p5.js 2 para sketches en modo instancia (`p5-hero`, `p5-badge`, `p5-network`).
- Animate.css 4.1.1.
- MySQL / MariaDB como motor de base de datos principal.
- PHPUnit 12 para pruebas automatizadas y Laravel Pint para estilo de código.

## Configuración de Producción (cPanel · `hibranding.net`)

### 1. Base de datos MySQL en cPanel

1. En **cPanel → MySQL® Databases**, asegúrate de tener creada la base de datos y el usuario con todos los privilegios (`ALL PRIVILEGES`):
   - **Base de datos:** `rosanny2_hibranding`
   - **Usuario:** `rosanny2_hibranding`
2. En **cPanel → phpMyAdmin**, selecciona la base de datos `rosanny2_hibranding` e importa el archivo:
   - `database/hi_branding_dump.sql`
   *(Incluye estructura completa de tablas InnoDB `utf8mb4`, usuario administrador, planes de membresía, métodos de pago y datos iniciales).*

### 2. Archivos y Document Root en cPanel

El proyecto incluye un `.htaccess` en la raíz y otro en `public/.htaccess` para adaptarse a cualquier configuración de cPanel:

- **Opción A (Subir todo a `public_html`):** Puedes subir todo el contenido del proyecto directamente a `public_html`. El archivo `.htaccess` raíz redirige de forma transparente todo el tráfico hacia `public/` y bloquea el acceso directo a `.env`, `storage/`, `database/`, `vendor/` y archivos internos.
- **Opción B (Document Root apuntando a `public`):** Si colocas el proyecto en `/home/rosanny2/hibranding` y apuntas `public_html` (o el dominio `hibranding.net`) a `/home/rosanny2/hibranding/public`, funcionará directamente con `public/.htaccess`.

### 3. Variables de entorno (`.env`)

Verifica que el archivo `.env` en la raíz del proyecto tenga las credenciales de producción:

```env
APP_NAME="Hi Branding"
APP_ENV=production
APP_KEY=base64:/uzcV0YoHLXNijh/zpBdmwjr+ARJJop0axbqlf3IeWY=
APP_DEBUG=false
APP_URL=https://hibranding.net

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rosanny2_hibranding
DB_USERNAME=rosanny2_hibranding
DB_PASSWORD="..Hibranding..00"
```

### 4. Permisos en cPanel

Asegúrate de que las carpetas `storage/` y `bootstrap/cache/` tengan permisos de escritura (`755` o `775`).
El sistema incluye además una ruta de respaldo automática (`/storage/{path}`) por si el enlace simbólico `public/storage` no está disponible en el servidor compartido.

### 5. Acceso al Panel Directivo (`/admin`)

- **URL:** `https://hibranding.net/admin/login`
- **Usuario inicial:** `admin@hibranding.com`
- **Contraseña inicial:** `admin123` *(se recomienda actualizarla tras el primer ingreso en **Configuración → Actualizar contraseña**)*.

## Comandos útiles de mantenimiento

```bash
# Compilar assets de producción (ya compilados en public/build)
npm run build

# Ejecutar migraciones y seeders en MySQL
php artisan migrate --seed --force

# Limpiar cachés de Laravel
php artisan optimize:clear

# Ejecutar suite de pruebas PHPUnit
php artisan test
```
