@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    @if($errors->has('error'))
        <div class="max-w-4xl mx-auto mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong class="font-bold">Database Error:</strong>
            <span class="block sm:inline">{{ $errors->first('error') }}</span>
        </div>
    @endif
    <form action="{{ route('license.store') }}" method="POST" class="max-w-4xl mx-auto">
        @csrf
        
        <div class="bg-blue-800 text-white rounded-t-xl p-4 shadow-lg border-b-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold tracking-widest">REPUBLIC OF THE PHILIPPINES</h2>
                <span class="text-sm font-semibold">DRIVER LICENSE</span>
            </div>
        </div>

        <div class="bg-white shadow-2xl rounded-b-xl p-8 border-x border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="text-center">
                    <div class="w-48 h-48 bg-gray-200 border-2 border-dashed border-gray-400 mx-auto flex items-center justify-center mb-4">
                        <span class="text-gray-500">PHOTO</span>
                    </div>
                    <label class="block text-xs font-bold text-blue-800 uppercase">License No. : </label>
                    <input type="text" name="license_number" placeholder="D01-XX-XXXXXX" class="text-center w-full font-mono text-lg border-b-2 border-blue-800 focus:outline-none" id="license_number" required>
                    <button type="button" id="generate-ln-btn" class="bg-blue-800 text-white px-2 py-1 rounded text-xs hover:bg-blue-900">
                        Generate
                    </button>
                </div>

                <div class="md:col-span-2 grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Last Name, First Name, Middle Name</label>
                        <div class="flex gap-2">
                            <input type="text" name="last_name" placeholder="Last" class="w-1/3 border-b focus:border-blue-500 outline-none" required>
                            <input type="text" name="first_name" placeholder="First" class="w-1/3 border-b focus:border-blue-500 outline-none" required>
                            <input type="text" name="middle_name" placeholder="Middle" class="w-1/3 border-b focus:border-blue-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Nationality: </label>
                        <input type="text" name="nationality" value="Filipino" class="w-full border-b outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Gender: </label>
                        <select name="gender" class="w-full border-b outline-none" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Date of Birth: </label>
                        <input type="date" name="date_of_birth" class="w-full border-b outline-none" required>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Blood Type: </label>
                        <input type="text" name="blood_type" placeholder="O+" class="w-full border-b outline-none" required>
                    </div>

                    <div class="col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Address: </label>
                        <input type="text" name="address" placeholder="Full Home Address" class="w-full border-b outline-none" required>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 col-span-1 md:col-span-2">
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase">Height (cm): </label>
                    <input type="text" name="height" placeholder="170cm" class="w-full border rounded p-2 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase">Weight (kg): </label>
                    <input type="text" name="weight" placeholder="60kg" class="w-full border rounded p-2 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 uppercase">Eye Color: </label>
                    <input type="text" name="eye_color" placeholder="Brown" class="w-full border rounded p-2 outline-none" required>
                </div>
            </div>
            </div>

            <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 bg-blue-50 p-6 rounded-lg border border-blue-100">
                <div>
                    <label class="block text-sm font-bold text-blue-900">License Type: </label>
                    <select name="license_type" class="w-full p-2 rounded border">
                        <option value="Professional">Professional</option>
                        <option value="Non-Professional">Non-Professional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-blue-900">DL Codes (Array): </label>
                    <div class="flex flex-wrap gap-2 text-xs">
                        @foreach(['A', 'A1', 'B', 'B1', 'B2', 'C', 'D'] as $code)
                            <label><input type="checkbox" name="dl_codes[]" value="{{$code}}"> {{$code}}</label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-blue-900">Expiry Option (Years): </label>
                    <select name="expiry_option" class="w-full p-2 rounded border">
                        <option value="5">5 Years</option>
                        <option value="10">10 Years</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-800 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-900 transition shadow-lg">
                    ISSUE LICENSE
                </button>
            </div>
        </div>
    </form>
</div>


<script>
    document.getElementById('generate-ln-btn').addEventListener('click', async function() {
        try {
            const response = await fetch('/license/uniquelicensenum');
            const data = await response.json();

            if (data.status === 'success') {
                document.getElementById('license_number').value = data.data.license_number;
            } else {
                alert('Failed to generate license number: ' + data.message);
            }
        } catch (error) {
            alert('Something went wrong.');
        }
    });
</script>
@endsection