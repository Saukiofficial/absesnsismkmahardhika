<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        // Ambil data libur, urutkan dari yang terbaru
        $holidays = Holiday::orderBy('holiday_date', 'desc')->paginate(10);
        return view('admin.holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'holiday_date' => 'required|date|unique:holidays,holiday_date',
            'description' => 'nullable|string'
        ], [
            'holiday_date.unique' => 'Tanggal ini sudah terdaftar sebagai hari libur.'
        ]);

        Holiday::create($request->all());

        return redirect()->back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Holiday::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Hari libur dihapus.');
    }
}
