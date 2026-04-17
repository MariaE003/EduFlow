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
    
</script>
@endsection