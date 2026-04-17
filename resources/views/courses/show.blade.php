@extends('layouts.app')

@section('title', 'Détail du cours')

@section('content')
<div id="courseBox" class="bg-white p-6 rounded shadow"></div>
@endsection

@section('scripts')
<script>
    let courseId = {{ $id }};

    async function loadCourse() {
        let response = await fetch('/api/courses/' + courseId, {
            method: 'GET',
            headers: authHeaders()
        });

        let course = await response.json();

        if (!response.ok) {
            showMessage(course.message || course.erreur || 'Cours introuvable', 'error');
            return;
        }

        document.getElementById('courseBox').innerHTML = `
            <h1 class="text-3xl font-bold mb-3">${course.title}</h1>
            <p class="text-slate-700 mb-3">${course.description}</p>
            <p class="text-lg font-semibold mb-4">${course.price} DH</p>

            <div class="flex gap-2 flex-wrap">
                <button onclick="addToWishlist(${course.id})" class="bg-pink-600 text-white px-4 py-2 rounded">Ajouter aux favoris</button>
                <button onclick="enrollCourse(${course.id})" class="bg-green-600 text-white px-4 py-2 rounded">S'inscrire</button>
            </div>
        `;
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

    async function enrollCourse(courseId) {
        let payResponse = await fetch('/api/courses/' + courseId + '/pay', {
            method: 'POST',
            headers: authHeaders()
        });

        let payData = await payResponse.json();

        if (!payResponse.ok) {
            showMessage(payData.message || payData.error || payData.erreur || 'Erreur paiement', 'error');
            return;
        }

        let confirmResponse = await fetch('/api/courses/' + courseId + '/confirm', {
            method: 'POST',
            headers: jsonHeaders(),
            body: JSON.stringify({
                payment_id: 'demo_payment_' + Date.now()
            })
        });

        let confirmData = await confirmResponse.json();

        if (!confirmResponse.ok) {
            showMessage(confirmData.message || confirmData.error || confirmData.erreur || 'Erreur confirmation', 'error');
            return;
        }

        saveMyCourse(courseId);
        showMessage('Inscription confirmée');
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (!requireAuth()) return;
        loadCourse();
    });
</script>
@endsection