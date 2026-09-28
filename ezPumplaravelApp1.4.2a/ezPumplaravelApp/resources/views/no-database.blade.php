<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-6" x-data="{ showModal:false, actionUrl:'', dbExists: {{ $dbExists ? 'true' : 'false' }} }">

    {{-- Header --}}
    <h2 class="text-2xl font-bold text-gray-800 mb-4">
        {{ $dbExists ? 'System Maintenance' : 'Initial Setup Required' }}
    </h2>

    {{-- Info --}}
    @if(!$dbExists)
        <p class="text-red-600 mb-4">
            🚨 Database <strong>{{ $dbName ?? 'not configured' }}</strong> is missing.
            Please run setup before using the system.
        </p>
    @else
        <p class="text-gray-600 mb-4">
            Database <strong>{{ $dbName }}</strong> detected. You can run maintenance actions below.
        </p>
    @endif

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('output'))
        <div class="bg-gray-900 text-green-400 font-mono p-3 rounded mb-4 overflow-x-auto text-sm">
            <pre>{{ session('output') }}</pre>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="space-y-3">
        <button @click="showModal=true; actionUrl='{{ route('setup.create') }}'"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-semibold">
            ⚙️ Run Config
        </button>

        <button @click="showModal=true; actionUrl='{{ route('setup.reboot_no_db') }}'"
                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-semibold">
            🔁 Reboot System
        </button>
    </div>

    {{-- Password Modal --}}
    <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div @click.away="showModal=false" class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-4">🔒 Enter Password</h3>
            <form method="POST" :action="actionUrl">
                @csrf
                <input type="password" name="password" placeholder="Enter password"
                       class="w-full border rounded p-2 mb-4 focus:ring focus:ring-blue-300"
                       x-bind:placeholder="dbExists ? 'User password' : 'Type here'" required>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showModal=false"
                            class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 text-gray-800">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
