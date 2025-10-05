<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class LeavePolicyController extends Controller
{
    public function index()
    {
        $this->authorizeByPermission('attendance.view');
        $policies = DB::table('leave_policies')->orderBy('name')->get();
        // Current user's balances summary for display
        $balances = app(\App\Http\Controllers\LeaveRequestController::class)
            ->callAction('computeBalancesForUser', [auth()->id(), (int)date('Y')]);
        return view('leave.policies.index', compact('policies','balances'));
    }

    public function create()
    {
        $this->authorizeByPermission('attendance.manage');
        return view('leave.policies.form', ['mode'=>'create','policy'=>null]);
    }

    public function store(Request $request)
    {
        $this->authorizeByPermission('attendance.manage');
        $data = $request->validate([
            'code'=>['required','string','max:50',Rule::unique('leave_policies','code')],
            'name'=>['required','string','max:191'],
            'accrual_rule'=>['required','in:fixed,monthly,yearly'],
            'days_per_year'=>['required','numeric','min:0'],
            'carry_over'=>['boolean'],
            'max_carry_over'=>['nullable','numeric','min:0']
        ]);
        $data['carry_over'] = (bool)($data['carry_over'] ?? false);
        DB::table('leave_policies')->insert(array_merge($data,[
            'created_at'=>now(),'updated_at'=>now()
        ]));
        return redirect()->route('leave.policies.index')->with('success','Policy created');
    }

    public function edit($id)
    {
        $this->authorizeByPermission('attendance.manage');
        $policy = DB::table('leave_policies')->where('id',$id)->first();
        abort_unless($policy,404);
        return view('leave.policies.form', ['mode'=>'edit','policy'=>$policy]);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeByPermission('attendance.manage');
        $data = $request->validate([
            'code'=>['required','string','max:50',Rule::unique('leave_policies','code')->ignore($id)],
            'name'=>['required','string','max:191'],
            'accrual_rule'=>['required','in:fixed,monthly,yearly'],
            'days_per_year'=>['required','numeric','min:0'],
            'carry_over'=>['boolean'],
            'max_carry_over'=>['nullable','numeric','min:0']
        ]);
        $data['carry_over'] = (bool)($data['carry_over'] ?? false);
        DB::table('leave_policies')->where('id',$id)->update(array_merge($data,[
            'updated_at'=>now()
        ]));
        return redirect()->route('leave.policies.index')->with('success','Policy updated');
    }

    public function destroy($id)
    {
        $this->authorizeByPermission('attendance.manage');
        DB::table('leave_policies')->where('id',$id)->delete();
        return redirect()->route('leave.policies.index')->with('success','Policy deleted');
    }

    private function authorizeByPermission(string $perm): void
    {
        abort_unless(auth()->user()?->can($perm), 403);
    }
}
