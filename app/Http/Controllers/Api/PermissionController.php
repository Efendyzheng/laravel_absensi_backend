<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public function index(Request $request)
    {
        $permissions = Permission::where('user_id', $request->user()->id)
            ->orderByDesc('date')
            ->get();

        return response([
            'message' => 'Success',
            'data' => $permissions
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'reason' => 'required',
        ]);

        $permission = new Permission();
        $permission->user()->associate($request->user()->id);
        $permission->date = $request->date;
        $permission->reason = $request->reason;
        // $permission->is_approved = 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->storeAs('public/permissions/', $imageName);
            $permission->image_url =  $imageName;
        }

        $permission->save();

        return response()->json(['message' => 'Permission Created Successfully'], 201);
    }
}
