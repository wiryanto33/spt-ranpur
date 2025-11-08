@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            </div>
        </div>

        <div class="section-body">
            {{-- Charts Row 1 --}}
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4>Distribusi Status Kerusakan</h4></div>
                        <div class="card-body">
                            <canvas id="chartDamageStatus" height="140"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4>Kesiapan Ranpur</h4></div>
                        <div class="card-body">
                            <canvas id="chartVehicleReady" height="140"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Row 2 --}}
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4>Tren Pemeliharaan 6 Bulan</h4></div>
                        <div class="card-body">
                            <canvas id="chartMaintenanceMonthly" height="140"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4>IN/OUT Sparepart (14 Hari)</h4></div>
                        <div class="card-body">
                            <canvas id="chartStockMovements" height="140"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Row 3 --}}
            {{-- <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4>Status Permintaan Sparepart</h4></div>
                        <div class="card-body">
                            <canvas id="chartRequestStatus" height="140"></canvas>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary"><i class="fas fa-truck"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Total Ranpur</h4></div>
                            <div class="card-body">{{ $totalVehicles }}</div>
                            <div class="card-footer p-2 text-muted">Siap: {{ $readyVehicles }} | Tidak: {{ $notReadyVehicles }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Kerusakan Terbuka</h4></div>
                            <div class="card-body">{{ $openDamage }}</div>
                            <div class="card-footer p-2 text-muted">Selesai: {{ $drCounts['SELESAI'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning"><i class="fas fa-boxes"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Sparepart Low Stock</h4></div>
                            <div class="card-body">{{ $lowStockCount }}</div>
                            <div class="card-footer p-2 text-muted">Total Item: {{ $totalSparepart }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info"><i class="fas fa-tasks"></i></div>
                        <div class="card-wrap">
                            <div class="card-header"><h4>Permintaan & Perbaikan</h4></div>
                            <div class="card-body">{{ $pendingRequests }}</div>
                            <div class="card-footer p-2 text-muted">Perbaikan aktif: {{ $ongoingRepairs }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4>Kerusakan Terbaru</h4></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead><tr><th>Tanggal</th><th>Ranpur</th><th>Judul</th><th>Status</th></tr></thead>
                                    <tbody>
                                        @forelse ($recentDamage as $row)
                                            <tr>
                                                <td>{{ $row->tanggal->format('d M Y') }}</td>
                                                <td>{{ $row->vehicle->nomor_lambung }}</td>
                                                <td>{{ $row->judul }}</td>
                                                <td><span class="badge badge-{{ $row->status==='SELESAI' ? 'success' : 'secondary' }}">{{ $row->status }}</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4>Pemeliharaan Terbaru</h4></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead><tr><th>Tanggal</th><th>Ranpur</th><th>Kondisi</th><th>Temuan</th></tr></thead>
                                    <tbody>
                                        @forelse ($recentMaint as $row)
                                            <tr>
                                                <td>{{ $row->tanggal->format('d M Y') }}</td>
                                                <td>{{ $row->vehicle->nomor_lambung }}</td>
                                                <td><span class="badge badge-{{ $row->cond_overall==='BAIK' ? 'success' : ($row->cond_overall==='CUKUP' ? 'warning':'danger') }}">{{ $row->cond_overall }}</span></td>
                                                <td>{!! $row->ada_temuan_kerusakan ? '<span class="badge badge-danger">Ada</span>' : '<span class="badge badge-success">Tidak</span>' !!}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-muted">Temuan 30 hari: {{ $temuan30 }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4>Sparepart Low Stock</h4></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead><tr><th>Sparepart</th><th>Stok</th><th>Minimum</th><th>Lokasi</th></tr></thead>
                                    <tbody>
                                        @forelse ($lowStockList as $sp)
                                            <tr>
                                                <td>{{ $sp->kode }} — {{ $sp->nama }}</td>
                                                <td>{{ $sp->stok }}</td>
                                                <td>{{ $sp->stok_minimum }}</td>
                                                <td>{{ $sp->location?->kode ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center">Aman</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header"><h4>Pergerakan Stok Terakhir</h4></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead><tr><th>Tanggal</th><th>Sparepart</th><th>Jenis</th><th>Qty</th></tr></thead>
                                    <tbody>
                                        @forelse ($recentMoves as $mv)
                                            <tr>
                                                <td>{{ $mv->tanggal->format('d M Y H:i') }}</td>
                                                <td>{{ $mv->sparepart->kode }} — {{ $mv->sparepart->nama }}</td>
                                                <td><span class="badge badge-{{ $mv->jenis==='IN' ? 'success' : 'warning' }}">{{ $mv->jenis }}</span></td>
                                                <td>{{ $mv->qty }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center">Tidak ada data</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h4>Permintaan Sparepart Terbaru</h4></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead><tr><th>Tanggal</th><th>Ranpur</th><th>Kerusakan</th><th>Peminta</th><th>Status</th></tr></thead>
                            <tbody>
                                @forelse ($recentRequests as $rq)
                                    <tr>
                                        <td>{{ $rq->tanggal->format('d M Y') }}</td>
                                        <td>{{ $rq->diagnosis->damageReport->vehicle->nomor_lambung }}</td>
                                        <td>{{ $rq->diagnosis->damageReport->judul }}</td>
                                        <td>{{ $rq->requester->name }}</td>
                                        <td><span class="badge badge-secondary">{{ $rq->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('customScript')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const damageStatus = @json($damageStatusChart);
        const vehicleReady = @json($vehicleReadyChart);
        const maintenanceMonthly = @json($maintenanceMonthly);
        const stockMovementsDaily = @json($stockMovementsDaily);
        const requestStatus = @json($requestStatusChart);

        const palette = ['#6777ef', '#fc544b', '#ffa426', '#63ed7a', '#3abaf4', '#191d21'];

        // Pie/Doughnut helpers
        function doughnut(id, labels, data, colors) {
            new Chart(document.getElementById(id), {
                type: 'doughnut',
                data: { labels, datasets: [{ data, backgroundColor: colors}]},
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        }

        // Line / Bar helpers
        function line(id, labels, data, color) {
            new Chart(document.getElementById(id), {
                type: 'line',
                data: { labels, datasets: [{ data, label: 'Jumlah', fill: false, borderColor: color, tension: .3 }]},
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });
        }
        function stackedBar(id, labels, inData, outData) {
            new Chart(document.getElementById(id), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        { label: 'IN', backgroundColor: palette[3], data: inData },
                        { label: 'OUT', backgroundColor: palette[1], data: outData },
                    ]
                },
                options: { responsive: true, scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } } }
            });
        }

        // Render charts
        doughnut('chartDamageStatus', damageStatus.labels, damageStatus.data, [palette[1], palette[2], palette[0], palette[3]]);
        doughnut('chartVehicleReady', vehicleReady.labels, vehicleReady.data, [palette[3], palette[1]]);
        line('chartMaintenanceMonthly', maintenanceMonthly.labels, maintenanceMonthly.data, palette[0]);
        stackedBar('chartStockMovements', stockMovementsDaily.labels, stockMovementsDaily.in, stockMovementsDaily.out);
        doughnut('chartRequestStatus', requestStatus.labels, requestStatus.data, [palette[0], palette[2], palette[3], palette[1], palette[4]]);
    </script>
@endpush
