<?php

namespace App\Http\Controllers;

use App\Models\WeightLog;
use Illuminate\Http\Request;

class WeightLogController extends Controller
{
    public function index()
    {
        $logs = WeightLog::orderBy('recorded_at', 'asc')->get();

        // แปลงข้อมูลส่งเข้า Google Chart
        $chartData = [["วันที่", "น้ำหนัก (กก.)"]];
        foreach ($logs as $log) {
            $chartData[] = [$log->recorded_at, (float) $log->weight];
        }

        return view('weights.index', compact('logs', 'chartData'));
    }

    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'weight' => 'required|numeric|min:1|max:300',
            'recorded_at' => 'required|date',
        ], [
            'weight.required' => 'กรุณากรอกน้ำหนัก',
            'weight.numeric' => 'น้ำหนักต้องเป็นตัวเลขเท่านั้น',
            'weight.min' => 'น้ำหนักต้องมากกว่า 0',
            'recorded_at.required' => 'กรุณาระบุวันที่บันทึก',
            'recorded_at.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
        ]);

        WeightLog::create($validated);

        return redirect()->route('weights.index')->with('success', 'บันทึกข้อมูลน้ำหนักเรียบร้อยแล้ว');
    }

    public function edit(WeightLog $weight)
    {
        return view('weights.edit', compact('weight'));
    }

    public function update(Request $request, WeightLog $weight)
    {
        // Validation
        $validated = $request->validate([
            'weight' => 'required|numeric|min:1|max:300',
            'recorded_at' => 'required|date',
        ], [
            'weight.required' => 'กรุณากรอกน้ำหนัก',
            'weight.numeric' => 'น้ำหนักต้องเป็นตัวเลขเท่านั้น',
            'recorded_at.required' => 'กรุณาระบุวันที่บันทึก',
        ]);

        $weight->update($validated);

        return redirect()->route('weights.index')->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }

    public function destroy(WeightLog $weight)
    {
        $weight->delete();
        return redirect()->route('weights.index')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
}