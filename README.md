# HB · Hi Branding

**Quiet Luxury Digital Platform** para descubrir espacios de trabajo, consultar membresías, solicitar asesorías y conectar con una comunidad de fundadores y profesionales.

Hi Branding reúne en una sola experiencia digital el ecosistema de espacios, eventos y comunidad de HB. La interfaz combina una identidad editorial cálida con componentes interactivos y contenido respaldado por Laravel.

## Estado del producto

El repositorio contiene una experiencia funcional de presentación y captura de solicitudes. Las membresías, perfiles y eventos se leen desde la base de datos cuando hay registros y ofrecen datos de muestra como fallback para instalaciones nuevas. Las solicitudes de asesoría se validan y guardan en la base de datos.

Algunas acciones son demostrativas: el RSVP de eventos abre un correo, no crea una inscripción persistida; tampoco hay autenticación ni panel administrativo. La “Matriz Técnica de Especificaciones” no está implementada todavía en la vista de membresías.

## Stack tecnológico

- PHP 8.3+ y Laravel 13.
- Blade para layouts y vistas de servidor.
- Tailwind CSS 4 mediante `@tailwindcss/vite`, además de CSS propio.
- Vite 8 y JavaScript ES modules.
- p5.js 2 para sketches en modo instancia.
- Animate.css 4.1.1, cargado desde CDN.
- SQLite por defecto; MySQL, MariaDB y PostgreSQL están disponibles mediante la configuración de Laravel.
- PHPUnit 12 para pruebas y Pint para formato PHP.

## Arquitectura

```text
app/
  Http/Controllers/
    AppointmentController.php
    CommunityController.php
    EventController.php
    MembershipController.php
  Models/
    Appointment.php
    CommunityMember.php
    Event.php
    Membership.php
bootstrap/app.php             Middleware, routing y proxies de Codespaces
database/
  migrations/                 Esquemas de citas, miembros, eventos y membresías
  seeders/DatabaseSeeder.php   Contenido inicial de demostración
resources/
  css/app.css                 Tailwind 4, tokens y estilos Quiet Luxury
  js/app.js                   Navegación, filtros, loader y scroll reveals
  js/p5-*.js                  Hero, badge y red de nodos
  views/
    layouts/app.blade.php     Header, footer, Animate.css y Vite
    home.blade.php            Portada y espacios
    memberships/index.blade.php
    location/index.blade.php
    appointments/index.blade.php
    community/index.blade.php
    events/index.blade.php
routes/web.php                Rutas públicas y acciones de asesoría
tests/Feature/                 Pruebas de rutas y comportamiento
```

`resources/views/welcome.blade.php` es la vista de bienvenida original de Laravel; la ruta `/` utiliza `home.blade.php`.

## Vistas y rutas

| Método y ruta | Nombre | Propósito |
| --- | --- | --- |
| `GET /` | `home` | Hero scrollytelling, búsqueda/filtros y espacios destacados. |
| `GET /memberships` | `memberships.index` | Tarjetas de planes y contacto para consultar una membresía. |
| `GET /location` | `location.index` | Sede, horarios, ubicación y servicios. |
| `GET /appointments` | `appointments.index` | Próximas citas, historial, asesor asignado y formulario. |
| `POST /appointments` | `appointments.store` | Valida y persiste una solicitud de asesoría. |
| `GET /community` | `community.index` | Métricas, filtros y perfiles del club. |
| `GET /events` | `events.index` | Evento destacado y agenda de próximos encuentros. |

Todas las rutas actuales son públicas. No hay inicio de sesión ni autorización por usuario. Las métricas de comunidad y los datos de asesor asignado son contenido de presentación, no agregados calculados desde una cuenta autenticada.

## Sistema de diseño

### Paleta

| Uso | Color |
| --- | --- |
| Fondo principal | `#FDFBF7` |
| Fondo crema secundario | `#F7F3EE` |
| Tarjetas | `#FFFFFF` / `#FCFAF7` |
| Terracota | `#A8492A` |
| Hover terracota | `#8C3B20` |
| Texto principal | `#211E1D` |
| Loader oscuro | `#1A1817` |

### Tipografía y efectos

- **Cormorant Garamond** para titulares y acentos en cursiva.
- **Plus Jakarta Sans** para interfaz y cuerpo.
- Sombras de baja intensidad, bordes cálidos y elevación discreta al hover.
- Animate.css aplica `swing` a titulares seleccionados y `flipInY` a perfiles. Un `IntersectionObserver` activa reveals de títulos y tarjetas al entrar en pantalla; `prefers-reduced-motion` desactiva estas animaciones.
- El loader HB aparece al entrar por primera vez en la sesión. El hero combina imagen, copy y buscador; el desplazamiento aplica zoom sutil, desplazamiento del texto y elevación del buscador.
- p5.js contiene los sketches del hero, badge y red de nodos. Cada sketch se monta solo donde existe su contenedor.

Tailwind 4 escanea las vistas Blade mediante `@source "../views/**/*.blade.php"` en `resources/css/app.css`. El layout carga la entrada con `@vite(['resources/css/app.css', 'resources/js/app.js'])`. En Codespaces, `TrustProxies` usa los encabezados reenviados para generar URLs HTTPS de assets bajo el host público actual.

## Instalación local

### Requisitos

- PHP 8.3 o superior y Composer 2.
- Node.js compatible con Vite 8 (20.19+ o 22.12+) y npm.
- SQLite para la configuración inicial, o un servidor MySQL/MariaDB/PostgreSQL.

### Preparación

```bash
git clone <url-del-repositorio>
cd Hi_Brandign-web
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Para usar SQLite, configura `DB_CONNECTION=sqlite` en `.env` y crea el archivo de base de datos si aún no existe:

```bash
touch database/database.sqlite
php artisan migrate --seed
```

Para otro motor, configura `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD` en `.env` antes de migrar.

### Ejecutar

En una terminal, inicia Laravel:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

En otra terminal, elige el modo de assets:

```bash
# Desarrollo con recarga de Vite
npm run dev

# O genera assets de producción/locales
npm run build
```

Abre `http://localhost:8000`. En Codespaces, utiliza la URL HTTPS que muestra el reenvío del puerto 8000. Si el HTML vuelve a enlazar assets a `localhost`, comprueba que no exista `public/hot` de un Vite dev server detenido y que el proxy reenvíe `X-Forwarded-Host` y `X-Forwarded-Proto`.

### Datos iniciales

`php artisan migrate --seed` crea las tablas y carga membresías, perfiles y eventos de muestra. La ejecución de `DatabaseSeeder` es idempotente para estos registros por slug/título; también crea un usuario de ejemplo `test@example.com`.

## Pruebas y mantenimiento

```bash
# Suite PHPUnit completa
php artisan test

# Formato PHP
vendor/bin/pint --dirty --format agent

# Limpiar vistas y configuración compiladas
php artisan view:clear
php artisan config:clear
php artisan route:clear

# Reconstruir frontend
npm run build
```

Las pruebas Feature cubren el renderizado de rutas, URLs de assets detrás del proxy de Codespaces, validación y persistencia de citas, y lectura de miembros/eventos desde la base de datos.