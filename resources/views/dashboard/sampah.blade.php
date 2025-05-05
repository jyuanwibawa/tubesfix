@extends('dashboard.main')

@section('content')
<p class="font-poppins font-medium text-gray-800 text-[28px] ms-10 mb-6">Grafik Sampah</p>

<!-- Kartu TPS dan TPA -->
<div class="px-10 w-full flex flex-col md:flex-row gap-6 mb-6">
    <!-- TPS -->
    <div
        class="border-l-4 border-primary-green px-6 py-6 w-full flex justify-between items-center shadow-md bg-white rounded-lg">
        <div>
            <p class="text-primary-green text-lg font-semibold">Jumlah Sampah TPS</p>
            <p class="text-gray-800 text-md font-light">{{ $jumlahSampahTPSd }}</p>
        </div>
        <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="#198754" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 
            1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
        </svg>
    </div>

    <!-- TPA -->
    <div
        class="border-l-4 border-primary-green px-6 py-6 w-full flex justify-between items-center shadow-md bg-white rounded-lg">
        <div>
            <p class="text-primary-green text-lg font-semibold">Jumlah Sampah TPA</p>
            <p class="text-gray-800 text-md font-light">{{ $jumlahSampahTPAd }}</p>
        </div>
        <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="#198754" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 
            1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
        </svg>
    </div>
</div>
<!-- Daftar Collection Point -->
<div class="px-10 mt-10">
    <p class="font-poppins font-medium text-gray-800 text-[24px] mb-4">Daftar Collection Point</p>

    <!-- Tombol Tambah Data -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('collection-points.create') }}"
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md font-medium">
            + Tambah Data
        </a>
    </div>

    @if($collectionPoints->isEmpty())
    <p class="text-gray-500">Tidak ada data collection point.</p>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto bg-white shadow-md rounded-md">
            <thead class="bg-primary-green text-white text-left">
                <tr>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Tipe</th>
                    <th class="py-3 px-4">Latitude</th>
                    <th class="py-3 px-4">Longitude</th>
                    <th class="py-3 px-4">Deskripsi</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($collectionPoints as $point)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-2 px-4">{{ $point->name }}</td>
                    <td class="py-2 px-4">{{ $point->type }}</td>
                    <td class="py-2 px-4">{{ $point->lat }}</td>
                    <td class="py-2 px-4">{{ $point->lng }}</td>
                    <td class="py-2 px-4">{{ $point->description }}</td>
                    <td class="py-2 px-4 space-x-2">
                        <!-- Tombol Edit -->
                        <a href="{{ route('collection-points.edit', $point->id) }}"
                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md text-sm">
                            Edit
                        </a>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('collection-points.destroy', $point->id) }}" method="POST"
                            class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Grafik -->
<div class="px-10 w-full">
    <div class="relative w-full h-[400px]">
        <canvas id="grafikSampah"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('grafikSampah').getContext('2d');

        const bulanLabels = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const dataTPS = @json($dataTPS);
        const dataTPA = @json($dataTPA);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [{
                        label: 'Sampah TPS',
                        data: dataTPS,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Sampah TPA',
                        data: dataTPA,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endsection