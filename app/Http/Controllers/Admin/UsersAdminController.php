<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

class UsersAdminController extends Controller
{
    public function index()
    {
        // Gate: only specific roles may view user management
        $user = Auth::user();
        if (!$user || !$user->hasAnyRole(['IT_Admin','HR_Admin_Manager'])) {
            abort(403);
        }

        $users = DB::table('users')->select('id','name','email','created_at')->orderBy('id')->get();

        // Try to load role names if spatie tables exist
        $roles = [];
        if (DB::getSchemaBuilder()->hasTable('model_has_roles') && DB::getSchemaBuilder()->hasTable('roles')) {
            $mr = DB::table('model_has_roles as mr')
                ->join('roles as r', 'r.id', '=', 'mr.role_id')
                ->where('mr.model_type', '=', config('auth.providers.users.model'))
                ->select('mr.model_id as user_id','r.name as role')
                ->get();
            foreach ($mr as $row) { $roles[$row->user_id][] = $row->role; }
        }

        return view('admin.users.index', [
            'users' => $users,
            'rolesMap' => $roles,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyRole(['IT_Admin','HR_Admin_Manager'])) abort(403);
        $allRoles = DB::table('roles')->pluck('name');
        return view('admin.users.form', ['mode'=>'create','user'=>null,'allRoles'=>$allRoles,'assigned'=>[]]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasAnyRole(['IT_Admin','HR_Admin_Manager'])) abort(403);
        $data = $request->validate([
            'name' => ['required','string','max:191'],
            'email' => ['required','email','max:191', Rule::unique('users','email')],
            'password' => ['required','string','min:8','confirmed'],
            'roles' => ['array'],
        ]);
        $u = new User();
        $u->name = $data['name'];
        $u->email = $data['email'];
        $u->password = bcrypt($data['password']);
        $u->save();
        // Assign roles if Spatie tables present
        if (DB::getSchemaBuilder()->hasTable('roles')) {
            $u->syncRoles($data['roles'] ?? []);
        }
        return redirect()->route('admin.users.index')->with('success','User created');
    }

    public function edit(User $user)
    {
        $me = Auth::user();
        if (!$me || !$me->hasAnyRole(['IT_Admin','HR_Admin_Manager'])) abort(403);
        $allRoles = DB::getSchemaBuilder()->hasTable('roles') ? DB::table('roles')->pluck('name') : collect();
        $assigned = $user->roles()->pluck('name')->toArray();
        return view('admin.users.form', ['mode'=>'edit','user'=>$user,'allRoles'=>$allRoles,'assigned'=>$assigned]);
    }

    public function update(Request $request, User $user)
    {
        $me = Auth::user();
        if (!$me || !$me->hasAnyRole(['IT_Admin','HR_Admin_Manager'])) abort(403);
        $data = $request->validate([
            'name' => ['required','string','max:191'],
            'email' => ['required','email','max:191', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:8','confirmed'],
            'roles' => ['array'],
        ]);
        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();
        if (DB::getSchemaBuilder()->hasTable('roles')) {
            $user->syncRoles($data['roles'] ?? []);
        }
        return redirect()->route('admin.users.index')->with('success','User updated');
    }

    public function destroy(User $user)
    {
        $me = Auth::user();
        if (!$me || !$me->hasAnyRole(['IT_Admin','HR_Admin_Manager'])) abort(403);
        if ($me->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error','You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success','User deleted');
    }
}
