<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Revenue;
use App\Models\Utility;
use App\Models\Customer;
use App\Models\BankAccount;
use App\Models\JournalItem;
use App\Models\Transaction;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\InvoicePayment;
use Illuminate\Support\Facades\Mail;
use App\Models\ProductServiceCategory;

class RevenueController extends Controller
{

    public function index(Request $request)
    {
        if (\Auth::user()->can('manage revenue')) {
            $customer = Customer::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('Select Client', '');

            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('Select Bank Account', '');

            $category = ChartOfAccount::get()->pluck('name', 'id');
            $category->prepend('Select Ledger Account', '');


            $query = Revenue::where('created_by', '=', \Auth::user()->creatorId());

            if (!empty($request->date)) {
                $date_range = explode('to', $request->date);
                $query->whereBetween('date', $date_range);
            }

            if (!empty($request->customer)) {
                $query->where('customer_id', '=', $request->customer);
            }
            if (!empty($request->account)) {
                $query->where('account_id', '=', $request->account);
            }

            if (!empty($request->category)) {
                $query->where('expense_head_credit', '=', $request->category)->orWhere('expense_head_debit', '=', $request->category);
            }

            if (!empty($request->payment)) {
                $query->where('payment_method', '=', $request->payment);
            }
            $revenues = $query->get();

            return view('revenue.index', compact('revenues', 'customer', 'account', 'category'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function incomeBreakdown(Request $request)
    {
        if (\Auth::user()->can('manage revenue')) {
            $customer = Customer::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $customer->prepend('Select Client', '');

            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('Select Bank Account', '');

            $category = ChartOfAccount::get()->pluck('name', 'id');
            $category->prepend('Select Ledger Account', '');


            $query = Revenue::where('created_by', '=', \Auth::user()->creatorId());

            $month = $request->month;
            $month_name = '';

            if (!empty($month)) {
                $query->whereMonth('date', intval($month));
                $month_name = Carbon::create()->month($month)->format('F');
            }

            // if (!empty($request->date)) {
            //     $date_range = explode('to', $request->date);
            //     $query->whereBetween('date', $date_range);
            // }

            if (!empty($request->customer)) {
                $query->where('customer_id', '=', $request->customer);
            }
            if (!empty($request->account)) {
                $query->where('account_id', '=', $request->account);
            }

            if (!empty($request->category)) {
                $query->where('expense_head_credit', '=', $request->category)->orWhere('expense_head_debit', '=', $request->category);
            }

            if (!empty($request->payment)) {
                $query->where('payment_method', '=', $request->payment);
            }
            $revenues = $query->get();

            return view('revenue.income_breakdown', compact('revenues', 'customer', 'account', 'category', 'month_name', 'month'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {

        if (\Auth::user()->can('create revenue')) {
            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            $chart_of_accounts = ChartOfAccount::select(\DB::raw('CONCAT(name, " - ", code) AS code_name, id'))
                ->get()
                ->pluck('code_name', 'id');
            $chart_of_accounts->prepend('--', '');
            $categories = ProductServiceCategory::where('type', '=', 1)->get()->pluck('name', 'id');
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('revenue.create', compact('customers', 'categories', 'accounts', 'chart_of_accounts'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create revenue')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                    'amount' => 'required',
                    'account_id' => 'required',
                    // 'category_id' => 'required',
                    'revenue_type' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $revenue                 = new Revenue();
            $revenue->date           = $request->date;
            $revenue->amount         = $request->amount;
            $revenue->account_id     = $request->account_id;
            $revenue->revenue_type   = $request->revenue_type;
            if ($request->revenue_type != 1) {
                $revenue->customer_id    = 0;
                $revenue->project_id    = 0;
            } else {
                $revenue->customer_id    = $request->customer_id;
                $revenue->project_id    = $request->project_id;;
            }
            $revenue->category_id    = 0;
            $revenue->payment_method = 0;
            $revenue->reference      = time();
            $revenue->description    = $request->description;
            $revenue->expense_head_debit     = $request->expense_head_debit;
            $revenue->expense_head_credit     = $request->expense_head_credit;
            if (!empty($request->add_receipt)) {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                // $request->add_receipt->storeAs('uploads/revenue', $fileName);
                $revenue->add_receipt = $fileName;
                $dir        = 'uploads/revenue';
                $url = '';
                $path = Utility::upload_file($request, 'add_receipt', $fileName, $dir, []);
                if ($path['flag'] == 0) {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                //                $request->add_receipt  = $fileName;
                //                $revenue->save();
            }

            $revenue->created_by     = \Auth::user()->creatorId();
            $revenue->save();

            // Journal Entry

            $journal              = new JournalEntry();
            $journal->journal_id  = $this->journalNumber();
            $journal->date        = $request->date;
            $journal->reference   = time();
            $journal->description = $request->description;
            $journal->created_by  = \Auth::user()->creatorId();
            $journal->save();

            $revenue->journal_id = $journal->id;
            $revenue->save();

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


            // Bank Account debited
            $debitAccount = ChartOfAccount::where('id', $request->expense_head_debit)->first();
            $code = $debitAccount->code;
            if ($code == 2000 || $code == 2100 || $code == 2200 || $code == 2300 || $code == 2400 || $code == 2500) {
                $account = ChartOfAccount::where('code', 100)->first();
                if ($account != null) {
                    $journalItem              = new JournalItem();
                    $journalItem->journal     = $journal->id;
                    $journalItem->account     = $account->id;
                    $journalItem->description = $request->description;
                    $journalItem->debit       = $request->amount;
                    $journalItem->credit      = 0;
                    $journalItem->save();
                }
            }


            // // Account receivables credited
            // $account = ChartOfAccount::where('code', 300)->first();
            // if ($account != null) {
            //     $journalItem              = new JournalItem();
            //     $journalItem->journal     = $journal->id;
            //     $journalItem->account     = $account->id;
            //     $journalItem->description = $request->description;
            //     $journalItem->credit       = $request->amount;
            //     $journalItem->debit      = 0;
            //     $journalItem->save();
            // }


            //End expense Head Credit

            //End Journal Entry

            // $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $category            = ChartOfAccount::where('id', $request->expense_head_credit)->first();
            $revenue->payment_id = $revenue->id;
            $revenue->type       = 'Revenue';
            $revenue->category   = $category->name;
            $revenue->user_id    = $revenue->customer_id;
            $revenue->user_type  = 'Customer';
            $revenue->account    = $request->account_id;
            Transaction::addTransaction($revenue);

            $customer         = Customer::where('id', $request->customer_id)->first();
            $payment          = new InvoicePayment();
            $payment->name    = !empty($customer) ? $customer['name'] : '';
            $payment->date    = \Auth::user()->dateFormat($request->date);
            $payment->amount  = \Auth::user()->priceFormat($request->amount);
            $payment->invoice = '';

            if (!empty($customer)) {
                Utility::userBalance('customer', $customer->id, $revenue->amount, 'credit');
            }

            Utility::bankAccountBalance($request->account_id, $revenue->amount, 'credit');

            if ($revenue->project_id) {
                Utility::projectAmountPaid($request->project_id, $revenue->amount, 'credit');
            }

            //            try
            //            {
            //                Mail::to($customer['email'])->send(new InvoicePaymentCreate($payment));
            //            }
            //            catch(\Exception $e)
            //            {
            //                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            //            }

            //Slack Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            if (isset($setting['revenue_notification']) && $setting['revenue_notification'] == 1) {
                $msg = __("New Revenue of") . ' ' . \Auth::user()->priceFormat($request->amount) . ' ' . __("created for") . ' ' . $customer->name . ' ' . __("by") . ' ' . \Auth::user()->name . '.';
                Utility::send_slack_msg($msg);
            }

            //Telegram Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            if (isset($setting['telegram_revenue_notification']) && $setting['telegram_revenue_notification'] == 1) {
                $msg = __("New Revenue of") . ' ' . \Auth::user()->priceFormat($request->amount) . ' ' . __("created for") . ' ' . $customer->name . ' ' . __("by") . ' ' . \Auth::user()->name . '.';
                Utility::send_telegram_msg($msg);
            }

            //Twilio Notification
            $setting  = Utility::settings(\Auth::user()->creatorId());
            $customer = Customer::find($request->customer_id);
            if (isset($setting['twilio_revenue_notification']) && $setting['twilio_revenue_notification'] == 1) {
                $msg = __("New Revenue of") . ' ' . \Auth::user()->priceFormat($request->amount) . ' ' . __("created for") . ' ' . $customer->name . ' ' . __("by") . ' ' . \Auth::user()->name . '.';

                Utility::send_twilio_msg($customer->contact, $msg);
            }


            return redirect()->route('revenue.index')->with('success', __('Revenue successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit(Revenue $revenue)
    {
        if (\Auth::user()->can('edit revenue')) {
            $customers = Customer::get()->pluck('name', 'id');
            $customers->prepend('--', 0);
            $chart_of_accounts = ChartOfAccount::select(\DB::raw('CONCAT(name, " - ", code) AS code_name, id'))
                ->get()
                ->pluck('code_name', 'id');
            $chart_of_accounts->prepend('--', '');
            $categories = ProductServiceCategory::where('type', '=', 1)->get()->pluck('name', 'id');
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->get()->pluck('name', 'id');

            return view('revenue.edit', compact('customers', 'categories', 'accounts', 'revenue', 'chart_of_accounts'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, Revenue $revenue)
    {
        if (\Auth::user()->can('edit revenue')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                    'amount' => 'required',
                    'account_id' => 'required',
                    // 'category_id' => 'required',
                    'revenue_type' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $customer = Customer::where('id', $request->customer_id)->first();
            if (!empty($customer)) {
                Utility::userBalance('customer', $customer->id, $revenue->amount, 'debit');
            }

            Utility::bankAccountBalance($revenue->account_id, $revenue->amount, 'debit');

            $old_expense_head_debit = $revenue->expense_head_debit;
            $old_expense_head_credit = $revenue->expense_head_credit;

            if ($revenue->project_id) {
                Utility::projectAmountPaid($revenue->project_id, $revenue->amount, 'debit');
            }

            $revenue->date           = $request->date;
            $revenue->amount         = $request->amount;
            $revenue->account_id     = $request->account_id;
            $revenue->revenue_type   = $request->revenue_type;
            if ($request->revenue_type != 1) {
                $revenue->customer_id    = $revenue->customer_id;
                $revenue->project_id    = $revenue->project_id;
            } else {
                $revenue->customer_id    = $request->customer_id;
                $revenue->project_id    = $request->project_id;;
            }
            $revenue->category_id    = 0;
            $revenue->payment_method = 0;
            // $revenue->reference      = $request->reference;
            $revenue->description    = $request->description;
            $revenue->expense_head_debit     = $request->expense_head_debit;
            $revenue->expense_head_credit     = $request->expense_head_credit;
            if (!empty($request->add_receipt)) {

                if ($revenue->add_receipt) {
                    $path = storage_path('uploads/revenue/' . $revenue->add_receipt);
                    if (file_exists($path)) {
                        \File::delete($path);
                    }
                }
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $revenue->add_receipt = $fileName;
                $dir        = 'uploads/revenue';
                $path = Utility::upload_file($request, 'add_receipt', $fileName, $dir, []);
                if ($path['flag'] == 0) {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                //                $request->add_receipt  = $path['url'];
                //                $revenue->save();
            }

            $revenue->save();

            try {
                // Journal Entry

                $journal              = JournalEntry::find($revenue->journal_id);
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


                // Bank Account debited
                $debitAccount = ChartOfAccount::where('id', $request->expense_head_debit)->first();
                $code = $debitAccount->code;
                if ($code == 2000 || $code == 2100 || $code == 2200 || $code == 2300 || $code == 2400 || $code == 2500) {
                    $account = ChartOfAccount::where('code', 100)->first();
                    if ($account != null) {
                        $journalItem              = JournalItem::where("journal", $journal->id)->where("account", $account->id)->first();
                        if ($journalItem == null) {
                            $journalItem          = new JournalItem();
                            $journalItem->journal = $journal->id;
                        }
                        $journalItem->journal     = $journal->id;
                        $journalItem->account     = $account->id;
                        $journalItem->description = $request->description;
                        $journalItem->debit       = $request->amount;
                        $journalItem->credit      = 0;
                        $journalItem->save();
                    }
                }

                // // Account Receivables

                // $account = ChartOfAccount::where('code', 300)->first();
                // if ($account != null) {
                //     $journalItem = JournalItem::where("journal", $journal->id)->where("account", $account->id)->first();
                //     if ($journalItem == null) {
                //         $journalItem          = new JournalItem();
                //         $journalItem->journal = $journal->id;
                //     }

                //     $journalItem->account = $account->id;
                //     $journalItem->journal     = $journal->id;
                //     $journalItem->description = $request->description;
                //     $journalItem->credit       = $request->amount;
                //     $journalItem->debit      = 0;
                //     $journalItem->save();
                // }

                // // End Account Receivables

                //End Journal Entry
            } catch (Exception $e) {
            }

            // $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $category            = ChartOfAccount::where('id', $request->expense_head_credit)->first();
            $revenue->category   = $category->name;
            $revenue->payment_id = $revenue->id;
            $revenue->type       = 'Revenue';
            $revenue->account    = $request->account_id;
            Transaction::editTransaction($revenue);

            if (!empty($customer)) {
                Utility::userBalance('customer', $customer->id, $request->amount, 'credit');
            }

            Utility::bankAccountBalance($request->account_id, $request->amount, 'credit');

            if ($revenue->project_id) {
                Utility::projectAmountPaid($request->project_id, $revenue->amount, 'credit');
            }


            return redirect()->route('revenue.index')->with('success', __('Revenue successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Revenue $revenue)
    {
        if (\Auth::user()->can('delete revenue')) {
            if ($revenue->created_by == \Auth::user()->creatorId()) {

                $journalEntry = JournalEntry::where('id', $revenue->journal_id)->first();

                if ($journalEntry != null) {
                    $journalEntry->delete();
                    JournalItem::where('journal', '=', $journalEntry->id)->delete();
                }

                $revenue->delete();
                $type = 'Revenue';
                $user = 'Customer';
                Transaction::destroyTransaction($revenue->id, $type, $user);

                if ($revenue->customer_id != 0) {
                    Utility::userBalance('customer', $revenue->customer_id, $revenue->amount, 'debit');
                }

                Utility::bankAccountBalance($revenue->account_id, $revenue->amount, 'debit');

                if ($revenue->project_id) {
                    Utility::projectAmountPaid($revenue->project_id, $revenue->amount, 'debit');
                }

                return redirect()->route('revenue.index')->with('success', __('Revenue successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function journalNumber()
    {
        $latest = JournalEntry::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->journal_id + 1;
    }
}
