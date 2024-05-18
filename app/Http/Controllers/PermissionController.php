<?php

namespace App\Http\Controllers;

use App\Helpers\SendNotificationHelper;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->input('search');

        $permissions = Permission::with('user')
            ->when($name, function ($query, $name) {
                $query->whereHas('user', function ($query) use ($name) {
                    $query->where('name', 'like', "%$name%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('pages.permission.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return view('pages.permission.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('pages.permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->is_approved = $request->is_approved;
        $permission->save();

        $str = $request->is_approved == 1 ? 'Disetujui' : 'Ditolak';

        SendNotificationHelper::sendNotificationToUser($request->user()->id, 'Status Izin anda adalah ' . $str);

        return redirect()->route('permissions.index')->with('success', 'Permission Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission Deleted Successfully');
    }
}
