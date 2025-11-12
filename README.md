Plataforma Cursos

Aplicación en Laravel con autenticación por token y dos perfiles (admin y alumno) para gestionar alumnos, cursos y asignaciones. Todo el backend responde en JSON (API REST) y se incluyen dos vistas Blade muy simples para usar la API desde el navegador.

Qué se implementó
- Autenticación por token tipo Bearer y autorización por rol.
- Perfiles: admin y student.
- CRUD de Alumnos y Cursos.
- Asignación y eliminación de cursos a usuarios.
- Listados con filtros (por nombre/email de alumno y nombre/ID de curso).
- Vistas mínimas para login y panel con estilos externos.


Levantar servidor
php artisan serve

Uso desde el navegador
- Login: http://127.0.0.1:8000/login
  - Envía credenciales a /api/login, guarda el token en localStorage y redirige a /app.
- Panel: http://127.0.0.1:8000/app
  - Si el usuario es admin: CRUD de cursos y alumnos.
  - Si es student: muestra “Mis cursos”.

Uso por API (Postman/curl)
- Login (POST /api/login)
  - Headers: Accept: application/json, Content-Type: application/json
  - Body JSON: { "email":"admin@example.com", "password":"admin123" }
  - Respuesta incluye: { token, user, permissions }
- Usar token en siguientes llamadas: Authorization: Bearer Token.

