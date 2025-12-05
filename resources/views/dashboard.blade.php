@extends('layout')

@section('title', 'Dashboard')

@section('content')
<div class="p-8 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <p id="welcome">Loading...</p>
    <button id="logoutBtn" class="bg-red-600 text-white px-4 py-2 rounded">Logout</button>
</div>

<script>
    async function fetchUser() {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }

        try {
            const response = await fetch('/api/user', {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });
            const user = await response.json();
            document.getElementById('welcome').textContent = `Welcome, ${user.name}`;
        } catch (e) {
            console.error(e);
            window.location.href = '/login';
        }
    }

    fetchUser();

    document.getElementById('logoutBtn').addEventListener('click', async () => {
        const token = localStorage.getItem('auth_token');
        if (!token) return;

        await fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        localStorage.removeItem('auth_token');
        window.location.href = '/login';
    });
</script>
@endsection