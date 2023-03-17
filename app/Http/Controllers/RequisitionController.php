<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
        $date = new DateTime();
        $requisition->requisition_date     = $request->requisition_date;
        $requisition->title    = $request->title;
        $requisition->ref_number     = 'Req/' . $date->format('Ymd/His');

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
        // $requisition->ref_number     = $requisition->ref_number;

        // $total_amount = 0;
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
}
