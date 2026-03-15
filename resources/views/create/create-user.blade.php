@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    @if($errors->has('error'))
        <div class="max-w-4xl mx-auto mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong class="font-bold">Database Error:</strong>
            <span class="block sm:inline">{{ $errors->first('error') }}</span>
        </div>
    @endif
    <form action="{{ route('user.store') }}" method="POST" class="max-w-4xl mx-auto">
        @csrf
        
        <div class="bg-blue-800 text-white rounded-t-xl p-4 shadow-lg border-b-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold tracking-widest">REPUBLIC OF THE PHILIPPINES</h2>
                <span class="text-sm font-semibold">USER FORM</span>
            </div>
        </div>

        <div class="bg-white shadow-2xl rounded-b-xl p-8 border-x border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="md:col-span-2 grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Last Name, First Name, Middle Name</label>
                        <div class="flex gap-2">
                            <input type="text" name="last_name" placeholder="Last" class="w-1/3 border-b focus:border-blue-500 outline-none" required>
                            <input type="text" name="first_name" placeholder="First" class="w-1/3 border-b focus:border-blue-500 outline-none" required>
                            <input type="text" name="middle_name" placeholder="Middle" class="w-1/3 border-b focus:border-blue-500 outline-none">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Username: </label>
                    <input type="username" name="username" value="{{ old('username') }}" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email Address: </label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Password: </label>
                    <input type="password" name="password" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-800 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-900 transition shadow-lg">
                    ISSUE USER ACCOUNT
                </button>
            </div>
        </div>
    </form>
</div>
@endsection