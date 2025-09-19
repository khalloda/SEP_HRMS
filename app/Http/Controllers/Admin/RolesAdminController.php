<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAdminController extends Controller
{
    private function authorizeAdmin(): void
    {
        $u = Auth::user();
        if (!$u || !$u->hasAnyRole(['IT_Admin','HR_Admin_Manager'])) abort(403);
    }

    public function index()
    {
        $this->authorizeAdmin();
        $roles = Role::query()->orderBy('name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        $perms = Permission::orderBy('name')->get();
        return view('admin.roles.form', ['mode'=>'create','role'=>null,'perms'=>$perms,'assigned'=>[]]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $data = $request->validate([
            'name' => ['required','string','max:191', Rule::unique('roles','name')],
            'permissions' => ['array'],
        ]);
        $role = Role::create(['name'=>$data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);
        return redirect()->route('admin.roles.index')->with('success','Role created');
    }

    public function edit(Role $role)
    {
        $this->authorizeAdmin();
        $perms = Permission::orderBy('name')->get();
        $assigned = $role->permissions()->pluck('name')->toArray();
        return view('admin.roles.form', ['mode'=>'edit','role'=>$role,'perms'=>$perms,'assigned'=>$assigned]);
    }

    public function update(Request $request, Role $role)
    {
        $this->authorizeAdmin();
        $data = $request->validate([
            'name' => ['required','string','max:191', Rule::unique('roles','name')->ignore($role->id)],
            'permissions' => ['array'],
        ]);
        $role->name = $data['name'];
        $role->save();
        $role->syncPermissions($data['permissions'] ?? []);
        return redirect()->route('admin.roles.index')->with('success','Role updated');
    }

    public function destroy(Role $role)
    {
        $this->authorizeAdmin();
        if (in_array($role->name, ['IT_Admin','HR_Admin_Manager'])) {
            return redirect()->route('admin.roles.index')->with('error','This core role cannot be deleted.');
        }
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success','Role deleted');
    }
}

