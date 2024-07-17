<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Vender;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Revenue;
use App\Models\Utility;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Department;
use App\Models\BankAccount;
use App\Models\BillPayment;
use App\Models\JournalItem;
use App\Models\Requisition;
use App\Models\Transaction;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\RequisitionItem;
use App\Models\User;
use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    public function index(Request $request)
    {
        $employee      = Employee::where('user_id', \Auth::user()->id)->first();

        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'admin') {
            $query = Requisition::orderBy('created_at', 'DESC');
        } else {
            if (isset($employee->department)) {
                $query = Requisition::where('department_id', '=', $employee->department->id);
            } else {
                $query = Requisition::where('created_by', '=', \Auth::user()->id);
            }
        }

        $requisitions = $query->get();

        return view('requisition.index', compact('requisitions'));
    }

    public function chairman_dashboard_main(){

       
        $currentYear = date('Y'); // Get the current year
$currentMonth = date('m'); // Get the current month
$currentDate = date('Y-m-d'); // Get the current date in 'YYYY-MM-DD' format

// Fetch all incomes for the current month
$incomes = Revenue::whereYear('created_at', $currentYear)
                  ->whereMonth('created_at', $currentMonth)
                  ->sum('amount');

// Fetch all payments for the current month
$payments = Payment::whereYear('created_at', $currentYear)
                   ->whereMonth('created_at', $currentMonth)
                   ->sum('amount');
                   // Fetch all incomes for today
$incomes1 = Revenue::whereDate('created_at', $currentDate)
->sum('amount');

// Fetch all payments for today
$payments1 = Payment::whereDate('created_at', $currentDate)
->sum('amount');

        $approvals = Requisition::orderBy('updated_at', 'DESC')->where('chairman_approval','=', 'Pending')->limit(3)->get();
        $requisitions = Requisition::orderBy('updated_at', 'DESC')->where('chairman_approval','=', 'Approved')->limit(3)->get();
        $completed_projects = Project::where('status', 'LIKE', 'complete')->count();
        $ongoing_projects = Project::where('status', 'LIKE', 'in_progress')->count();
        $on_hold_projects = Project::where('status', 'LIKE', 'on_hold')->count();
        $users_at_work = User::where('type', '!=', 'client')->count();
        $totalEmployeesWithLeave = DB::table('employees')
        ->join('leaves', 'employees.id', '=', 'leaves.employee_id')
        ->where('leaves.chairman_approval', '=', 'Approved')
        ->select(DB::raw('COUNT(DISTINCT employees.id) AS total_employees_with_leave'))
        ->get();
        // The result is a collection with one row containing the total count
        $users_on_leave = $totalEmployeesWithLeave->first()->total_employees_with_leave;
        $users_as_client = User::where('type', '=', 'client')->count();

        $year = date('Y');

// Initialize arrays to store monthly data
$monthlyIncomes = [];
$monthlyPayments = [];

// Fetch monthly data for incomes
for ($month = 1; $month <= 12; $month++) {
    $monthlyIncomes[] = Revenue::whereYear('created_at', $year)
                                ->whereMonth('created_at', $month)
                                ->sum('amount');
}

// Fetch monthly data for payments
for ($month = 1; $month <= 12; $month++) {
    $monthlyPayments[] = Payment::whereYear('created_at', $year)
                                ->whereMonth('created_at', $month)
                                ->sum('amount');
}

// Prepare data for JavaScript
$incomesData = implode(',', $monthlyIncomes);
$paymentsData = implode(',', $monthlyPayments);

        return view('dashboard.chairman-dashboard', compact(
            'incomes', 'payments', 'incomes1', 'payments1', 'approvals', 'requisitions', 
            'completed_projects', 'ongoing_projects', 'on_hold_projects',
            'users_at_work','users_on_leave','users_as_client',
            'incomesData', 'paymentsData'));
    }

    public function chairman_dashboard_index(){
        if(\Auth::check())
        {
              $user = \Auth::user();
                if($user->type == 'chairman' || $user->type == 'company')
                {
                    
$query = Requisition::where('admin_approval', '=', "Approved")->orderBy('updated_at', 'DESC');
$chart_of_accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', \Auth::user()->creatorId())
                ->where('code', '!=', 2000)
                ->where('code', '!=', 2100)
                ->where('code', '!=', 2200)
                ->where('code', '!=', 2300)
                ->where('code', '!=', 2400)
                ->where('code', '!=', 2500)
                ->get()
                ->pluck('code_name', 'id');
            $chart_of_accounts->prepend('--', '');

            $requisitions = $query->get();
            return view('requisition.chairman-approval', compact('requisitions', 'chart_of_accounts'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }

        }
   
    }
    public function approval(Request $request)
    {
        if (\Auth::user()->can('manage requisition approval')) {
            $employee      = Employee::where('user_id', \Auth::user()->id)->first();

            if (\Auth::user()->type == 'company' || \Auth::user()->type == 'office admin' || \Auth::user()->type == 'chairman' || \Auth::user()->type == 'ED/COO' || \Auth::user()->can('manage payment status')) {
                $query = Requisition::where('department_id', '=', $employee->department->id)->orderBy('updated_at', 'DESC');
            } else {
                if (isset($employee->department)) {
                    $query = Requisition::where('department_id', '=', $employee->department->id)->orderBy('updated_at', 'DESC');
                } else {
                    $query = Requisition::where('created_by', '=', \Auth::user()->id);
                }
            }

            $chart_of_accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', \Auth::user()->creatorId())
                ->where('code', '!=', 2000)
                ->where('code', '!=', 2100)
                ->where('code', '!=', 2200)
                ->where('code', '!=', 2300)
                ->where('code', '!=', 2400)
                ->where('code', '!=', 2500)
                ->get()
                ->pluck('code_name', 'id');
            $chart_of_accounts->prepend('--', '');

            $requisitions = $query->get();
            return view('requisition.approval', compact('requisitions', 'chart_of_accounts'));
        } else {
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
        if($employee) {
            $requisition->department_id = $employee->department ? $employee->department->id : 0;
        }else {
            $requisition->employee_id = 0;
            $requisition->department_id = 0;
        }

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

        $employee      = Employee::where('user_id', \Auth::user()->id)->first();

        if($employee) {
            $requisition->department_id = $employee->department ? $employee->department->id : 0;
        }else {
            $requisition->employee_id = 0;
            $requisition->department_id = 0;
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

        return view('requisition.view', compact('requisition', 'employee', 'requisitionItem'));
    }

    public function action1($id)
    {
        if (\Auth::user()->type == "chairman" || \Auth::user()->type == "company") {
            $requisition     = Requisition::find($id);
            $requisitionItem = RequisitionItem::where('requisition_id', $requisition->id)->get();
            $employee  = Employee::find($requisition->employee_id);


            $venders = Vender::get()->pluck('name', 'id');
            $venders->prepend('--', 0);
            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            $chart_of_accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', \Auth::user()->creatorId())
                ->where('code', '!=', 2000)
                ->where('code', '!=', 2100)
                ->where('code', '!=', 2200)
                ->where('code', '!=', 2300)
                ->where('code', '!=', 2400)
                ->where('code', '!=', 2500)
                ->get()
                ->pluck('code_name', 'id');
            $chart_of_accounts->prepend('--', '');
            $departments = Department::get()->pluck('name', 'id');
            $departments->prepend('--', 0);
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');


            return view('requisition.action1', compact('requisition', 'employee', 'requisitionItem', 'chart_of_accounts', 'venders', 'customers', 'departments', 'accounts'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function action($id)
    {
        if (\Auth::user()->can('manage requisition approval')) {
            $requisition     = Requisition::find($id);
            $requisitionItem = RequisitionItem::where('requisition_id', $requisition->id)->get();
            $employee  = Employee::find($requisition->employee_id);


            $venders = Vender::get()->pluck('name', 'id');
            $venders->prepend('--', 0);
            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            $chart_of_accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', \Auth::user()->creatorId())
                ->where('code', '!=', 2000)
                ->where('code', '!=', 2100)
                ->where('code', '!=', 2200)
                ->where('code', '!=', 2300)
                ->where('code', '!=', 2400)
                ->where('code', '!=', 2500)
                ->get()
                ->pluck('code_name', 'id');
            $chart_of_accounts->prepend('--', '');
            $departments = Department::get()->pluck('name', 'id');
            $departments->prepend('--', 0);
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');


            return view('requisition.action', compact('requisition', 'employee', 'requisitionItem', 'chart_of_accounts', 'venders', 'customers', 'departments', 'accounts'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function changeaction(Request $request)
    {

        $requisition = Requisition::find($request->requisition_id);

        $requisition->hod_approval = $request->hod_approval;
        $requisition->admin_approval = $request->admin_approval;
        $requisition->chairman_approval = $request->chairman_approval;
        $requisition->payment_status = $request->payment_status;


        if ($requisition->hod_approval == 'Approved') {
            $requisition->hod_remark = $request->hod_remark;
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Pending';
            $requisition->chairman_approval = 'Pending';
        }

        if ($requisition->admin_approval == 'Approved') {
            $requisition->admin_remark = $request->admin_remark;
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Approved';
            $requisition->chairman_approval = 'Pending';
        }

        if ($requisition->chairman_approval == 'Approved') {
            $requisition->chairman_remark = $request->chairman_remark;

            $requisition->chairman_approval = 'Approved';
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Approved';
        }

        if ($requisition->hod_approval == 'Rejected' || $requisition->admin_approval == 'Rejected' || $requisition->chairman_approval == 'Rejected') {
            $requisition->status = 'Rejected';
        } elseif ($requisition->chairman_approval == 'Approved') {
            $requisition->status = 'Approved';
        }

        if ($requisition->payment_status) {
            $requisition->chairman_approval = 'Approved';
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Approved';
        }

        if ($requisition->payment_status == 'Paid') {
            $payment                 = new Payment();
            $payment->date           = $requisition->requisition_date;
            $payment->amount         = $requisition->totalAmount();
            $payment->account_id     = $request->account_id;
            $payment->vender_id      = $request->vender_id;
            $payment->category_id    = 0;
            $payment->payment_method = 0;
            $payment->reference      = time();

            $payment->expense_type   = $request->expense_type;
            if ($request->expense_type == 1) {
                $payment->client_id    = $request->client_id;
                $payment->project_id    = $request->project_id;
                $payment->department_id    = 0;
            } else if ($request->expense_type == 2) {
                $payment->client_id    = 0;
                $payment->project_id    = 0;
                $payment->department_id    = $request->department_id;
            }

            $payment->description    = $requisition->title;
            $payment->created_by     = \Auth::user()->creatorId();
            $payment->expense_head_debit     = $request->expense_head_debit;
            $payment->expense_head_credit     = $request->expense_head_credit;
            $payment->save();

            // Journal Entry

            $journal              = new JournalEntry();
            $journal->journal_id  = $this->journalNumber();
            $journal->date        = $requisition->requisition_date;
            $journal->reference   = time();
            $journal->description = $requisition->title;
            $journal->created_by  = \Auth::user()->creatorId();
            $journal->save();

            $payment->journal_id = $journal->id;
            $payment->save();

            //Expense Head Debit

            $journalItem              = new JournalItem();
            $journalItem->journal     = $journal->id;
            $journalItem->account     = $request->expense_head_debit;
            $journalItem->description = $requisition->title;
            $journalItem->debit       = $requisition->totalAmount();
            $journalItem->credit      = 0;
            $journalItem->save();

            //End expense Head Debit

            //Expense Head Credit

            $journalItem              = new JournalItem();
            $journalItem->journal     = $journal->id;
            $journalItem->account     = $request->expense_head_credit;
            $journalItem->description = $requisition->title;
            $journalItem->credit       = $requisition->totalAmount();
            $journalItem->debit      = 0;
            $journalItem->save();

            //End expense Head Credit

            //End Journal Entry

            // $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $category            = ChartOfAccount::where('id', $request->expense_head_debit)->first();
            $payment->payment_id = $payment->id;
            $payment->type       = 'Payment';
            $payment->category   = $category->name;
            $payment->user_id    = $payment->vender_id;
            $payment->user_type  = 'Vender';
            $payment->account    = $request->account_id;

            Transaction::addTransaction($payment);

            $vender          = Vender::where('id', $request->vender_id)->first();
            $payment         = new BillPayment();
            $payment->name   = !empty($vender) ? $vender['name'] : '';
            $payment->method = '-';
            $payment->date   = \Auth::user()->dateFormat($request->date);
            $payment->amount = \Auth::user()->priceFormat($requisition->totalAmount());
            $payment->bill   = '';

            if (!empty($vender)) {
                Utility::userBalance('vendor', $vender->id, $requisition->totalAmount(), 'debit');
            }

            Utility::bankAccountBalance($request->account_id, $requisition->totalAmount(), 'debit');
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

    public function changeactionByChairman(Request $request)
    {

        $requisition = Requisition::find($request->requisition_id);

        $requisition->hod_approval = $request->hod_approval;
        $requisition->admin_approval = $request->admin_approval;
        $requisition->chairman_approval = $request->chairman_approval;
        $requisition->payment_status = $request->payment_status;


        if ($requisition->hod_approval == 'Approved') {
            $requisition->hod_remark = $request->hod_remark;
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Pending';
            $requisition->chairman_approval = 'Pending';
        }

        if ($requisition->admin_approval == 'Approved') {
            $requisition->admin_remark = $request->admin_remark;
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Approved';
            $requisition->chairman_approval = 'Pending';
        }

        if ($requisition->chairman_approval == 'Approved') {
            $requisition->chairman_remark = $request->chairman_remark;

            $requisition->chairman_approval = 'Approved';
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Approved';
        }

        if ($requisition->hod_approval == 'Rejected' || $requisition->admin_approval == 'Rejected' || $requisition->chairman_approval == 'Rejected') {
            $requisition->status = 'Rejected';
        } elseif ($requisition->chairman_approval == 'Approved') {
            $requisition->status = 'Approved';
        }

        if ($requisition->payment_status) {
            $requisition->chairman_approval = 'Approved';
            $requisition->hod_approval           = 'Approved';
            $requisition->admin_approval = 'Approved';
        }

        if ($requisition->payment_status == 'Paid') {
            $payment                 = new Payment();
            $payment->date           = $requisition->requisition_date;
            $payment->amount         = $requisition->totalAmount();
            $payment->account_id     = $request->account_id;
            $payment->vender_id      = $request->vender_id;
            $payment->category_id    = 0;
            $payment->payment_method = 0;
            $payment->reference      = time();

            $payment->expense_type   = $request->expense_type;
            if ($request->expense_type == 1) {
                $payment->client_id    = $request->client_id;
                $payment->project_id    = $request->project_id;
                $payment->department_id    = 0;
            } else if ($request->expense_type == 2) {
                $payment->client_id    = 0;
                $payment->project_id    = 0;
                $payment->department_id    = $request->department_id;
            }

            $payment->description    = $requisition->title;
            $payment->created_by     = \Auth::user()->creatorId();
            $payment->expense_head_debit     = $request->expense_head_debit;
            $payment->expense_head_credit     = $request->expense_head_credit;
            $payment->save();

            // Journal Entry

            $journal              = new JournalEntry();
            $journal->journal_id  = $this->journalNumber();
            $journal->date        = $requisition->requisition_date;
            $journal->reference   = time();
            $journal->description = $requisition->title;
            $journal->created_by  = \Auth::user()->creatorId();
            $journal->save();

            $payment->journal_id = $journal->id;
            $payment->save();

            //Expense Head Debit

            $journalItem              = new JournalItem();
            $journalItem->journal     = $journal->id;
            $journalItem->account     = $request->expense_head_debit;
            $journalItem->description = $requisition->title;
            $journalItem->debit       = $requisition->totalAmount();
            $journalItem->credit      = 0;
            $journalItem->save();

            //End expense Head Debit

            //Expense Head Credit

            $journalItem              = new JournalItem();
            $journalItem->journal     = $journal->id;
            $journalItem->account     = $request->expense_head_credit;
            $journalItem->description = $requisition->title;
            $journalItem->credit       = $requisition->totalAmount();
            $journalItem->debit      = 0;
            $journalItem->save();

            //End expense Head Credit

            //End Journal Entry

            // $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $category            = ChartOfAccount::where('id', $request->expense_head_debit)->first();
            $payment->payment_id = $payment->id;
            $payment->type       = 'Payment';
            $payment->category   = $category->name;
            $payment->user_id    = $payment->vender_id;
            $payment->user_type  = 'Vender';
            $payment->account    = $request->account_id;

            Transaction::addTransaction($payment);

            $vender          = Vender::where('id', $request->vender_id)->first();
            $payment         = new BillPayment();
            $payment->name   = !empty($vender) ? $vender['name'] : '';
            $payment->method = '-';
            $payment->date   = \Auth::user()->dateFormat($request->date);
            $payment->amount = \Auth::user()->priceFormat($requisition->totalAmount());
            $payment->bill   = '';

            if (!empty($vender)) {
                Utility::userBalance('vendor', $vender->id, $requisition->totalAmount(), 'debit');
            }

            Utility::bankAccountBalance($request->account_id, $requisition->totalAmount(), 'debit');
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

        return redirect()->route('chairman.dashboard')->with('success', __('Requisition approval successfully updated.'));
    }

    function journalNumber()
    {
        $latest = JournalEntry::latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->journal_id + 1;
    }
}
