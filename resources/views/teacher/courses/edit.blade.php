@extends('layouts.app')

@section('title', 'Modifier cours')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Modifier le cours</h1>

    <form id="courseForm" class="space-y-4">
        <div>
            <label class="block mb-1">Titre</label>
            <input type="text" id="title" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Description</label>
            <textarea id="description" class="w-full border rounded px-3 py-2" required></textarea>
        </div>

        <div>
            <label class="block mb-1">Prix</label>
            <input type="number" id="price" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Intérêt</label>
            <select id="interest_id" class="w-full border rounded px-3 py-2"></select>
        </div>

        <button class="w-full bg-yellow-500 text-white py-2 rounded">Mettre à jour</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let courseId = {{ $id }};

    async function loadInterests() {
        let response = await fetch('/api/interests', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        let data = await response.json();

        let select = document.getElementById('interest_id');
        select.innerHTML = `<option value="">Choisir</option>`;

        data.forEach(function (interest) {
            select.innerHTML += `<option value="${interest.id}">${interest.name}</option>`;
        });
    }

    async function loadCourse() {
        let response = await fetch('/api/courses/' + courseId, {
            method: 'GET',
            headers: authHeaders()
        });

        let course = await response.json();

        if (!response.ok) {
            showMessage(course.message || course.error || course.erreur || 'Erreur chargement', 'error');
            return;
        }

        document.getElementById('title').value = course.title || '';
        document.getElementById('description').value = course.description || '';
        document.getElementById('price').value = course.price || '';
        document.getElementById('interest_id').value = course.interest_id || '';
    }

    document.getElementById('courseForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        let response = await fetch('/api/courses/' + courseId, {
            method: 'PUT',
            headers: jsonHeaders(),
            body: JSON.stringify({
                title: document.getElementById('title').value,
                description: document.getElementById('description').value,
                price: document.getElementById('price').value,
                interest_id: document.getElementById('interest_id').value
            })
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.error || data.erreur || 'Erreur mise à jour', 'error');
            return;
        }

        showMessage('Cours modifié');
        window.location.href = "{{ route('teacher.courses.index') }}";
    });

    document.addEventListener('DOMContentLoaded', function () {
        requireRole('teacher').then(async function (ok) {
            if (!ok) return;
            await loadInterests();
            await loadCourse();
        });
    });
</script>
@endsection