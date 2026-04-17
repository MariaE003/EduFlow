@extends('layouts.app')

@section('title', 'Mes cours')

@section('content')
<h1 class="text-3xl font-bold mb-6">Mes cours inscrits</h1>
<div id="myCoursesBox" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
@endsection

@section('scripts')
<script>
    async function loadMyCourses() {
        let ids = getMyCourses();

        let response = await fetch('/api/courses', {
            method: 'GET',
            headers: authHeaders()
        });

        let courses = await response.json();

        if (!response.ok) {
            showMessage(courses.message || courses.error || courses.erreur || 'Erreur cours', 'error');
            return;
        }

        let myCourses = courses.filter(function (course) {
            return ids.includes(course.id);
        });

        let box = document.getElementById('myCoursesBox');
        box.innerHTML = '';

        if (myCourses.length === 0) {
            box.innerHTML = `<div class="bg-white p-4 rounded shadow">Aucun cours inscrit</div>`;
            return;
        }

        myCourses.forEach(function (course) {
            box.innerHTML += `
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-2">${course.title}</h2>
                    <p class="text-slate-600 mb-2">${course.description}</p>
                    <p class="font-semibold mb-3">${course.price} DH</p>

                    <div class="flex gap-2 flex-wrap">
                        <a href="/courses/${course.id}" class="bg-blue-600 text-white px-3 py-2 rounded">Voir</a>
                        <button onclick="cancelCourse(${course.id})" class="bg-red-600 text-white px-3 py-2 rounded">Se retirer</button>
                    </div>
                </div>
            `;
        });
    }

    async function cancelCourse(courseId) {
        let response = await fetch('/api/courses/' + courseId + '/cancel', {
            method: 'DELETE',
            headers: authHeaders()
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.error || data.erreur || 'Erreur annulation', 'error');
            return;
        }

        removeMyCourse(courseId);
        showMessage('Inscription annulée');
        loadMyCourses();
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (!requireRole('student')) return;
        loadMyCourses();
    });
</script>
@endsection