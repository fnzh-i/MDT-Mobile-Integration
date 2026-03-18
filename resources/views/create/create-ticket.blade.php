@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    @if($errors->has('error'))
        <div class="max-w-4xl mx-auto mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong class="font-bold">Database Error:</strong>
            <span class="block sm:inline">{{ $errors->first('error') }}</span>
        </div>
    @endif
    <form action="{{ route('ticket.store') }}" method="POST" class="max-w-4xl mx-auto">
        @csrf
        
        <div class="bg-blue-800 text-white rounded-t-xl p-4 shadow-lg border-b-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold tracking-widest">REPUBLIC OF THE PHILIPPINES</h2>
                <span class="text-sm font-semibold">TICKET FORM</span>
            </div>
        </div>

        <div class="bg-white shadow-2xl rounded-b-xl p-8 border-x border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="text-center">
                     <label class="text-xs font-bold text-gray-500 uppercase">License_ID: </label>
                    <input type="number" name="license_id" placeholder="D01-XX-XXXXXX" class="text-center w-full font-mono text-lg border-b-2 border-blue-800 focus:outline-none" id="license_number" required>
                </div>

                <div class="md:col-span-2 grid grid-cols-2 gap-4">

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Nationality: </label>
                        <input type="text" name="nationality" value="Filipino" class="w-full border-b outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Date of Incident: </label>
                        <input type="date" name="date_of_incident" class="w-full border-b outline-none" required>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Place of Incident: </label>
                        <input type="text" name="place_of_incident" placeholder="Malate, Manila City" class="w-full border-b outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Violations: </label>
                        <input type="number" name="violation_id[]" placeholder="Violation" class="w-full border-b outline-none" required>
                    </div>

                    <div class="col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Notes: </label>
                        <input type="text" name="notes" placeholder="Full Incident" class="w-full border-b outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-blue-900">Status: </label>
                        <select name="license_type" class="w-full p-2 rounded border">
                            <option value="Unsettled">Unsettled</option>
                            <option value="Settled">Settled</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-800 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-900 transition shadow-lg">
                    ISSUE TICKET
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