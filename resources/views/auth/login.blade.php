@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Connexion</h1>

    <form id="loginForm" class="space-y-4">
        <div>
            <label class="block mb-1">Email</label>
            <input type="email" id="email" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Mot de passe</label>
            <input type="password" id="password" class="w-full border rounded px-3 py-2" required>
        </div>

        <button class="w-full bg-blue-600 text-white py-2 rounded">
            Se connecter
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;
        let response = await fetch('/api/login', {
            method: 'POST',
            headers: jsonHeaders(),
            body: JSON.stringify({
                email: email,
                password: password
            })
        });
        let data = await response.json();

        if (!response.ok) {
            showMessage(data.erreur || 'Login failed', 'error');
            return;
        }

        setToken(data.token);
        setUserName(email);

        let role = await detectRole();

        if (role === 'teacher') {
            window.location.href = "{{ route('dashboard') }}";
        } else {
            window.location.href = "{{ route('home') }}";
        }
    });
</script>
@endsection