Prueba Técnica — Sistema de Biblioteca (Laravel 11 + React TS)

Este monorepo contiene:
- backend/ — API en Laravel 11
- frontend/ — UI en React + TypeScript (Vite)

Requisitos
- PHP >= 8.2 (bcmath, fileinfo, mbstring, openssl, pdo_mysql, curl, xml, zip)
- Composer 2.x
- Node.js 18+ (recomendado 20+), npm o pnpm
- MariaDB/MySQL activo

Primeros pasos
1) Backend
   - cd backend
   - copy .env.example .env
   - php artisan key:generate
   - Configura DB en .env y ejecuta: php artisan migrate
   - php artisan serve

2) Frontend
   - cd frontend
   - npm install
   - crea .env con VITE_API_URL=http://127.0.0.1:8000
   - npm run dev

Notas
- Ajusta DB_PORT si usas un puerto distinto (ej. 3307).
- Usa http://localhost o http://127.0.0.1 según prefieras.

