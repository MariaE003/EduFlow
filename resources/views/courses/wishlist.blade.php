@extends('layouts.app')

@section('title', 'Favoris')

@section('content')
<h1 class="text-3xl font-bold mb-6">Mes favoris</h1>
<div id="wishlistBox" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
@endsection

@section('scripts')
<script>
    async function loadWishlist() {
        let wishlistResponse = await fetch('/api/wishlist', {
            method: 'GET',
            headers: authHeaders()
        });

        let wishlistData = await wishlistResponse.json();

        if (!wishlistResponse.ok) {
            showMessage(wishlistData.message || wishlistData.error || wishlistData.erreur || 'Erreur wishlist', 'error');
            return;
        }

        let coursesResponse = await fetch('/api/courses', {
            method: 'GET',
            headers: authHeaders()
        });

        let coursesData = await coursesResponse.json();

        if (!coursesResponse.ok) {
            showMessage(coursesData.message || coursesData.error || coursesData.erreur || 'Erreur cours', 'error');
            return;
        }

        let ids = wishlistData.map(function (item) {
            return item.course_id;
        });

        let courses = coursesData.filter(function (course) {
            return ids.includes(course.id);
        });

        let box = document.getElementById('wishlistBox');
        box.innerHTML = '';

        if (courses.length === 0) {
            box.innerHTML = `<div class="bg-white p-4 rounded shadow">Aucun favori</div>`;
            return;
        }

        courses.forEach(function (course) {
            box.innerHTML += `
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-2">${course.title}</h2>
                    <p class="text-slate-600 mb-2">${course.description}</p>
                    <p class="font-semibold mb-3">${course.price} DH</p>

                    <div class="flex gap-2 flex-wrap">
                        <a href="/courses/${course.id}" class="bg-blue-600 text-white px-3 py-2 rounded">Voir</a>
                        <button onclick="removeWishlist(${course.id})" class="bg-red-600 text-white px-3 py-2 rounded">Retirer</button>
                    </div>
                </div>
            `;
        });
    }

    async function removeWishlist(courseId) {
        let response = await fetch('/api/wishlist/' + courseId, {
            method: 'DELETE',
            headers: authHeaders()
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.error || data.erreur || 'Erreur suppression', 'error');
            return;
        }

        showMessage('Cours retiré des favoris');
        loadWishlist();
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (!requireRole('student')) return;
        loadWishlist();
    });
</script>
@endsection