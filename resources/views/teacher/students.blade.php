@extends('layouts.app')

@section('title', 'Étudiants')

@section('content')
<h1 class="text-3xl font-bold mb-6">Étudiants du cours</h1>
<div id="studentsBox" class="bg-white rounded shadow overflow-x-auto"></div>
@endsection

@section('scripts')
<script>
    let courseId = {{ $id }};

    async function loadStudents() {
        let response = await fetch('/api/teacher/courses/' + courseId + '/students', {
            method: 'GET',
            headers: authHeaders()
        });

        let course = await response.json();

        if (!response.ok) {
            showMessage(course.message || course.error || course.erreur || 'Erreur chargement', 'error');
            return;
        }

        let enrollments = course.enrollments || [];

        let html = `
            <div class="p-4 border-b">
                <h2 class="text-2xl font-bold">${course.title}</h2>
            </div>
            <table class="w-full">
                <thead class="bg-slate-200">
                    <tr>
                        <th class="text-left p-3">Nom</th>
                        <th class="text-left p-3">Email</th>
                        <th class="text-left p-3">Statut</th>
                        <th class="text-left p-3">Paiement</th>
                    </tr>
                </thead>
                <tbody>
        `;

        if (enrollments.length === 0) {
            html += `
                <tr>
                    <td colspan="4" class="p-3">Aucun étudiant inscrit</td>
                </tr>
            `;
        } else {
            enrollments.forEach(function (item) {
                html += `
                    <tr class="border-t">
                        <td class="p-3">${item.student ? item.student.name : ''}</td>
                        <td class="p-3">${item.student ? item.student.email : ''}</td>
                        <td class="p-3">${item.status}</td>
                        <td class="p-3">${item.payment_status}</td>
                    </tr>
                `;
            });
        }

        html += `</tbody></table>`;

        document.getElementById('studentsBox').innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', function () {
        requireRole('teacher').then(function (ok) {
            if (ok) loadStudents();
        });
    });
</script>
@endsection