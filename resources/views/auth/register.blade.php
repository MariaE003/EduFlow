@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Inscription</h1>

    <form id="registerForm" class="space-y-4">
        <div>
            <label class="block mb-1">Nom</label>
            <input type="text" id="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Email</label>
            <input type="email" id="email" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Mot de passe</label>
            <input type="password" id="password" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Rôle</label>
            <select id="role" class="w-full border rounded px-3 py-2">
                <option value="student">Étudiant</option>
                <option value="teacher">Enseignant</option>
            </select>
        </div>

        <div id="interestsBox">
            <label class="block mb-2">Centres d'intérêt</label>
            <div id="interestsList" class="grid grid-cols-2 gap-2"></div>
        </div>

        <button class="w-full bg-green-600 text-white py-2 rounded">
            S'inscrire
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let roleSelect = document.getElementById('role');
    let interestsBox = document.getElementById('interestsBox');
    let interestsList = document.getElementById('interestsList');

    function toggleInterests() {
        if (roleSelect.value === 'student') {
            interestsBox.style.display = 'block';
        } else {
            interestsBox.style.display = 'none';
        }
    }

    roleSelect.addEventListener('change', toggleInterests);

    async function loadInterests() {
        let response = await fetch('/api/interests', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });

        let data = await response.json();

        interestsList.innerHTML = '';

        data.forEach(function (interest) {
            interestsList.innerHTML += `
                <label class="flex items-center gap-2 border rounded p-2">
                    <input type="checkbox" value="${interest.id}" class="interestItem">
                    <span>${interest.name}</span>
                </label>
            `;
        });
    }

    document.getElementById('registerForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        let interests = [];
        document.querySelectorAll('.interestItem:checked').forEach(function (item) {
            interests.push(Number(item.value));
        });

        let payload = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            role: document.getElementById('role').value,
            interests: interests
        };

        let response = await fetch('/api/register', {
            method: 'POST',
            headers: jsonHeaders(),
            body: JSON.stringify(payload)
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.erreur || 'Register failed', 'error');
            return;
        }

        setToken(data.token);
        setRole(data.user.role);
        setUserName(data.user.name);

        if (data.user.role === 'teacher') {
            window.location.href = "{{ route('dashboard') }}";
        } else {
            window.location.href = "{{ route('home') }}";
        }
    });
    document.addEventListener('DOMContentLoaded', function () {
        loadInterests();
        toggleInterests();
    });
</script>
@endsection