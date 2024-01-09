<?php

namespace App\Http\Controllers;

use App\Imports\EmployeesImport;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RequisitionController extends Controller
{
    public function index(Request $request)
    {
        $employee      = Employee::where('user_id', \Auth::user()->id)->first();

        if(\Auth::user()->type == 'company' || \Auth::user()->type == 'admin')
        {
            $query = Requisition::orderBy('created_at', 'DESC');
        }
        else
        {
            if(isset($employee->department))
            {
                $query = Requisition::where('department_id', '=', $employee->department->id);
            }
            else
            {
                $query = Requisition::where('created_by', '=', \Auth::user()->id);
            }

        }

        $requisitions = $query->get();

        return view('requisition.index', compact('requisitions'));
    }

    public function approval(Request $request)
    {
        if(\Auth::user()->can('manage requisition approval'))
        {
            $employee      = Employee::where('user_id', \Auth::user()->id)->first();

            if(\Auth::user()->type == 'company' || \Auth::user()->type == 'office admin' || \Auth::user()->type == 'chairman' || \Auth::user()->can('manage payment status'))
            {
                $query = Requisition::orderBy('updated_at', 'DESC');
            }
            else
            {
                if(isset($employee->department))
                {
                    $query = Requisition::where('department_id', '=', $employee->department->id)->orderBy('updated_at', 'DESC');
                }
                else
                {
                    $query = Requisition::where('created_by', '=', \Auth::user()->id);
                }

            }

            $requisitions = $query->get();
            return view('requisition.approval', compact('requisitions'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }

    }

    public function create()
    {
        $employee      = Employee::where('user_id', \Auth::user()->id)->first();

        return view('requisition.create', compact('employee'));
    }

    public function store(Request $request)
    {
        $employee      = Employee::where('user_id', \Auth::user()->id)->first();

        $requisition                 = new Requisition();
        $requisition->employee_id    = $request->employee_id;
        $requisition->status         = 'Pending';
        $requisition->hod_approval = 'Pending';
        $requisition->admin_approval = 'Pending';
        $requisition->chairman_approval = 'Pending';
        $date = new DateTime();
        $requisition->requisition_date     = $request->requisition_date;
        $requisition->title    = $request->title;
        $requisition->ref_number     = 'req/' . $date->format('Ymd/His');
        $requisition->created_by       = \Auth::user()->id;
        $requisition->department_id = $employee->department ? $employee->department->id : 0;

        // $total_amount = 0;

        $image = $request->file('document');
        if ($image != '') {
            $request->validate(
                [
                    'document' => 'sometimes|required|mimes:pdf,doc,jpg,jpeg,png,svg|max:20000',
                ],
                ['document.mimes' => __('The :attribute must be an pdf,doc,jpg,jpeg,png,svg'),]
            );
            $path_folder = public_path('storage/requisition/' . $request->employee_id);

            $image_name = "requisition - " . rand() . '.' . $image->getClientOriginalExtension();
            $image->move($path_folder, $image_name);

            $requisition->document = $image_name;
        }

        $requisition->save();

        $items = $request->items;

        foreach ($items as $item) {
            $requisitionItem                 = new RequisitionItem();
            $requisitionItem->requisition_id    = $requisition->id;
            $requisitionItem->item     = $item["item"];
            $requisitionItem->quantity     = $item['quantity'];
            $requisitionItem->rate     = $item['rate'];
            // $total_amount += ($item['quantity'] * $item['rate']);
            $requisitionItem->save();
        }

        // $requisition->amount = $total_amount;
        // $requisition->save();
        // $requisition->created_by     = \Auth::user()->creatorId();


        return redirect()->route('requisition.index')->with('success', __('Requisition successfully created.'));
    }

    public function edit(Request $request, $id)
    {

        $employee      = Employee::where('user_id', \Auth::user()->id)->first();
        $requisition = Requisition::find($id);
        $requisitionItem = RequisitionItem::where('requisition_id', $requisition->id)->get();

        return view('requisition.edit', compact('requisition', 'employee', 'requisitionItem'));
    }

    public function update(Request $request, $id)
    {

        $requisition = Requisition::find($id);

        $requisition->employee_id    = $request->employee_id;
        $requisition->status         = 'Pending';
        $requisition->requisition_date     = $request->requisition_date;
        $requisition->title    = $request->title;

        $image = $request->file('document');
        if ($image != '') {

            if (File::exists($requisition->document)) {
                $old_image = $path_folder = public_path('storage/requisition/' . $requisition->document);
                File::delete($old_image);
            }

            $request->validate(
                [
                    'document' => 'sometimes|required|mimes:jpg,jpeg,png,svg|max:20000',
                ],
                ['document.mimes' => __('The :attribute must be an jpg,jpeg,png,svg'),]
            );
            $path_folder = public_path('storage/requisition/' . $request->employee_id);

            $image_name = "requisition - " . rand() . '.' . $image->getClientOriginalExtension();
            $image->move($path_folder, $image_name);

            $requisition->document = $image_name;
        }

        $requisition->save();

        $items = $request->items;

        foreach ($items as $item) {

            $requisitionItem                = RequisitionItem::find($item['id']);

            if ($requisitionItem == null) {
                $requisitionItem                 = new RequisitionItem();
                $requisitionItem->requisition_id = $requisition->id;
            }
            $requisitionItem->requisition_id    = $requisition->id;

            $requisitionItem->item     = $item["item"];
            $requisitionItem->quantity     = $item['quantity'];
            $requisitionItem->rate     = $item['rate'];
            // $total_amount += ($item['quantity'] * $item['rate']);
            $requisitionItem->save();
        }
        return redirect()->route('requisition.index')->with('success', __('Requisition successfully updated.'));
    }

    public function destroy($id)
    {
        $requisition = Requisition::find($id);
        $requisition->requisitionItem()->delete();

        $requisition->delete();

        return redirect()->route('requisition.index')->with('success', __('Requisition successfully deleted.'));
    }

    public function view($id)
    {
        $requisition     = Requisition::find($id);
        $requisitionItem = RequisitionItem::where('requisition_id', $requisition->id)->get();
        $employee  = Employee::find($requisition->employee_id);

        return view('requisition.view', compact('requisition','employee','requisitionItem'));
    }

    public function action($id)
    {
        if(\Auth::user()->can('manage requisition approval'))
        {
            $requisition     = Requisition::find($id);
            $requisitionItem = RequisitionItem::where('requisition_id', $requisition->id)->get();
            $employee  = Employee::find($requisition->employee_id);

            return view('requisition.action', compact('requisition','employee','requisitionItem'));
        }
        else{
            return response()->json(['error' => __('Permission denied.')], 401);
        }

    }

    public function changeaction(Request $request)
    {

        $requisition = Requisition::find($request->requisition_id);

        $requisition->hod_approval = $request->hod_approval;
        $requisition->admin_approval = $request->admin_approval;
        $requisition->chairman_approval = $request->chairman_approval;
        $requisition->payment_status = $request->payment_status ;


        if($requisition->hod_approval == 'Approved')
        {
            $requisition->hod_remark = $request->hod_remark;
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Pending';
            $requisition->chairman_approval = 'Pending' ;
        }

        if($requisition->admin_approval == 'Approved')
        {
            $requisition->admin_remark = $request->admin_remark;
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Approved';
            $requisition->chairman_approval = 'Pending' ;
        }

        if($requisition->chairman_approval == 'Approved')
        {
            $requisition->chairman_remark = $request->chairman_remark;

            $requisition->chairman_approval = 'Approved' ;
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Approved';
        }

        if($requisition->hod_approval == 'Rejected' || $requisition->admin_approval == 'Rejected' || $requisition->chairman_approval == 'Rejected')
        {
            $requisition->status = 'Rejected';
        }
        elseif($requisition->chairman_approval == 'Approved')
        {
            $requisition->status = 'Approved';
        }

        if($requisition->payment_status)
        {
            $requisition->chairman_approval = 'Approved' ;
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Approved';
        }




        $requisition->save();

        // //Send Email
        // $setings = Utility::settings();
        // if(!empty($employee->id))
        // {
        //     if($setings['leave_status'] == 1)
        //     {

        //         $employee     = Employee::where('id', $leave->employee_id)->where('created_by', '=', \Auth::user()->creatorId())->first();
        //         $leave->name  = !empty($employee->name) ? $employee->name : '';
        //         $leave->email = !empty($employee->email) ? $employee->email : '';


        //         $actionArr = [

        //             'leave_name'=> !empty($employee->name) ? $employee->name : '',
        //             'leave_status' => $leave->status,
        //             'leave_reason' =>  $leave->leave_reason,
        //             'leave_start_date' => $leave->start_date,
        //             'leave_end_date' => $leave->end_date,
        //             'total_leave_days' => $leave->total_leave_days,

        //         ];
        //         $resp = Utility::sendEmailTemplate('leave_action_sent', [$employee->id => $employee->email], $actionArr);


        //         return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.') .(($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

        //     }

        // }

        return redirect()->route('requisition-approval')->with('success', __('Requisition Approval successfully updated.'));
    }
}
