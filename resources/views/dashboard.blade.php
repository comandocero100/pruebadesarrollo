<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel</title>
  <link rel="stylesheet" href="{{ asset('css/base.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <script src="{{ asset('js/dashboard.js') }}" defer></script>
</head>
<body>
  <header class="topbar">
    <div class="brand">Panel</div>
    <div class="useractions">
      <span id="who" class="who"></span>
      <button class="btn btn-danger" id="btnLogout">Salir</button>
    </div>
  </header>
  <div class="container">
    <div id="ok" class="alert alert-ok" hidden></div>
    <div id="err" class="alert alert-error" hidden></div>

    <!-- Admin -->
    <section id="admin" hidden>
      <div class="grid">
        <div class="card">
          <h2 class="subtitle">Cursos</h2>
          <div class="row wrap">
            <input id="c_name" class="input" placeholder="Nombre del curso" />
            <input id="c_int" class="input w-120" placeholder="Intensidad" type="number" min="0" />
            <button class="btn btn-primary" id="btnCreateCourse">Crear</button>
            <button class="btn" id="btnReloadCourses">Refrescar</button>
          </div>
          <div id="coursesOut" class="muted">Sin datos</div>
        </div>

        <div class="card">
          <h2 class="subtitle">Alumnos</h2>
          <div class="row wrap">
            <input id="s_name" class="input" placeholder="Nombre" />
            <input id="s_email" class="input" placeholder="Email" />
            <input id="s_phone" class="input" placeholder="Teléfono" />
            <input id="s_password" class="input" placeholder="Password" type="password" />
            <button class="btn btn-primary" id="btnCreateStudent">Crear</button>
          </div>
          <div class="row wrap mt-6">
            <input id="s_filter_name" class="input" placeholder="Filtrar nombre" />
            <input id="s_filter_course" class="input" placeholder="Filtrar curso" />
            <button class="btn" id="btnFilterStudents">Buscar</button>
          </div>
          <div id="studentsOut" class="muted">Sin datos</div>
        </div>

        <div class="card">
          <h2 class="subtitle">Asignación de cursos</h2>
          <div class="row wrap">
            <input id="a_user" class="input w-120" placeholder="ID usuario" type="number" min="1" />
            <input id="a_course" class="input w-120" placeholder="ID curso" type="number" min="1" />
            <button class="btn btn-primary" id="btnAssign">Asignar</button>
            <button class="btn btn-warn" id="btnUnassign">Quitar</button>
          </div>
          <p class="muted">Usa los IDs de las tablas de arriba.</p>
        </div>
      </div>
    </section>

    <!-- Alumno -->
    <section id="student" hidden>
      <div class="card">
        <h2 class="subtitle">Mis cursos</h2>
        <div id="myCoursesOut" class="muted">Sin datos</div>
      </div>
    </section>

  </div>
</body>
</html>

