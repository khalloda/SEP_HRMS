<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionsAdminController extends Controller
{
    private function authorizeAdmin(): void
    {
        $u = Auth::user();
        if (!$u || !$u->hasAnyRole(['IT_Admin','HR_Admin_Manager'])) abort(403);
    }

    public function index()
    {
        $this->authorizeAdmin();
        $perms = Permission::orderBy('name')->get();
        return view('admin.permissions.index', compact('perms'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $data = $request->validate([
            'name' => ['required','string','max:191', Rule::unique('permissions','name')],
        ]);
        Permission::create(['name'=>$data['name']]);
        return redirect()->route('admin.permissions.index')->with('success','Permission created');
    }

    public function destroy(Permission $permission)
    {
        $this->authorizeAdmin();
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success','Permission deleted');
    }
}

