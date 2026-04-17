@extends('layouts.app')

@section('title', 'Mes cours enseignant')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Mes cours</h1>
    <a href="{{ route('teacher.courses.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Créer un cours</a>
</div>

<div id="teacherCoursesBox" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
@endsection

@section('scripts')
<script>
    async function loadTeacherCourses() {
        let response = await fetch('/api/teacher/stats', {
            method: 'GET',
            headers: authHeaders()
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.error || data.erreur || 'Erreur chargement', 'error');
            return;
        }

        let box = document.getElementById('teacherCoursesBox');
        box.innerHTML = '';

        if (data.length === 0) {
            box.innerHTML = `<div class="bg-white p-4 rounded shadow">Aucun cours</div>`;
            return;
        }

        data.forEach(function (course) {
            box.innerHTML += `
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-2">${course.title}</h2>
                    <p class="text-slate-600 mb-2">${course.description}</p>
                    <p class="font-semibold mb-2">${course.price} DH</p>
                    <p class="mb-1">Étudiants : ${course.total_students}</p>
                    <p class="mb-3">Actifs : ${course.active_students}</p>

                    <div class="flex gap-2 flex-wrap">
                        <a href="/teacher/courses/${course.id}/edit" class="bg-yellow-500 text-white px-3 py-2 rounded">Modifier</a>
                        <button onclick="deleteCourse(${course.id})" class="bg-red-600 text-white px-3 py-2 rounded">Supprimer</button>
                        <a href="/teacher/courses/${course.id}/students" class="bg-blue-600 text-white px-3 py-2 rounded">Étudiants</a>
                        <a href="/teacher/courses/${course.id}/groups" class="bg-purple-600 text-white px-3 py-2 rounded">Groupes</a>
                    </div>
                </div>
            `;
        });
    }

    async function deleteCourse(courseId) {
        if (!confirm('Supprimer ce cours ?')) return;

        let response = await fetch('/api/courses/' + courseId, {
            method: 'DELETE',
            headers: authHeaders()
        });

        if (!response.ok) {
            let data = await response.json();
            showMessage(data.message || data.error || data.erreur || 'Erreur suppression', 'error');
            return;
        }

        showMessage('Cours supprimé');
        loadTeacherCourses();
    }

    document.addEventListener('DOMContentLoaded', function () {
        requireRole('teacher').then(function (ok) {
            if (ok) loadTeacherCourses();
        });
    });
</script>
@endsection