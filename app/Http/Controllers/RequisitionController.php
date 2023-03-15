<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use DateTime;
use Illuminate\Http\Request;

class RequisitionController extends Controller
{
    public function index()
    {
        $query = Requisition::orderBy('created_at', 'DESC');
        $requisitions = $query->get();

        return view('requisition.index', compact('requisitions'));
    }

    public function create()
    {
        $employee      = Employee::where('user_id', \Auth::user()->id)->first();

        return view('requisition.create', compact('employee'));
    }

    public function store(Request $request)
    {

        $requisition                 = new Requisition();
        $requisition->employee_id    = $request->employee_id;
        $requisition->status         = 'Pending';
        $requisition->requisition_date     = $request->requisition_date;
        $requisition->title    = $request->title;

        // $total_amount = 0;

        $image = $request->file('document');
        if ($image != '') {
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
        $date = new DateTime();
        foreach ($items as $item) {
            $requisitionItem                 = new RequisitionItem();
            $requisitionItem->requisition_id    = $requisition->id;
            $requisitionItem->ref_number     = 'Req/'. $date->format('Ymd/His');
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

    public function edit ($id)
    {
        $requisition = Requisition::find($id);
        return view('requisition.edit', compact('requisition'));
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        $requisition = Requisition::find($id);
        $requisition->requisitionItem()->delete();

        $requisition->delete();

        return redirect()->route('requisition.index')->with('success', __('Requisition successfully deleted.'));
    }
}
