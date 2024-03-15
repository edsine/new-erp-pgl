<?php

namespace App\Http\Controllers;

use App\Models\Sptask;
use App\Models\User;
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

        $user = User::get();
        $user->prepend('Select User','');
        $dept = Department::get();
        $dept->prepend('Select Department','');
        // $tasks=Sptask::join('sptaskusers','id','=','sptask_id')

        // ->get()

        // ->where('created_by',$user)

        // ;
        $tasks = Sptask::with('sptaskuser')->get();
        // $tasks= \DB::table('sptasks as tk')
        // ->join('sptaskusers as u','tk.id','u.sptask_id')
        // ->where('created_by',$theuser->id)
        // ->orWhere('user_id',$theuser->id)
        // ->get();
        // dd($tasks);


        // if (\Auth::user()->can('manage project')) {
        return view('sptask.index', compact('view', 'user', 'dept','tasks'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }


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

        $sptask = new Sptask();
        $sptask->title = $request->title;
        $sptask->description = $request->description;
        $sptask->start_date = $request->start_date;
        $sptask->end_date = $request->end_date;
        $sptask->tags = $request->tags;
        $sptask->created_by = $request->created_by;

        $sptask->save();

        $user = $request->user_id;
        $department = $request->department_id;

            Sptaskusers::create([
                'sptask_id' => $sptask->id,
                'user_id' => $user,
                'department_id' => $department
            ]);

        return redirect()->route('sptask.index')->with('success', 'done sucessful');
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
