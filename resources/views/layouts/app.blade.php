<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduFlow')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">

    <nav class="bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold">EduFlow</a>
            <div id="navLinks" class="flex gap-3 flex-wrap"></div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-6">
        <div id="messageBox"></div>
        @yield('content')
    </main>

    <script>
        function getToken() {
            return localStorage.getItem('token') || '';
        }

        function getRole() {
            return localStorage.getItem('role') || '';
        }

        function setToken(token) {
            localStorage.setItem('token', token);
        }

        function setRole(role) {
            localStorage.setItem('role', role);
        }

        function setUserName(name) {
            localStorage.setItem('name', name);
        }

        function getUserName() {
            return localStorage.getItem('name') || '';
        }

        function clearAuth() {
            localStorage.removeItem('token');
            localStorage.removeItem('role');
            localStorage.removeItem('name');
        }

        function getMyCourses() {
            return JSON.parse(localStorage.getItem('my_courses') || '[]');
        }

        function saveMyCourse(courseId) {
            let ids = getMyCourses();
            if (!ids.includes(courseId)) {
                ids.push(courseId);
                localStorage.setItem('my_courses', JSON.stringify(ids));
            }
        }

        function removeMyCourse(courseId) {
            let ids = getMyCourses().filter(id => id != courseId);
            localStorage.setItem('my_courses', JSON.stringify(ids));
        }

        function authHeaders() {
            let headers = {
                'Accept': 'application/json'
            };

            if (getToken()) {
                headers['Authorization'] = 'Bearer ' + getToken();
            }

            return headers;
        }

        function jsonHeaders() {
            let headers = authHeaders();
            headers['Content-Type'] = 'application/json';
            return headers;
        }

        function showMessage(message, type = 'success') {
            let color = type === 'error'? 'bg-red-100 text-red-700 border-red-300' : 'bg-green-100 text-green-700 border-green-300';
            document.getElementById('messageBox').innerHTML = `
                <div class="mb-4 border px-4 py-3 rounded ${color}">
                    ${message}
                </div>
            `;
        }

        async function detectRole() {
            if (!getToken()) return '';

            let response = await fetch('/api/teacher/stats', {
                method: 'GET',
                headers: authHeaders()
            });

            if (response.ok) {
                setRole('teacher');
                return 'teacher';
            } else {
                setRole('student');
                return 'student';
            }
        }

        function requireAuth() {
            if (!getToken()) {
                window.location.href = "{{ route('login') }}";
                return false;
            }
            return true;
        }

        async function requireRole(role) {
            if (!requireAuth()) return false;

            let currentRole = getRole();
            if (!currentRole) {
                currentRole = await detectRole();
            }

            if (currentRole !== role) {
                if (currentRole === 'teacher') {
                    window.location.href = "{{ route('dashboard') }}";
                } else {
                    window.location.href = "{{ route('home') }}";
                }
                return false;
            }

            return true;
        }

        async function logout() {
            try {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: authHeaders()
                });
            } catch (error) {
            }
            clearAuth();
            window.location.href = "{{ route('login') }}";
        }

        async function renderNav() {
            let nav = document.getElementById('navLinks');

            if (!getToken()) {
                nav.innerHTML = `
                    <a href="{{ route('login') }}" class="bg-white text-slate-900 px-4 py-2 rounded">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-500 px-4 py-2 rounded">Register</a>
                `;
                return;
            }

            let role = getRole();
            if (!role) {
                role = await detectRole();
            }

            if (role === 'teacher') {
                nav.innerHTML = `
                    <a href="{{ route('dashboard') }}" class="bg-slate-700 px-4 py-2 rounded">Dashboard</a>
                    <a href="{{ route('teacher.courses.index') }}" class="bg-slate-700 px-4 py-2 rounded">Mes cours</a>
                    <a href="{{ route('teacher.stats') }}" class="bg-slate-700 px-4 py-2 rounded">Stats</a>
                    <a href="{{ route('teacher.courses.create') }}" class="bg-green-600 px-4 py-2 rounded">Créer cours</a>
                    <button onclick="logout()" class="bg-red-600 px-4 py-2 rounded">Logout</button>
                `;
            } else {
                nav.innerHTML = `
                    <a href="{{ route('home') }}" class="bg-slate-700 px-4 py-2 rounded">Cours</a>
                    <a href="{{ route('recommendations') }}" class="bg-slate-700 px-4 py-2 rounded">Recommandations</a>
                    <a href="{{ route('wishlist') }}" class="bg-slate-700 px-4 py-2 rounded">Favoris</a>
                    <a href="{{ route('my-courses') }}" class="bg-slate-700 px-4 py-2 rounded">Mes cours</a>
                    <button onclick="logout()" class="bg-red-600 px-4 py-2 rounded">Logout</button>
                `;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            renderNav();
        });
    </script>

    @yield('scripts')
</body>
</html>