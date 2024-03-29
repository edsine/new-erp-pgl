<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\Department;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StaffLeaveController extends Controller
{
    public function index(Request $request)
    {
        $employee      = Employee::where('user_id', \Auth::user()->id)->first();

        // if (\Auth::user()->type == 'company' || \Auth::user()->type == 'admin') {
        //     $query = Leave::orderBy('created_at', 'DESC');
        // } else {
        //     if (isset($employee->department)) {
        //         $query = Leave::where('department_id', '=', $employee->department->id);
        //     } else {
        //         $query = Leave::where('created_by', '=', \Auth::user()->id);
        //     }
        // }

        $query = Leave::where('created_by', '=', \Auth::user()->id);

        if (isset($employee)) {
            $query->orWhere('employee_id', $employee->id);
        }

        $staff_leaves = $query->get();

        return view('staffleave.index', compact('staff_leaves'));
    }

    public function approval(Request $request)
    {
        if (\Auth::user()->can('manage leave')) {
            $employee      = Employee::where('user_id', \Auth::user()->id)->first();
            $query = Leave::query();

            if (isset($employee)) {
                $department = $employee->department;

                if (\Auth::user()->can('manage hod approval')) {
                    if (isset($department)) {
                        $query = Leave::where('department_id', '=', $employee->department->id);
                    } else {
                        return redirect()->back()->with('error', __('Contact Administrator to complete HRM setup.'));
                    }
                } else if (\Auth::user()->can('manage admin approval')) {
                    $query = Leave::where('hod_approval', 'Approved');
                } else if (\Auth::user()->can('manage chairman approval')) {
                    $query = Leave::where('admin_approval', 'Approved');
                }
            }

            $leaves = $query->get();
            return view('staffleave.approval', compact('leaves'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function create()
    {
        $employee      = Employee::where('user_id', \Auth::user()->id)->first();
        $employees = Employee::pluck('name', 'id');
        $leavetypes = LeaveType::all();

        return view('staffleave.create', compact('employee', 'employees', 'leavetypes'));
    }

    public function store(Request $request)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'leave_type_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'reliever_id' => 'required',
                'leave_reason' => 'required',

            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $employee      = Employee::where('user_id', \Auth::user()->id)->first();

        $leave                 = new Leave();
        if (!isset($employee)) {
            $employee = Employee::where('id', $request->employee_id)->first();
        }

        if (!isset($employee)) {
            return redirect()->route('staffleave.index')->with('error', __('Employee not found.'));
        }

        $leave->employee_id    = $employee->id;
        $leave->department_id = $employee->department ? $employee->department->id : 0;

        $leave->leave_type_id    = $request->leave_type_id;
        $leave->reliever_id      = $request->reliever_id;
        $leave->applied_on       = date('Y-m-d');
        $leave->start_date       = $request->start_date;
        $leave->end_date         = $request->end_date;
        $leave->total_leave_days = 0;

        $leave->status         = 'Awaiting HOD Approval';
        $leave->hod_approval = 'Pending';
        $leave->admin_approval = 'Pending';
        $leave->chairman_approval = 'Pending';

        $leave->leave_reason    = $request->leave_reason;
        $leave->created_by       = \Auth::user()->id;


        // Check if number of days correspond to leave type days
        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);

        $total_leave_days = 0;

        // Iterate through each day between start and end dates
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

        foreach ($period as $day) {
            // Check if the day is not Saturday (6) or Sunday (0)
            if ($day->format('N') < 6) {
                $total_leave_days++;
            }
        }

        $leave_type = LeaveType::findorFail($request->leave_type_id);
        $leave_type_days = $leave_type->days;

        if ($total_leave_days > $leave_type_days) {
            return redirect()->back()->with('error', 'Number of days selected should not be greater than leave type days.');
        }

        $leave->save();



        return redirect()->route('staff_leave.index')->with('success', __('Leave successfully created.'));
    }

    public function edit(Request $request, $id)
    {

        $employee      = Employee::where('user_id', \Auth::user()->id)->first();
        $leave = Leave::find($id);

        if (\Auth::user()->can('manage hod approval')) {
            if ($leave->admin_approval == "Approved") {
                return redirect()->back()->with('error', __('Cannot edit leave request.'));
            }
        } else if (\Auth::user()->can('manage admin approval')) {
            if ($leave->chairman_approval == "Approved") {
                return redirect()->back()->with('error', __('Cannot edit leave request.'));
            }
        } else if (!\Auth::user()->can('manage hod approval') && !\Auth::user()->can('manage admin approval') && !\Auth::user()->can('manage chairman approval')) {
            if ($leave->hod_approval == "Approved") {
                return redirect()->back()->with('error', __('Cannot edit leave request.'));
            }
        }



        $employees = Employee::pluck('name', 'id');
        $leavetypes = LeaveType::pluck('title', 'id');

        return view('staffleave.edit', compact('leave', 'employee', 'employees', 'leavetypes'));
    }

    public function update(Request $request, $id)
    {
        $leave = Leave::find($id);


        $validator = \Validator::make(
            $request->all(),
            [
                'leave_type_id' => 'required',
                'start_date' => 'required',
                'reliever_id' => 'required',
                'end_date' => 'required',
                'leave_reason' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $employee      = Employee::where('user_id', \Auth::user()->id)->first();

        if (!isset($employee)) {
            $employee = Employee::where('id', $request->employee_id)->first();
        }

        if (!isset($employee)) {
            return redirect()->route('staffleave.index')->with('error', __('Employee not found.'));
        }

        $leave->employee_id    = $employee->id;
        $leave->department_id = $employee->department ? $employee->department->id : 0;

        $leave->leave_type_id    = $request->leave_type_id;
        $leave->reliever_id      = $request->reliever_id;
        $leave->applied_on       = date('Y-m-d');
        $leave->start_date       = $request->start_date;
        $leave->end_date         = $request->end_date;

        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);

        $total_leave_days = 0;

        // Iterate through each day between start and end dates
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

        foreach ($period as $day) {
            // Check if the day is not Saturday (6) or Sunday (0)
            if ($day->format('N') < 6) {
                $total_leave_days++;
            }
        }

        $leave->total_leave_days = $total_leave_days;

        $leave->leave_reason    = $request->leave_reason;
        $leave->created_by       = \Auth::user()->id;


        // Check if number of days correspond to leave type days
        $leave_type = LeaveType::findorFail($request->leave_type_id);
        $leave_type_days = $leave_type->days;

        if ($total_leave_days > $leave_type_days) {
            return redirect()->back()->with('error', 'Number of days selected should not be greater than leave type days.');
        }

        $leave->save();

        return redirect()->route('staff_leave.index')->with('success', __('Leave request successfully updated.'));
    }

    public function destroy($id)
    {
        $leave = Leave::find($id);

        if (\Auth::user()->can('manage hod approval')) {
            if ($leave->admin_approval == "Approved") {
                return redirect()->back()->with('error', __('Cannot delete leave request.'));
            }
        } else if (\Auth::user()->can('manage admin approval')) {
            if ($leave->chairman_approval == "Approved") {
                return redirect()->back()->with('error', __('Cannot delete leave request.'));
            }
        } else if (!\Auth::user()->can('manage hod approval') && !\Auth::user()->can('manage admin approval') && !\Auth::user()->can('manage chairman approval')) {
            if ($leave->hod_approval == "Approved") {
                return redirect()->back()->with('error', __('Cannot delete leave request.'));
            }
        }

        $leave = Leave::find($id);

        $leave->delete();

        return redirect()->route('staff_leave.index')->with('success', __('Leave successfully deleted.'));
    }

    public function view($id)
    {
        $leave     = leave::find($id);
        $employee  = Employee::find($leave->employee_id);
        $reliever  = Employee::find($leave->reliever_id);

        $leavetypes = LeaveType::all();

        return view('staffleave.view', compact('leave', 'employee', 'reliever', 'leavetypes'));
    }

    public function action($id)
    {
        if (\Auth::user()->can('manage leave')) {
            $leave     = Leave::find($id);
            $employee  = Employee::find($leave->employee_id);
            $reliever  = Employee::find($leave->reliever_id);

            $leavetypes = LeaveType::all();

            return view('staffleave.action', compact('leave', 'employee', 'reliever', 'leavetypes'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function changeaction(Request $request)
    {

        $leave = Leave::find($request->leave_id);

        $leave->hod_approval = $request->hod_approval;
        $leave->admin_approval = $request->admin_approval;
        $leave->chairman_approval = $request->chairman_approval;


        if ($leave->hod_approval == 'Approved') {
            $leave->status = 'Awaiting Admin Approval';
            $leave->hod_remark = $request->hod_remark;
            $leave->hod_approval           = 'Approved';
            $leave->admin_approval = 'Pending';
            $leave->chairman_approval = 'Pending';
        }

        if ($leave->admin_approval == 'Approved') {
            $leave->status = 'Awaiting Chairman Approval';
            $leave->admin_remark = $request->admin_remark;
            $leave->hod_approval           = 'Approved';
            $leave->admin_approval = 'Approved';
            $leave->chairman_approval = 'Pending';
        }

        if ($leave->chairman_approval == 'Approved') {
            $leave->status = 'Approved';
            $leave->chairman_remark = $request->chairman_remark;

            $leave->chairman_approval = 'Approved';
            $leave->hod_approval           = 'Approved';
            $leave->admin_approval = 'Approved';
        }

        $leave->save();



        return redirect()->route('staff_leave-approval')->with('success', __('Leave Approval successfully updated.'));
    }
}
