<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $this->authorizeByPermission('attendance.view');
        $requests = DB::table('leave_requests as lr')
            ->join('users as u','u.id','=','lr.user_id')
            ->join('leave_policies as p','p.id','=','lr.policy_id')
            ->select('lr.*','u.name as user_name','p.name as policy_name')
            ->orderByDesc('lr.created_at')
            ->limit(200)
            ->get();
        $policies = DB::table('leave_policies')->orderBy('name')->get();
        return view('leave.requests.index', compact('requests','policies'));
    }

    public function store(Request $request)
    {
        $this->authorizeByPermission('attendance.view'); // employees can request if they can view attendance; adjust as needed
        $data = $request->validate([
            'policy_id' => 'required|exists:leave_policies,id',
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
            'reason'    => 'nullable|string|max:1000',
        ]);
        $days = (new \Carbon\Carbon($data['from_date']))->floatDiffInDaysFiltered(function($d){return true;}, new \Carbon\Carbon($data['to_date'])) + 1;
        DB::table('leave_requests')->insert([
            'user_id' => Auth::id(),
            'policy_id' => $data['policy_id'],
            'from_date' => $data['from_date'],
            'to_date'   => $data['to_date'],
            'days'      => $days,
            'status'    => 'pending',
            'reason'    => $data['reason'] ?? null,
            'created_at'=> now(),
            'updated_at'=> now(),
        ]);
        return redirect()->route('leave.requests.index')->with('success','Leave request submitted');
    }

    public function approve($id)
    {
        $this->authorizeByPermission('attendance.manage');
        DB::table('leave_requests')->where('id',$id)->update([
            'status'=>'approved','approver_id'=>Auth::id(),'updated_at'=>now()
        ]);
        return back()->with('success','Request approved');
    }
    public function reject($id)
    {
        $this->authorizeByPermission('attendance.manage');
        DB::table('leave_requests')->where('id',$id)->update([
            'status'=>'rejected','approver_id'=>Auth::id(),'updated_at'=>now()
        ]);
        return back()->with('success','Request rejected');
    }

    // Calendar events JSON for FullCalendar
    public function events()
    {
        $this->authorizeByPermission('attendance.view');
        $events = DB::table('leave_requests as lr')
            ->join('users as u','u.id','=','lr.user_id')
            ->select('lr.id','u.name','lr.from_date','lr.to_date','lr.status')
            ->whereIn('lr.status',['approved','pending'])
            ->get()
            ->map(function($r){
                return [
                    'id'=>$r->id,
                    'title'=> $r->name.' ('.$r->status.')',
                    'start'=>$r->from_date,
                    'end'  => (new \Carbon\Carbon($r->to_date))->addDay()->toDateString(),
                    'color'=> $r->status==='approved' ? '#28a745' : '#ffc107',
                ];
            });
        return response()->json($events);
    }

    private function authorizeByPermission(string $perm): void
    {
        abort_unless(auth()->user()?->can($perm), 403);
    }
}

