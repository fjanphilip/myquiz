@extends('layout')

@section('title','Login')

@section('content')
<div class="bg-white p-8 rounded shadow max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
    <form id="loginForm" class="space-y-4">
        <input type="email" name="email" placeholder="Email" class="w-full p-2 border rounded" required>
        <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded" required>
        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Login</button>
    </form>
    <div id="message" class="mt-4 text-center"></div>
    <p class="mt-4 text-center">Don't have an account? <a href="{{ url('/register') }}" class="text-blue-600">Register</a></p>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const messageDiv = document.getElementById('message');

        messageDiv.textContent = 'Processing...';

        try {
            const response = await fetch('{{ url("/api/login") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                localStorage.setItem('auth_token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));
                window.location.href = '/dashboard';
            } else {
                messageDiv.textContent = data.message || 'Login failed';
            }
        } catch (error) {
            messageDiv.textContent = 'An error occurred';
            console.error(error);
        }
    });
</script>
@endsection