<?php

namespace App\Http\Controllers;

use App\Models\Broadcast;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BroadcastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');

        $broadcasts = Broadcast::when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('broadcasts.description', 'like', "%{$search}%")
                    ->orWhereExists(function ($query) use ($search) {
                        $query->select(DB::raw(1))
                            ->from('departments')
                            ->whereRaw('FIND_IN_SET(departments.id, broadcasts.department)')
                            ->where('departments.name', 'like', "%{$search}%");
                    });
            });
        })
            ->orderByDesc('id')
            ->paginate(10);

        return view('pages.broadcasts.index', compact('broadcasts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('pages.broadcasts.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $department_str = is_array($request->department) ? implode(',', $request->department) : null;

        Broadcast::create([
            'description' => $request->description,
            'department' => $department_str,
        ]);

        return redirect()->route('broadcasts.index')->with('success', 'Broadcast Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Broadcast $broadcast)
    {
        $departments = Department::all();

        $broadcastDepartments = $broadcast->department ? explode(',', $broadcast->department) : [];


        return view('pages.broadcasts.edit', compact('broadcast', 'departments', 'broadcastDepartments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Broadcast $broadcast)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $department_str = is_array($request->department) ? implode(',', $request->department) : null;

        $broadcast->update([
            'description' => $request->description,
            'department' => $department_str,
        ]);


        return redirect()->route('broadcasts.index')->with('success', 'Broadcast Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Broadcast $broadcast)
    {
        $broadcast->delete();
        return redirect()->route('broadcasts.index')->with('success', 'Broadcast Deleted Successfully');
    }
}
