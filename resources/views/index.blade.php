@extends('layouts.app')

@section('title', 'Cours')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Liste des cours</h1>
    <input type="text" id="searchInput" placeholder="Rechercher..." class="border rounded px-3 py-2 bg-white">
</div>

<div id="coursesContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
@endsection

@section('scripts')
<script>
    let allCourses = [];

    function renderCourses(courses) {
        let box = document.getElementById('coursesContainer');
        box.innerHTML = '';

        if (courses.length === 0) {
            box.innerHTML = `<div class="bg-white p-4 rounded shadow">Aucun cours trouvé</div>`;
            return;
        }

        courses.forEach(function (course) {
            box.innerHTML += `
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-2">${course.title}</h2>
                    <p class="text-slate-600 mb-2">${course.description}</p>
                    <p class="font-semibold mb-3">${course.price} DH</p>

                    <div class="flex gap-2 flex-wrap">
                        <a href="/courses/${course.id}" class="bg-blue-600 text-white px-3 py-2 rounded">Voir détails</a>
                        <button onclick="addToWishlist(${course.id})" class="bg-pink-600 text-white px-3 py-2 rounded">Favori</button>
                    </div>
                </div>
            `;
        });
    }

    async function loadCourses() {
        let response = await fetch('/api/courses', {
            method: 'GET',
            headers: authHeaders()
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.erreur || 'Erreur chargement cours', 'error');
            return;
        }

        allCourses = data;
        renderCourses(allCourses);
    }

    async function addToWishlist(courseId) {
        let response = await fetch('/api/wishlist', {
            method: 'POST',
            headers: jsonHeaders(),
            body: JSON.stringify({
                course_id: courseId
            })
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.error || data.erreur || 'Erreur favoris', 'error');
            return;
        }

        showMessage('Cours ajouté aux favoris');
    }

    document.getElementById('searchInput').addEventListener('input', function () {
        let value = this.value.toLowerCase();

        let filtered = allCourses.filter(function (course) {
            return course.title.toLowerCase().includes(value)
                || course.description.toLowerCase().includes(value);
        });

        renderCourses(filtered);
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (!requireAuth()) return;
        loadCourses();
    });
</script>
@endsection