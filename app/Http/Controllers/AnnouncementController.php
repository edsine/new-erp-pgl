<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementEmployee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage announcement'))
        {

            if(Auth::user()->type == 'employee')
            {
                $current_employee = Employee::where('user_id', '=', \Auth::user()->id)->first();
                $announcements    = Announcement::orderBy('announcements.id', 'desc')->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')->where('announcement_employees.employee_id', '=', $current_employee->id)->orWhere(
                    function ($q){
                        $q->where('announcements.department_id', '["0"]')->where('announcements.employee_id', '["0"]');
                    }
                )->get();
            }
            else
            {
                $current_employee = Employee::where('user_id', '=', \Auth::user()->id)->first();
                $announcements    = Announcement::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('announcement.index', compact('announcements', 'current_employee'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create announcement'))
        {
            $employees   = Employee::get()->pluck('name', 'id');
            $branch      = Branch::get();
            $departments = Department::get();

            return view('announcement.create', compact('employees', 'branch', 'departments'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create announcement'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'start_date' => 'required',
                                   'end_date' => 'required',
                                   'branch_id' => 'required',
                                   'department_id' => 'required',
                                   'employee_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $announcement                = new Announcement();
            $announcement->title         = $request->title;
            $announcement->start_date    = $request->start_date;
            $announcement->end_date      = $request->end_date;
            $announcement->branch_id     = $request->branch_id;
            $announcement->department_id = json_encode($request->department_id);
            $announcement->employee_id   = json_encode($request->employee_id);
            $announcement->description   = $request->description;
            $announcement->created_by    = \Auth::user()->creatorId();
            $announcement->save();

            if(in_array('0', $request->employee_id))
            {
                $departmentEmployee = Employee::whereIn('department_id', $request->department_id)->get()->pluck('id');
                $departmentEmployee = $departmentEmployee;

            }
            else
            {
                $departmentEmployee = $request->employee_id;
            }
            foreach($departmentEmployee as $employee)
            {
                $announcementEmployee                  = new AnnouncementEmployee();
                $announcementEmployee->announcement_id = $announcement->id;
                $announcementEmployee->employee_id     = $employee;
                $announcementEmployee->created_by      = \Auth::user()->creatorId();
                $announcementEmployee->save();
            }


            //Slack Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            if(isset($setting['announcement_notification']) && $setting['announcement_notification'] ==1){
                $msg = $request->title .' '.__("announcement created for branch").' '. $branch->name.' '. __("from").' '. $request->start_date. ' '.__("to").' '.$request->end_date.'.';
                Utility::send_slack_msg($msg);
            }

            //Telegram Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            $branch = Branch::find($request->branch_id);
            if(isset($setting['telegram_announcement_notification']) && $setting['telegram_announcement_notification'] ==1){
                $msg = $request->title .' '.__("announcement created for branch").' '. $branch->name.' '. __("from").' '. $request->start_date. ' '.__("to").' '.$request->end_date.'.';
                Utility::send_telegram_msg($msg);
            }


            return redirect()->route('announcement.index')->with('success', __('Announcement  successfully created.'));
        }

        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Announcement $announcement)
    {
        return redirect()->route('announcement.index');
    }

    public function edit($announcement)
    {
        if(\Auth::user()->can('edit announcement'))
        {
            $announcement = Announcement::find($announcement);
            if($announcement->created_by == Auth::user()->creatorId())
            {
                $branch      = Branch::get()->pluck('name', 'id');
                $departments = Department::get()->pluck('name', 'id');

                return view('announcement.edit', compact('announcement', 'branch', 'departments'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Announcement $announcement)
    {
        if(\Auth::user()->can('edit announcement'))
        {
            if($announcement->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'title' => 'required',
                                       'start_date' => 'required',
                                       'end_date' => 'required',
                                       'branch_id' => 'required',
                                       'department_id' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $announcement->title         = $request->title;
                $announcement->start_date    = $request->start_date;
                $announcement->end_date      = $request->end_date;
                $announcement->branch_id     = $request->branch_id;
                $announcement->department_id = $request->department_id;
                $announcement->description   = $request->description;
                $announcement->save();

                return redirect()->route('announcement.index')->with('success', __('Announcement successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Announcement $announcement)
    {
        if(\Auth::user()->can('delete announcement'))
        {
            if($announcement->created_by == \Auth::user()->creatorId())
            {
                $announcement->delete();

                return redirect()->route('announcement.index')->with('success', __('Announcement successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function getdepartment(Request $request)
    {

        if($request->branch_id == 0)
        {
            $departments = Department::get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $departments = Department::get()->pluck('name', 'id')->toArray();
        }

        return response()->json($departments);
    }

    public function getemployee(Request $request)
    {
        // dd(department_id);
        if(!$request->department_id )
        {
            $employees = Employee::get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $employees = Employee::where('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();
        }

        return response()->json($employees);
    }
}
