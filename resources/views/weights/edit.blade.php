<x-weight>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">แก้ไขข้อมูลน้ำหนัก</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('weights.update', $weight->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="weight" class="form-label">น้ำหนัก (กิโลกรัม)</label>
                            <input type="number" step="0.1" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $weight->weight) }}">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="recorded_at" class="form-label">วันที่บันทึก</label>
                            <input type="date" class="form-control @error('recorded_at') is-invalid @enderror" id="recorded_at" name="recorded_at" value="{{ old('recorded_at', $weight->recorded_at) }}">
                            @error('recorded_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('weights.index') }}" class="btn btn-secondary">ย้อนกลับ</a>
                            <button type="submit" class="btn btn-primary">อัปเดตข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-weight>