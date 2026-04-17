<!-- 
    <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
        <div id="courseDetail" class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            Chargement du cours...
        </div>

        <aside class="space-y-6">
            <div class="rounded-[2rem] bg-brand-900 p-8 text-white shadow-xl">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-200">Actions</p>
                <div id="actionBox" class="mt-5 space-y-3"></div>
            </div>
            <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-700">Paiement</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">Le parcours de paiement declenche d'abord <span class="font-semibold text-brand-700">/api/courses/{'{id}'}/pay</span>, puis confirme l'inscription via <span class="font-semibold text-brand-700">/api/courses/{'{id}'}/confirm</span>.</p>
            </div>
        </aside>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            if (!EduFlow.requireAuth()) return;

            const courseId = Number(@json($id));
            const detail = document.getElementById('courseDetail');
            const actionBox = document.getElementById('actionBox');

            try {
                const role = await EduFlow.detectRole();
                const course = await EduFlow.api('/courses/' + courseId);
                render(course, role);
            } catch (error) {
                detail.textContent = error.message;
            }

            function render(course, role) {
                detail.innerHTML = `
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-brand-700">Detail</p>
                    <h1 class="mt-4 text-4xl font-semibold text-stone-900">${course.title}</h1>
                    <p class="mt-6 text-base leading-8 text-stone-600">${course.description || 'Aucune description disponible.'}</p>
                    <div class="mt-8 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl bg-stone-50 p-5">
                            <p class="text-sm text-stone-500">Prix</p>
                            <p class="mt-2 text-2xl font-semibold text-stone-900">${EduFlow.formatPrice(course.price)}</p>
                        </div>
                        <div class="rounded-3xl bg-stone-50 p-5">
                            <p class="text-sm text-stone-500">Enseignant</p>
                            <p class="mt-2 text-lg font-semibold text-stone-900">${course.teacher?.name || ('Enseignant #' + (course.teacher_id || '-'))}</p>
                        </div>
                    </div>
                `;

                if (role === 'teacher') {
                    actionBox.innerHTML = `
                        <a href="${EduFlow.teacherEditUrl(course.id)}" class="block rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-brand-800 transition hover:bg-brand-50">Modifier ce cours</a>
                        <a href="${EduFlow.teacherStudentsUrl(course.id)}" class="block rounded-2xl border border-brand-200 px-4 py-3 text-center text-sm font-medium text-white transition hover:bg-white/10">Voir les etudiants</a>
                        <a href="${EduFlow.teacherGroupsUrl(course.id)}" class="block rounded-2xl border border-brand-200 px-4 py-3 text-center text-sm font-medium text-white transition hover:bg-white/10">Voir les groupes</a>
                    `;
                    return;
                }

                actionBox.innerHTML = `
                    <button id="wishlistButton" class="w-full rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-brand-800 transition hover:bg-brand-50">Ajouter aux favoris</button>
                    <button id="payButton" class="w-full rounded-2xl bg-accent-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-accent-600">S'inscrire au cours</button>
                `;

                document.getElementById('wishlistButton').addEventListener('click', async () => {
                    try {
                        await EduFlow.api('/wishlist', {
                            method: 'POST',
                            body: JSON.stringify({ course_id: course.id })
                        });
                        EduFlow.showFlash('Cours ajoute aux favoris.');
                    } catch (error) {
                        EduFlow.showFlash(error.message, 'error');
                    }
                });
                document.getElementById('payButton').addEventListener('click', async () => {
                    try {
                        const payment = await EduFlow.api('/courses/' + course.id + '/pay', { method: 'POST' });
                        const paymentId = window.prompt('Paiement cree. Saisissez un identifiant de paiement pour confirmer l inscription.', payment.client_secret || 'payment_demo');
                        if (!paymentId) return;
                        await EduFlow.api('/courses/' + course.id + '/confirm', {
                            method: 'POST',
                            body: JSON.stringify({ payment_id: paymentId })
                        });
                        EduFlow.saveEnrollment(course.id);
                        EduFlow.showFlash('Paiement confirme et inscription enregistree.');
                    } catch (error) {
                        EduFlow.showFlash(error.message, 'error');
                    }
                });
            }
        });
    </script> -->


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