@extends('layouts.app')

@section('title', 'Dashboard enseignant')

@section('content')
<h1 class="text-3xl font-bold mb-6">Dashboard enseignant</h1>

<div id="statsCards" class="grid md:grid-cols-3 gap-4 mb-6"></div>
<div id="coursesList" class="grid md:grid-cols-2 gap-4"></div>
@endsection

@section('scripts')
<script>
    async function loadDashboard() {
        let response = await fetch('/api/teacher/stats', {
            method: 'GET',
            headers: authHeaders()
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.error || data.erreur || 'Erreur dashboard', 'error');
            return;
        }

        let totalCourses = data.length;
        let totalStudents = 0;
        let totalGroups = data.length;

        data.forEach(function (course) {
            totalStudents += Number(course.total_students || 0);
        });

        document.getElementById('statsCards').innerHTML = `
            <div class="bg-white p-4 rounded shadow">
                <p class="text-slate-500">Cours</p>
                <h2 class="text-3xl font-bold">${totalCourses}</h2>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-slate-500">Étudiants inscrits</p>
                <h2 class="text-3xl font-bold">${totalStudents}</h2>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <p class="text-slate-500">Cours / Groupes</p>
                <h2 class="text-3xl font-bold">${totalGroups}</h2>
            </div>
        `;

        let box = document.getElementById('coursesList');
        box.innerHTML = '';

        data.forEach(function (course) {
            box.innerHTML += `
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-xl font-bold mb-2">${course.title}</h3>
                    <p class="mb-2">${course.description}</p>
                    <p class="mb-2">Prix : ${course.price} DH</p>
                    <p class="mb-1">Total étudiants : ${course.total_students}</p>
                    <p class="mb-1">Actifs : ${course.active_students}</p>
                    <p class="mb-3">Annulés : ${course.cancelled_students}</p>

                    <div class="flex gap-2 flex-wrap">
                        <a href="/teacher/courses/${course.id}/edit" class="bg-yellow-500 text-white px-3 py-2 rounded">Modifier</a>
                        <a href="/teacher/courses/${course.id}/students" class="bg-blue-600 text-white px-3 py-2 rounded">Étudiants</a>
                        <a href="/teacher/courses/${course.id}/groups" class="bg-purple-600 text-white px-3 py-2 rounded">Groupes</a>
                    </div>
                </div>
            `;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        requireRole('teacher').then(function (ok) {
            if (ok) loadDashboard();
        });
    });
</script>
@endsection
