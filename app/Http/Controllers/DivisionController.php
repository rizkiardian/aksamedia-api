<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::when($request->name, function($query, $name) {
            return $query->where('name', 'like', "%$name%");
        })->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data divisi berhasil diambil',
            'data' => [
                'divisions' => $divisions->items()
            ],
            'pagination' => [
                'current_page' => $divisions->currentPage(),
                'total_pages' => $divisions->lastPage(),
                'total_items' => $divisions->total(),
            ]
        ]);
    }
}
