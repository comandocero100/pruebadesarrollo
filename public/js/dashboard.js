let token = '';
let user = null;

function authHeaders(){ return { 'Accept':'application/json', 'Content-Type':'application/json', 'Authorization':`Bearer ${token}` }; }
function setHidden(el, hidden){ if(hidden) el.setAttribute('hidden',''); else el.removeAttribute('hidden'); }

async function api(path, options={}){
  const res = await fetch(path, options);
  const txt = await res.text();
  let data; try { data = txt ? JSON.parse(txt) : {}; } catch{ data = { raw: txt }; }
  if(!res.ok){ throw new Error(data?.message || 'Error API'); }
  return data;
}

// Cursos (Admin)
async function loadCourses(){
  const out = document.getElementById('coursesOut');
  out.textContent = 'Cargando...';
  const data = await api('/api/courses', { headers: authHeaders() });
  const rows = (data.data || data).map(c => `<tr><td>${c.id}</td><td>${c.name}</td><td>${c.intensity}</td>
  <td><button class="btn" data-edit-course="${c.id}">Editar</button> <button class="btn btn-danger" data-del-course="${c.id}">Borrar</button></td></tr>`).join('');
  out.innerHTML = `<table><thead><tr><th>ID</th><th>Nombre</th><th>Intensidad</th><th></th></tr></thead><tbody>${rows}</tbody></table>`;

  out.querySelectorAll('[data-edit-course]').forEach(b=>b.addEventListener('click',()=>updateCourse(parseInt(b.dataset.editCourse,10))));
  out.querySelectorAll('[data-del-course]').forEach(b=>b.addEventListener('click',()=>deleteCourse(parseInt(b.dataset.delCourse,10))));
}
async function createCourse(){
  const name = document.getElementById('c_name').value.trim();
  const intensity = parseInt(document.getElementById('c_int').value||'0',10);
  await api('/api/courses',{ method:'POST', headers: authHeaders(), body: JSON.stringify({ name, intensity }) });
  ok('Curso creado');
  document.getElementById('c_name').value=''; document.getElementById('c_int').value='';
  loadCourses();
}
async function updateCourse(id){
  const name = prompt('Nuevo nombre (deja vacío para no cambiar)');
  const intensity = prompt('Nueva intensidad (entero, deja vacío para no cambiar)');
  const body = {};
  if(name) body.name = name; if(intensity) body.intensity = parseInt(intensity,10);
  if(Object.keys(body).length===0) return;
  await api(`/api/courses/${id}`, { method:'PUT', headers: authHeaders(), body: JSON.stringify(body) });
  loadCourses();
}
async function deleteCourse(id){
  if(!confirm('¿Borrar curso?')) return;
  await api(`/api/courses/${id}`, { method:'DELETE', headers: authHeaders() });
  loadCourses();
}

// Alumnos (Admin)
async function loadStudents(){
  const out = document.getElementById('studentsOut');
  const name = document.getElementById('s_filter_name').value.trim();
  const course = document.getElementById('s_filter_course').value.trim();
  const qs = new URLSearchParams();
  if(name) qs.set('name',name); if(course) qs.set('course_name',course);
  const data = await api('/api/students' + (qs.toString()?('?' + qs.toString()):''), { headers: authHeaders() });
  const rows = (data.data || data).map(s => `<tr><td>${s.id}</td><td>${s.name}</td><td>${s.email}</td><td>${s.phone||''}</td>
    <td>${(s.courses||[]).map(c=>c.name).join(', ')}</td>
    <td><button class="btn" data-edit-stu='${JSON.stringify({id:s.id,name:s.name,email:s.email,phone:s.phone||""}).replace(/'/g,"&apos;") }'>Editar</button> <button class="btn btn-danger" data-del-stu="${s.id}">Borrar</button></td></tr>`).join('');
  out.innerHTML = `<table><thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Cursos</th><th></th></tr></thead><tbody>${rows}</tbody></table>`;

  out.querySelectorAll('[data-edit-stu]').forEach(b=>b.addEventListener('click',()=>{
    const d = JSON.parse(b.getAttribute('data-edit-stu'));
    editStudent(d.id, d.name, d.email, d.phone);
  }));
  out.querySelectorAll('[data-del-stu]').forEach(b=>b.addEventListener('click',()=>deleteStudent(parseInt(b.dataset.delStu,10))));
}
async function createStudent(){
  const name = document.getElementById('s_name').value.trim();
  const email = document.getElementById('s_email').value.trim();
  const phone = document.getElementById('s_phone').value.trim();
  const password = document.getElementById('s_password').value;
  await api('/api/students',{ method:'POST', headers: authHeaders(), body: JSON.stringify({ name,email,phone,password }) });
  ok('Alumno creado');
  document.getElementById('s_name').value=''; document.getElementById('s_email').value=''; document.getElementById('s_phone').value=''; document.getElementById('s_password').value='';
  loadStudents();
}
async function editStudent(id, name, email, phone){
  const n = prompt('Nuevo nombre', name)||undefined;
  const e = prompt('Nuevo email', email)||undefined;
  const p = prompt('Nuevo teléfono', phone)||undefined;
  const pass = prompt('Nueva contraseña (opcional)')||undefined;
  const body = {}; if(n) body.name=n; if(e) body.email=e; if(p) body.phone=p; if(pass) body.password=pass;
  if(Object.keys(body).length===0) return;
  await api(`/api/students/${id}`, { method:'PUT', headers: authHeaders(), body: JSON.stringify(body) });
  loadStudents();
}
async function deleteStudent(id){
  if(!confirm('¿Borrar alumno?')) return;
  await api(`/api/students/${id}`, { method:'DELETE', headers: authHeaders() });
  loadStudents();
}

// Asignación de cursos (Admin)
async function assignCourse(){
  const uid = parseInt(document.getElementById('a_user').value,10);
  const cid = parseInt(document.getElementById('a_course').value,10);
  await api(`/api/users/${uid}/courses`, { method:'POST', headers: authHeaders(), body: JSON.stringify({ course_id: cid }) });
  ok('Curso asignado');
}
async function unassignCourse(){
  const uid = parseInt(document.getElementById('a_user').value,10);
  const cid = parseInt(document.getElementById('a_course').value,10);
  await api(`/api/users/${uid}/courses/${cid}`, { method:'DELETE', headers: authHeaders() });
  ok('Curso removido');
}

// Alumno: mis cursos
async function loadMyCourses(){
  const out = document.getElementById('myCoursesOut');
  const data = await api('/api/me/courses', { headers: authHeaders() });
  const rows = data.map(c => `<tr><td>${c.id}</td><td>${c.name}</td><td>${c.intensity}</td></tr>`).join('');
  out.innerHTML = `<table><thead><tr><th>ID</th><th>Nombre</th><th>Intensidad</th></tr></thead><tbody>${rows}</tbody></table>`;
}

async function doLogout(){
  try { await api('/api/logout', { method:'POST', headers: authHeaders() }); } catch {}
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  location.href = '/login';
}

function ok(msg){ const el = document.getElementById('ok'); el.textContent = msg; setHidden(el, false); setTimeout(()=>setHidden(el,true),1500); }
function error(msg){ const el = document.getElementById('err'); el.textContent = msg; setHidden(el, false); }

window.addEventListener('DOMContentLoaded', async () => {
  token = localStorage.getItem('token') || '';
  const raw = localStorage.getItem('user');
  if(!token || !raw){ location.replace('/login'); return; }
  user = JSON.parse(raw);
  document.getElementById('who').textContent = `${user.name} (${user.role})`;

  document.getElementById('btnLogout').addEventListener('click', doLogout);
  document.getElementById('btnCreateCourse').addEventListener('click', createCourse);
  document.getElementById('btnReloadCourses').addEventListener('click', loadCourses);
  document.getElementById('btnCreateStudent').addEventListener('click', createStudent);
  document.getElementById('btnFilterStudents').addEventListener('click', loadStudents);
  document.getElementById('btnAssign').addEventListener('click', assignCourse);
  document.getElementById('btnUnassign').addEventListener('click', unassignCourse);

  // Mostrar secciones por rol
  if(user.role === 'admin'){
    setHidden(document.getElementById('admin'), false);
    await loadCourses();
    await loadStudents();
  } else {
    setHidden(document.getElementById('student'), false);
    await loadMyCourses();
  }
});

