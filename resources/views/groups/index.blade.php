@extends('layouts.app')

@section('title', 'Groupes')

@section('content')
<h1 class="text-3xl font-bold mb-6">Groupes du cours</h1>
<div id="groupsBox" class="grid md:grid-cols-2 gap-4"></div>
@endsection

@section('scripts')
<script>
    let courseId = {{ $id }};

    async function loadGroups() {
        let response = await fetch('/api/courses/' + courseId + '/groups', {
            method: 'GET',
            headers: authHeaders()
        });

        let groups = await response.json();

        if (!response.ok) {
            showMessage(groups.message || groups.error || groups.erreur || 'Erreur chargement', 'error');
            return;
        }

        let box = document.getElementById('groupsBox');
        box.innerHTML = '';

        if (groups.length === 0) {
            box.innerHTML = `<div class="bg-white p-4 rounded shadow">Aucun groupe</div>`;
            return;
        }

        groups.forEach(function (group) {
            let membersHtml = '';

            if (group.members.length === 0) {
                membersHtml = `<p class="text-slate-500">Aucun étudiant</p>`;
            } else {
                group.members.forEach(function (member) {
                    membersHtml += `
                        <div class="border rounded p-2">
                            <p class="font-semibold">${member.student ? member.student.name : ''}</p>
                            <p class="text-slate-500">${member.student ? member.student.email : ''}</p>
                        </div>
                    `;
                });
            }

            box.innerHTML += `
                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-bold mb-3">Groupe ${group.group_number}</h2>
                    <div class="space-y-2">
                        ${membersHtml}
                    </div>
                </div>
            `;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        requireRole('teacher').then(function (ok) {
            if (ok) loadGroups();
        });
    });
</script>
@endsection