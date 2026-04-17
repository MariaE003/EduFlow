@extends('layouts.app')

@section('title', 'Recommandations')

@section('content')
<h1 class="text-3xl font-bold mb-6">Suggestions pour vous</h1>
<div id="recommendationsBox" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
@endsection

@section('scripts')
<script>
    async function loadRecommendations() {
        let response = await fetch('/api/recommended-courses', {
            method: 'GET',
            headers: authHeaders()
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.error || data.erreur || 'Erreur recommandations', 'error');
            return;
        }

        let box = document.getElementById('recommendationsBox');
        box.innerHTML = '';

        if (data.length === 0) {
            box.innerHTML = `<div class="bg-white p-4 rounded shadow">Aucune recommandation</div>`;
            return;
        }

        data.forEach(function (course) {
            box.innerHTML += `
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-2">${course.title}</h2>
                    <p class="text-slate-600 mb-2">${course.description}</p>
                    <p class="font-semibold mb-3">${course.price} DH</p>

                    <div class="flex gap-2">
                        <a href="/courses/${course.id}" class="bg-blue-600 text-white px-3 py-2 rounded">Voir détails</a>
                        <button onclick="addToWishlist(${course.id})" class="bg-pink-600 text-white px-3 py-2 rounded">Favori</button>
                    </div>
                </div>
            `;
        });
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

        showMessage('Ajouté aux favoris');
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (!requireRole('student')) return;
        loadRecommendations();
    });
</script>
@endsection