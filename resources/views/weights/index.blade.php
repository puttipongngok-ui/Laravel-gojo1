<x-weight>
    <div class="row mb-4">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- ฟอร์มเพิ่มข้อมูล -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">บันทึกน้ำหนัก</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('weights.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="weight" class="form-label">น้ำหนัก (กิโลกรัม)</label>
                            <input type="number" step="0.1" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="recorded_at" class="form-label">วันที่บันทึก</label>
                            <input type="date" class="form-control @error('recorded_at') is-invalid @enderror" id="recorded_at" name="recorded_at" value="{{ old('recorded_at', date('Y-m-d')) }}">
                            @error('recorded_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">บันทึกข้อมูล</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- แสดง Google Chart -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">กราฟการเปลี่ยนแปลงน้ำหนัก</h5>
                </div>
                <div class="card-body">
                    <div id="curve_chart" style="width: 100%; height: 300px"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ตารางแสดงข้อมูล -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-dark text-white">
            <h5 class="card-title mb-0">ประวัติการบันทึก</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>วันที่</th>
                        <th>น้ำหนัก (กก.)</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->recorded_at)->format('d/m/Y') }}</td>
                            <td><span class="badge bg-primary fs-6">{{ $log->weight }} kg</span></td>
                            <td class="text-center">
                                <a href="{{ route('weights.edit', $log->id) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                                <form action="{{ route('weights.destroy', $log->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบข้อมูล?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">ยังไม่มีข้อมูลน้ำหนัก</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

   @push('scripts')
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var chartElement = document.getElementById('curve_chart');
        if (!chartElement) return;

        // ดึงข้อมูลจาก attribute data-chart
        var rawData = JSON.parse(chartElement.dataset.chart || '[]');

        // ป้องกัน Error กรณีที่ยังไม่มีข้อมูลบันทึก (มีแค่หัวตาราง 1 แถว)
        if (rawData.length <= 1) {
            chartElement.innerHTML = '<div class="text-center text-muted py-5">ยังไม่มีข้อมูลสำหรับแสดงผลกราฟ (กรุณาเพิ่มข้อมูลน้ำหนัก)</div>';
            return;
        }

        var data = google.visualization.arrayToDataTable(rawData);

        var options = {
            title: 'แนวโน้มน้ำหนักตัว',
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: { title: 'วันที่' },
            vAxis: { title: 'น้ำหนัก (กก.)' },
            colors: ['#0d6efd']
        };

        var chart = new google.visualization.LineChart(chartElement);
        chart.draw(data, options);
    }
</script>
@endpush
</x-weight>