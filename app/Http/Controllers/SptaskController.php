<?php

namespace App\Http\Controllers;

use App\Models\Sptask;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Sptaskprogress;
use App\Models\Sptaskusers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SptaskController extends Controller
{
    public function index($view = 'grid')
    {
        $theuser=auth()->user();

        $employee = Employee::get();
        $employee->prepend('Select Employee','');
        $dept = Department::get();
        $dept->prepend('Select Department','');

        //$tasks = Sptask::with('sptaskuser')->where('user_id', $theuser->id)->get();
        // Get the currently authenticated user’s employee record
$single_employee = Employee::where('user_id', auth()->user()->id)->first();

// Initialize an empty collection to handle cases where $single_employee is null
$tasks = collect();

// Check if the employee record exists
if ($single_employee) {
    // Retrieve tasks associated with the employee or tasks where user_id is 0
    $tasks = Sptask::with('sptaskuser')
        ->whereHas('sptaskuser', function ($query) use ($single_employee) {
            $query->where('user_id', $single_employee->id)
                  ->orWhere('department_id', $single_employee->department_id);
        })
        ->get();
} else {
    // Handle the case where no employee record is found if needed
    // For example, log an error or provide a message to the user
    // Log::error('Employee record not found for user ID: ' . auth()->user()->id);
    return redirect()->back()->with('error', 'A task can be viewed by an employee only.');
}

        // dd($tasks);


        return view('sptask.index', compact('view', 'employee', 'dept','tasks'));



    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
            ]

        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'error somewhere o, you did not fill something');
        }
        $employee = Employee::where('user_id', '=', auth()->user()->id)->first();

        if($employee){

        $sptask = new Sptask();
        $sptask->title = $request->title;
        $sptask->description = $request->description;
        $sptask->start_date = $request->start_date;
        $sptask->end_date = $request->end_date;
        $sptask->tags = $request->tags;
        $sptask->created_by = $request->created_by;

        $sptask->save();

        

        if($request->assign == "1"){
            $user = $employee->id;
            $department = $employee->department_id;
        }else if($request->assign == "2"){
            $user = $request->user_id;
            $department = $request->department_id;
        }else{
            $user = "0";
            $department = $request->department_id;
        }
        
        

            Sptaskusers::create([
                'sptask_id' => $sptask->id,
                'user_id' => $user,
                'department_id' => $department
            ]);

        return redirect()->route('sptask.index')->with('success', 'Task created successfully');
        }else{
            return redirect()->back()->with('error', 'A task can be created by an employee only.');
        }
    }


    public function addnewuser($id){
        $data= Sptask::findOrFail($id);
        dd($data->user->name);
    }

    public function show($id){

        $tasks= Sptask::findOrFail($id);
        // $progress= [
        //     1=>'IN PROGRESS',
        //     2=>'TO DO',
        //     3=>'DONE'
        // ];
        $progress=Sptaskprogress::get()->pluck('name','id');
        $progress->prepend('Select the Current Status','');

        return view('sptask.updatetask',compact('tasks','progress'));
        // return view('sptask.showtask',compact('tasks'));
    }

    public function update(Request $request,$id){
      $task=  Sptask::findOrFail($id);
      $task->update($request->all());

      return redirect()->back()->with('success','Updated Successfully');


    }
    public function destroy($id){

        $data= Sptask::findOrFail($id);
        $data->delete();

        return redirect()->route('sptask.index')->with('success', 'done sucessful');

    }
}
