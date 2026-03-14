@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    @if($errors->has('error'))
        <div class="max-w-4xl mx-auto mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong class="font-bold">Database Error:</strong>
            <span class="block sm:inline">{{ $errors->first('error') }}</span>
        </div>
    @endif
    <form action="{{ route('vehicle.store') }}" method="POST" class="max-w-4xl mx-auto">
        @csrf
        
        <div class="bg-blue-800 text-white rounded-t-xl p-4 shadow-lg border-b-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold tracking-widest">REPUBLIC OF THE PHILIPPINES</h2>
                <span class="text-sm font-semibold">VEHICLE CREATION FORM</span>make, model, color
            </div>
        </div>

        <div class="bg-white shadow-2xl rounded-b-xl p-8 border-x border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="text-center">
                    <label class="block text-xs font-bold text-blue-800 uppercase">License No. : </label>
                    <input type="text" name="license_number" placeholder="D01-XX-XXXXXX" class="text-center w-full font-mono text-lg border-b-2 border-blue-800 focus:outline-none" required>
                </div>

                <div class="md:col-span-2 grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">MV file Number, Plate Number, Vin Number</label>
                        <div class="flex gap-2">
                            <input type="text" name="mv_file_number" placeholder="MV File Number" class="w-1/3 border-b focus:border-blue-500 outline-none" required>
                            <input type="text" name="plate_number" placeholder="Plate Number" class="w-1/3 border-b focus:border-blue-500 outline-none" required>
                            <input type="text" name="vin" placeholder="Vin Number" class="w-1/3 border-b focus:border-blue-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Make: </label>
                        <input type="text" name="make" placeholder="Brand Name (Make)" class="w-full border-b outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Model: </label>
                        <input type="text" name="model" placeholder="Brand Model" class="w-full border-b outline-none" required>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Year: </label>
                        <input type="text" name="year" placeholder="Model Year" class="w-full border-b outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Color: </label>
                        <select name="color" class="w-full border-b outline-none">
                            <option value="Blue">Blue</option>
                            <option value="Red">Red</option>
                            <option value="White">White</option>
                            <option value="Black">Black</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-blue-900">Register Status: </label>
                        <select name="register_status" class="w-full p-2 rounded border">
                            <option value="Registered">Registered</option>
                            <option value="Unregistered">Unregistered</option>
                            <option value="Expired">Expired</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-blue-900">Expiry Option (Years): </label>
                        <select name="expiry_option" class="w-full p-2 rounded border">
                            <option value="1">1 Year</option>
                            <option value="3">3 Years</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-800 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-900 transition shadow-lg">
                    ISSUE VEHICLE REGISTRATION
                </button>
            </div>
        </div>
    </form>
</div>
@endsection