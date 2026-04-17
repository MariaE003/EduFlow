<!-- 
    <section>
        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-700">Suggestions pour vous</p>
        <h1 class="mt-3 text-3xl font-semibold text-stone-900">Cours recommandes</h1>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-stone-600">Les suggestions utilisent en priorite la route backend <span class="font-semibold text-brand-700">/api/recommended-courses</span>, avec un repli local si besoin.</p>
        <div id="recommendState" class="mt-6 rounded-3xl border border-dashed border-stone-300 bg-white px-6 py-10 text-center text-stone-500">
            Chargement des recommandations...
        </div>
        <div id="recommendGrid" class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3"></div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            if (!await EduFlow.requireRole('student')) return;

            const state = document.getElementById('recommendState');
            const grid = document.getElementById('recommendGrid');

            try {
                let recommended = [];

                try {
                    recommended = await EduFlow.api('/recommended-courses');
                } catch (error) {
                    const interests = EduFlow.getSavedInterests();
                    const courses = await EduFlow.api('/courses');
                    recommended = interests.length ? courses.filter((course) => interests.includes(Number(course.interest_id))) : [];
                }

                if (!recommended.length) {
                    state.textContent = 'Aucune recommandation disponible pour le moment.';
                    return;
                }

                state.classList.add('hidden');
                grid.innerHTML = recommended.map((course) => {
                    const actions = `
                        <a href="${EduFlow.courseUrl(course.id)}" class="rounded-2xl bg-brand-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-800">Voir details</a>
                        <button data-wishlist="${course.id}" class="rounded-2xl border border-stone-200 px-4 py-3 text-sm font-medium text-stone-700 transition hover:bg-stone-50">Ajouter aux favoris</button>
                    `;
                    return EduFlow.courseCard(course, actions);
                }).join('');

                grid.querySelectorAll('[data-wishlist]').forEach((button) => {
                    button.addEventListener('click', async () => {
                        try {
                            await EduFlow.api('/wishlist', {
                                method: 'POST',
                                body: JSON.stringify({ course_id: Number(button.dataset.wishlist) })
                            });
                            EduFlow.showFlash('Cours ajoute aux favoris.');
                        } catch (error) {
                            EduFlow.showFlash(error.message, 'error');
                        }
                    });
                });
            } catch (error) {
                state.textContent = error.message;
            }
        });
    </script>
 -->



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