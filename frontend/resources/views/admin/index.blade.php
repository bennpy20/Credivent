@extends('components.comp_one.layout')

@section('title')
    Admin
@endsection

@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Dashboard
            </h2>
            <!-- Cards -->
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-2">
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                    <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Total Panitia
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $panitiaCount }}
                        </p>
                    </div>
                </div>
                <!-- Card -->
                <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                    <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Total Tim Keuangan
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $keuanganCount }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Charts -->
                <div class="mb-8">
                    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                        <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                            Perbandingan Jumlah Panitia dan Tim Keuangan
                        </h4>
                        <canvas id="pie"></canvas>
                        <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                            <!-- Chart legend -->
                            <div class="flex items-center">
                                <span class="inline-block w-3 h-3 mr-1 bg-blue-500 rounded-full"></span>
                                <span>Tim Keuangan</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                <span>Panitia</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="w-full overflow-hidden rounded-lg shadow-xs">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full table-auto whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">Role</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                @foreach ($users as $user)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-sm">
                                            {{ $user['id'] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ $user['name'] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @switch($user['role'])
                                                @case(3)
                                                    Panitia
                                                @break

                                                @case(4)
                                                    Tim Keuangan
                                                @break

                                                @default
                                                    Lainnya
                                            @endswitch
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-{{ $user['status_color'] }}-700 bg-{{ $user['status_color'] }}-100 rounded-full dark:bg-{{ $user['status_color'] }}-700 dark:text-{{ $user['status_color'] }}-100">
                                                {{ $user['status_label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataPanitia = {{ json_encode($panitiaCount) }};
            const dataKeuangan = {{ json_encode($keuanganCount) }};

            const pieConfig = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [dataPanitia, dataKeuangan],
                        backgroundColor: ['#0694a2', '#1c64f2'],
                        label: 'Jumlah User per Role',
                    }],
                    labels: ['Panitia', 'Tim Keuangan'],
                },
                options: {
                    responsive: true,
                    cutoutPercentage: 80,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                },
            };

            const pieCtx = document.getElementById('pie');
            if (pieCtx) {
                window.myPie = new Chart(pieCtx, pieConfig);
            }
        });
    </script>
@endsection
