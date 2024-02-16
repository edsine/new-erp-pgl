<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Vender;
use App\Models\Payment;
use App\Models\Utility;
use App\Models\Customer;
use App\Models\Department;
use App\Models\BankAccount;
use App\Models\BillPayment;
use App\Models\JournalItem;
use App\Models\Transaction;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Mail;
use App\Models\ProductServiceCategory;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        if (\Auth::user()->can('manage payment')) {
            $vender = Vender::get()->pluck('name', 'id');
            $vender->prepend('Select Vendor', '');

            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('Select Client', '');

            $departments = Department::get()->pluck('name', 'id');
            $departments->prepend('Select Department', '');

            $account = BankAccount::get()->pluck('holder_name', 'id');
            $account->prepend('Select Bank Account', '');

            $category = ChartOfAccount::get()->pluck('name', 'id');
            $category->prepend('Select Ledger Account', '');


            $query = Payment::where('created_by', '=', \Auth::user()->creatorId());

            if (!empty($request->date)) {
                $date_range = explode('to', $request->date);
                $query->whereBetween('date', $date_range);
            }

            if (!empty($request->vender)) {
                $query->where('vendor_id', '=', $request->vender);
            }

            if (!empty($request->customer)) {
                $query->where('client_id', '=', $request->customer);
            }

            if (!empty($request->project)) {
                $query->where('project_id', '=', $request->project);
            }

            if (!empty($request->department)) {
                $query->where('department_id', '=', $request->department);
            }


            if (!empty($request->account)) {
                $query->where('account_id', '=', $request->account);
            }

            if (!empty($request->category)) {
                $query->where('expense_head_credit', '=', $request->category)->orWhere('expense_head_debit', '=', $request->category);
            }


            $payments = $query->get();


            return view('payment.index', compact('payments', 'account', 'category', 'vender', 'customers', 'departments'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create payment')) {
            $venders = Vender::get()->pluck('name', 'id');
            $venders->prepend('--', 0);
            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            $chart_of_accounts = ChartOfAccount::select(\DB::raw('CONCAT(name, " - ", code) AS code_name, id'))
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
            $categories = ProductServiceCategory::where('type', '=', 2)->get()->pluck('name', 'id');
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('payment.create', compact('venders', 'categories', 'accounts', 'customers', 'departments', 'chart_of_accounts'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {

        //        dd($request->all());

        if (\Auth::user()->can('create payment')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                    'amount' => 'required',
                    'account_id' => 'required',
                    // 'category_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $payment                 = new Payment();
            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
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
            if (!empty($request->add_receipt)) {



                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $payment->add_receipt = $fileName;
                $dir        = 'uploads/payment';
                $path = Utility::upload_file($request, 'add_receipt', $fileName, $dir, []);
                if ($path['flag'] == 0) {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                //                $request->add_receipt  = $path['url'];
                //                $payment->save();
            }


            $payment->description    = $request->description;
            $payment->created_by     = \Auth::user()->creatorId();
            $payment->expense_head_debit     = $request->expense_head_debit;
            $payment->expense_head_credit     = $request->expense_head_credit;
            $payment->save();

            // Journal Entry

            $journal              = new JournalEntry();
            $journal->journal_id  = $this->journalNumber();
            $journal->date        = $request->date;
            $journal->reference   = time();
            $journal->description = $request->description;
            $journal->created_by  = \Auth::user()->creatorId();
            $journal->save();

            $payment->journal_id = $journal->id;
            $payment->save();

            //Expense Head Debit

            $journalItem              = new JournalItem();
            $journalItem->journal     = $journal->id;
            $journalItem->account     = $request->expense_head_debit;
            $journalItem->description = $request->description;
            $journalItem->debit       = $request->amount;
            $journalItem->credit      = 0;
            $journalItem->save();

            //End expense Head Debit

            //Expense Head Credit

            $journalItem              = new JournalItem();
            $journalItem->journal     = $journal->id;
            $journalItem->account     = $request->expense_head_credit;
            $journalItem->description = $request->description;
            $journalItem->credit       = $request->amount;
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
            $payment->amount = \Auth::user()->priceFormat($request->amount);
            $payment->bill   = '';

            if (!empty($vender)) {
                Utility::userBalance('vendor', $vender->id, $request->amount, 'debit');
            }

            Utility::bankAccountBalance($request->account_id, $request->amount, 'debit');

            //            try
            //            {
            //                Mail::to($vender['email'])->send(new BillPaymentCreate($payment));
            //            }
            //            catch(\Exception $e)
            //            {
            //                $smtp_error = __('E-Mal has been not sent due to SMTP configuration');
            //            }



            //Twilio Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            $vender = Vender::find($request->vender_id);
            if (isset($setting['payment_notification']) && $setting['payment_notification'] == 1) {
                $msg = __("New payment of") . ' ' . \Auth::user()->priceFormat($request->amount) . __("created for") . ' ' . $vender->name . __("by") . ' ' .  $payment->type . '.';
                Utility::send_twilio_msg($vender->contact, $msg);
            }


            return redirect()->route('payment.index')->with('success', __('Payment successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit(Payment $payment)
    {

        if (\Auth::user()->can('edit payment')) {
            $venders = Vender::get()->pluck('name', 'id');
            $venders->prepend('--', 0);
            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            $chart_of_accounts = ChartOfAccount::select(\DB::raw('CONCAT(name, " - ", code) AS code_name, id'))
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
            $categories = ProductServiceCategory::get()->where('type', '=', 2)->pluck('name', 'id');

            $accounts = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('payment.edit', compact('venders', 'categories', 'accounts', 'payment', 'customers', 'departments', 'chart_of_accounts'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, Payment $payment)
    {
        if (\Auth::user()->can('edit payment')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                    'amount' => 'required',
                    'account_id' => 'required',
                    'vender_id' => 'required',
                    // 'category_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $vender = Vender::where('id', $request->vender_id)->first();
            if (!empty($vender)) {
                Utility::userBalance('vendor', $vender->id, $payment->amount, 'credit');
            }
            Utility::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

            $old_expense_head_debit = $payment->expense_head_debit;
            $old_expense_head_credit = $payment->expense_head_credit;

            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
            $payment->account_id     = $request->account_id;
            $payment->vender_id      = $request->vender_id;
            // $payment->category_id    = $request->category_id;
            $payment->payment_method = 0;
            // $payment->reference      = $request->reference;

            $payment->expense_type   = $request->expense_type;
            if ($request->expense_type == 1) {
                $payment->client_id    = $request->client_id;
                $payment->project_id    = $request->project_id;
                $payment->department_id    = $payment->department_id;
            } else if ($request->expense_type == 2) {
                $payment->client_id    = $payment->client_id;
                $payment->project_id    = $payment->project_id;
                $payment->department_id    = $request->department_id;
            }

            if (!empty($request->add_receipt)) {

                if ($payment->add_receipt) {
                    $path = storage_path('uploads/payment' . $payment->add_receipt);
                    if (file_exists($path)) {
                        \File::delete($path);
                    }
                }
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $payment->add_receipt = $fileName;
                $dir        = 'uploads/payment';
                $path = Utility::upload_file($request, 'add_receipt', $fileName, $dir, []);
                if ($path['flag'] == 0) {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                //                $request->add_receipt  = $fileName;
                //                $payment->save();
            }

            $payment->description    = $request->description;
            $payment->expense_head_debit     = $request->expense_head_debit;
            $payment->expense_head_credit     = $request->expense_head_credit;
            $payment->save();

            try {
                // Journal Entry

                $journal              = JournalEntry::find($payment->journal_id);
                $journal->date        = $request->date;
                $journal->reference   = $request->reference;
                $journal->description = $request->description;
                $journal->created_by  = \Auth::user()->creatorId();
                $journal->save();

                //Expense Head Debit

                $journalItem = JournalItem::where("journal", $journal->id)->where("account", $old_expense_head_debit)->first();

                if ($journalItem == null) {
                    $journalItem          = new JournalItem();
                    $journalItem->journal = $journal->id;
                }

                if (isset($request->expense_head_debit)) {
                    $journalItem->account = $request->expense_head_debit;
                }
                $journalItem->description = $request->description;
                $journalItem->debit       = $request->amount;
                $journalItem->credit      = 0;
                $journalItem->save();

                //End expense Head Debit

                //Expense Head Credit

                $journalItem = JournalItem::where("journal", $journal->id)->where("account", $old_expense_head_credit)->first();
                if ($journalItem == null) {
                    $journalItem          = new JournalItem();
                    $journalItem->journal = $journal->id;
                }

                if (isset($request->expense_head_credit)) {
                    $journalItem->account = $request->expense_head_credit;
                }

                $journalItem->journal     = $journal->id;
                $journalItem->description = $request->description;
                $journalItem->credit       = $request->amount;
                $journalItem->debit      = 0;
                $journalItem->save();

                //End expense Head Credit

                //End Journal Entry
            } catch (Exception $e) {
            }

            // $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $category            = ChartOfAccount::where('id', $request->expense_head_debit)->first();
            $payment->category   = $category->name;
            $payment->payment_id = $payment->id;
            $payment->type       = 'Payment';
            $payment->account    = $request->account_id;
            Transaction::editTransaction($payment);

            if (!empty($vender)) {
                Utility::userBalance('vendor', $vender->id, $request->amount, 'debit');
            }

            Utility::bankAccountBalance($request->account_id, $request->amount, 'debit');


            return redirect()->route('payment.index')->with('success', __('Payment successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Payment $payment)
    {
        if (\Auth::user()->can('delete payment')) {
            if ($payment->created_by == \Auth::user()->creatorId()) {
                $payment->delete();
                $type = 'Payment';
                $user = 'Vender';
                Transaction::destroyTransaction($payment->id, $type, $user);

                if ($payment->vender_id != 0) {
                    Utility::userBalance('vendor', $payment->vender_id, $payment->amount, 'credit');
                }
                Utility::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

                try {
                    $journalEntry = JournalEntry::find($payment->journal_id);
                    if (isset($journalEntry)) {
                        $journalEntry->delete();
                        JournalItem::where('journal', '=', $journalEntry->id)->delete();
                    }
                } catch (Exception $e) {
                }

                return redirect()->route('payment.index')->with('success', __('Payment successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
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
