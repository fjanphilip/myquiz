@extends('layout')

@section('title', 'Register')

@section('content')
<div class="bg-white p-8 rounded shadow max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6 text-center">Create Account</h1>
    <form id="registerForm" class="space-y-4">
        <input type="text" name="name" placeholder="Name" class="w-full p-2 border rounded" required>
        <input type="email" name="email" placeholder="Email" class="w-full p-2 border rounded" required>
        <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded" required>
        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Register</button>
    </form>
    <div id="message" class="mt-4 text-center"></div>
    <p class="mt-4 text-center">Already have an account? <a href="{{ url('/login') }}" class="text-blue-600">Login</a></p>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const messageDiv = document.getElementById('message');

        messageDiv.textContent = 'Processing...';

        try {
            const response = await fetch('{{ url("/api/register") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                messageDiv.textContent = data.message || 'Account created successfully!';
                form.reset();
            } else {
                messageDiv.textContent = data.message || 'Registration failed';
            }
        } catch (error) {
            messageDiv.textContent = 'An error occurred. Try again.';
            console.error(error);
        }
    });
</script>
@endsection