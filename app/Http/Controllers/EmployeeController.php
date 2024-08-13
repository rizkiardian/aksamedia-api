<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with('division')
            ->when($request->name, function ($query, $name) {
                return $query->where('name', 'like', "%$name%");
            })
            ->when($request->division_id, function ($query, $division_id) {
                return $query->where('division_id', $division_id);
            })
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diambil',
            'data' => [
                'employees' => $employees->items()
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'total_pages' => $employees->lastPage(),
                'total_items' => $employees->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'division_id' => 'required|uuid',
            'position' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $imagePath = $image ? $image->store('employee_images', 'public') : null;

        $employee = Employee::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'phone' => $request->phone,
            'division_id' => $request->division_id,
            'position' => $request->position,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil dibuat',
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'division_id' => 'required|uuid',
            'position' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        if ($image) {
            // Hapus gambar lama
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $employee->image = $image->store('employee_images', 'public');
        }

        $employee->update($request->only('name', 'phone', 'division_id', 'position'));

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diupdate',
        ]);
    }

    public function destroy(Employee $employee)
    {
        // Hapus gambar jika ada
        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }

        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil dihapus',
        ]);
    }
}
