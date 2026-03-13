<!DOCTYPE html>
<html>
<head>
    <title>MDT System - Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-10">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Create Account</h2>

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 mb-4 rounded text-sm border border-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Middle Name <span class="text-gray-400 text-xs">(Optional)</span></label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <hr class="mb-4 border-gray-100">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700 transition duration-200">
                Register User
            </button>
            
            <p class="mt-4 text-center text-sm text-gray-600">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a>
            </p>
        </form>
    </div>
</body>
</html>