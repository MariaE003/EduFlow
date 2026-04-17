@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<h1 class="text-3xl font-bold mb-6">Statistiques des cours</h1>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full">
        <thead class="bg-slate-200">
            <tr>
                <th class="text-left p-3">Cours</th>
                <th class="text-left p-3">Prix</th>
                <th class="text-left p-3">Total</th>
                <th class="text-left p-3">Actifs</th>
                <th class="text-left p-3">Annulés</th>
            </tr>
        </thead>
        <tbody id="statsTable"></tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    async function loadStats() {
        let response = await fetch('/api/teacher/stats', {
            method: 'GET',
            headers: authHeaders()
        });

        let data = await response.json();

        if (!response.ok) {
            showMessage(data.message || data.error || data.erreur || 'Erreur stats', 'error');
            return;
        }

        let table = document.getElementById('statsTable');
        table.innerHTML = '';

        data.forEach(function (course) {
            table.innerHTML += `
                <tr class="border-t">
                    <td class="p-3">${course.title}</td>
                    <td class="p-3">${course.price} DH</td>
                    <td class="p-3">${course.total_students}</td>
                    <td class="p-3">${course.active_students}</td>
                    <td class="p-3">${course.cancelled_students}</td>
                </tr>
            `;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        requireRole('teacher').then(function (ok) {
            if (ok) loadStats();
        });
    });
</script>
@endsection