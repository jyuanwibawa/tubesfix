@extends('dashboard.main')

@section('content')
<!-- Dashboard Title -->
<div class="px-10 mb-6">
    <h1 class="font-poppins font-semibold text-3xl text-gray-900">Dashboard</h1>
</div>

<!-- Grid Card -->
<<div class="px-10 w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-6">
    @foreach([
    ['label' => 'Jumlah Pengguna', 'value' => $userCount, 'icon' => 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4
    1.79 4 4 4z'],
    ['label' => 'Jumlah Admin', 'value' => $adminCount, 'icon' => 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79
    4 4 4z'],
    ['label' => 'Jumlah Pengelola', 'value' => $pengelolaCount, 'icon' => 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4
    4 1.79 4 4 4z'],
    ['label' => 'Artikel Published', 'value' => $publishedArticles, 'icon' => 'M5 4v16l7-7 7 7V4H5z'],
    ['label' => 'Artikel Draft', 'value' => $unpublishedArticles, 'icon' => 'M5 4v16l7-7 7 7V4H5z'],
    ['label' => 'Total Pickup Request', 'value' => $totalPickupRequest, 'icon' => 'M12 2L1 21h22L12 2zm0 3.84L19.74
    19H4.26L12 5.84zM11 10v4h2v-4h2zm0 6v2h2v-2h-2z'],
    ['label' => 'Pickup Completed', 'value' => $completedPickup, 'icon' => 'M9 16.17l-3.88-3.88L4 13.41l5 5
    9-9-1.41-1.42z'],
    ['label' => 'Pickup Accepted', 'value' => $acceptedPickup, 'icon' => 'M9 16.17l-3.88-3.88L4 13.41l5 5
    9-9-1.41-1.42z'],
    ['label' => 'Pickup Pending', 'value' => $pendingPickup, 'icon' => 'M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12
    2zm1 15h-2v-6h2zm0-8h-2V7h2z'],
    ['label' => 'Pickup Rejected', 'value' => $rejectedPickup, 'icon' => 'M9 16.17l-3.88-3.88L4 13.41l5 5
    9-9-1.41-1.42z'],
    ] as $card)
    <div
        class="transition-transform transform hover:scale-105 border-l-4 border-primary-green px-6 py-8 flex items-center justify-between shadow-lg bg-white rounded-xl hover:shadow-2xl">
        <div class="flex flex-col">
            <p class="font-poppins font-medium text-primary-green text-lg">{{ $card['label'] }}</p>
            <p class="font-poppins font-light text-gray-700 text-xl">{{ $card['value'] }}</p>
        </div>
        <svg class="w-12 h-12 text-primary-green" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
            viewBox="0 0 24 24">
            <path d="{{ $card['icon'] }}"></path>
        </svg>
    </div>
    @endforeach
    </div>

    <div class="px-10 w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">
        <!-- Line Chart Card -->
        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl h-[350px]">
            <h2 class="text-center mb-2 font-semibold">Pickup Request (Line Chart)</h2>
            <canvas id="grafikStatusPickupLine" style="height: 200px;"></canvas>
        </div>

        <!-- Pie Chart Card -->
        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl h-[350px]">
            <h2 class="text-center mb-2 font-semibold">Pickup Request (Pie Chart)</h2>
            <canvas id="grafikStatusPickupPie" style="height: 200px;"></canvas>
        </div>

        <!-- Bar Chart Card -->
        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl h-[350px]">
            <h2 class="text-center mb-2 font-semibold">Pickup Request (Bar Chart)</h2>
            <canvas id="grafikStatusPickupBar" style="height: 200px;"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const months = @json($months);
            const statusPending = @json($statusPending);
            const statusAccepted = @json($statusAccepted);
            const statusRejected = @json($statusRejected);
            const statusCompleted = @json($statusCompleted);

            // Line Chart
            const ctxLine = document.getElementById('grafikStatusPickupLine').getContext('2d');
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                            label: 'Pickup Pending',
                            data: statusPending,
                            borderColor: '#FF9F40',
                            fill: false
                        },
                        {
                            label: 'Pickup Accepted',
                            data: statusAccepted,
                            borderColor: '#4BC0C0',
                            fill: false
                        },
                        {
                            label: 'Pickup Rejected',
                            data: statusRejected,
                            borderColor: '#FF6384',
                            fill: false
                        },
                        {
                            label: 'Pickup Completed',
                            data: statusCompleted,
                            borderColor: '#36A2EB',
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });

            // Pie Chart (Total Data)
            const ctxPie = document.getElementById('grafikStatusPickupPie').getContext('2d');
            new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Pending', 'Accepted', 'Rejected', 'Completed'],
                    datasets: [{
                        data: [
                            statusPending.reduce((a, b) => a + b, 0),
                            statusAccepted.reduce((a, b) => a + b, 0),
                            statusRejected.reduce((a, b) => a + b, 0),
                            statusCompleted.reduce((a, b) => a + b, 0)
                        ],
                        backgroundColor: ['#FF9F40', '#4BC0C0', '#FF6384', '#36A2EB']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });

            // Bar Chart
            const ctxBar = document.getElementById('grafikStatusPickupBar').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                            label: 'Pickup Pending',
                            data: statusPending,
                            backgroundColor: '#FF9F40'
                        },
                        {
                            label: 'Pickup Accepted',
                            data: statusAccepted,
                            backgroundColor: '#4BC0C0'
                        },
                        {
                            label: 'Pickup Rejected',
                            data: statusRejected,
                            backgroundColor: '#FF6384'
                        },
                        {
                            label: 'Pickup Completed',
                            data: statusCompleted,
                            backgroundColor: '#36A2EB'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    @endsection