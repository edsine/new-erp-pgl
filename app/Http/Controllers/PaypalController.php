<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
//    private   $_api_context;
    protected $invoiceData;

    public function paymentConfig()
    {

        $payment_setting = Utility::getCompanyPaymentSetting($this->invoiceData->created_by);

        if($payment_setting['paypal_mode'] == 'live'){
            config([
                'paypal.live.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.live.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        }else{
            config([
                'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        }
    }

    public function customerPayWithPaypal(Request $request, $invoice_id)
    {

        $invoice                 = Invoice::find($invoice_id);
        $this->invoiceData       = $invoice;
        $this->paymentConfig();

        $settings                = DB::table('settings')->where('created_by', '=', $invoice->created_by)->get()->pluck('value', 'name');
        $get_amount = $request->amount;
        $request->validate(['amount' => 'required|numeric|min:0']);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));


//        $invoice = Invoice::find($invoice_id);

        if($invoice)
        {
            if($get_amount > $invoice->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else
            {

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $name = Utility::invoiceNumberFormat($settings, $invoice->invoice_id);


                $paypalToken = $provider->getAccessToken();

                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('customer.get.payment.status',[$invoice->id,$get_amount]),
                        "cancel_url" =>  route('customer.get.payment.status',[$invoice->id,$get_amount]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => Utility::getValByName('site_currency'),
                                "value" => $get_amount
                            ]
                        ]
                    ]
                ]);



                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('invoice.show', \Crypt::encrypt($invoice->id))
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('invoice.show', \Crypt::encrypt($invoice->id))
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }



                return redirect()->back()->with('error', __('Unknown error occurred'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customerGetPaymentStatus(Request $request, $invoice_id,$amount)
    {

        $invoice                 = Invoice::find($invoice_id);
        $this->invoiceData       = $invoice;
        $settings                = DB::table('settings')->where('created_by', '=', $invoice->created_by)->get()->pluck('value', 'name');




        $payment_id = Session::get('paypal_payment_id');

        Session::forget('paypal_payment_id');

        if(empty($request->PayerID || empty($request->token)))
        {
            return redirect()->back()->with('error', __('Payment failed'));
        }

//        $payment = Payment::get($payment_id, $this->_api_context);

//        $execution = new PaymentExecution();
//        $execution->setPayerId($request->PayerID);

        try
        {
//            $result   = $payment->execute($execution, $this->_api_context)->toArray();
            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
//            $status   = ucwords(str_replace('_', ' ', $result['state']));
//            if($result['state'] == 'approved')
//            {
//                $amount = $result['transactions'][0]['amount']['total'];
//            }
//            else
//            {
//                $amount = isset($result['transactions'][0]['amount']['total']) ? $result['transactions'][0]['amount']['total'] : '0.00';
//            }
//
//
//            if($result['state'] == 'approved')
//            {
            $payments = InvoicePayment::create(
                [

                    'invoice_id' => $invoice->id,
                    'date' => date('Y-m-d'),
                    'amount' => $amount,
                    'account_id' => 0,
                    'payment_method' => 0,
                    'order_id' => $order_id,
                    'currency' => Utility::getValByName('site_currency'),
                    'txn_id' => $payment_id,
                    'payment_type' => __('PAYPAL'),
                    'receipt' => '',
                    'reference' => '',
                    'description' => 'Invoice ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id),
                ]
            );

            if($invoice->getDue() <= 0)
            {
                $invoice->status = 4;
                $invoice->save();
            }
            elseif(($invoice->getDue() - $payments->amount) == 0)
            {
                $invoice->status = 4;
                $invoice->save();
            }
            else
            {
                $invoice->status = 3;
                $invoice->save();
            }

            $invoicePayment              = new \App\Models\Transaction();
            $invoicePayment->user_id     = $invoice->customer_id;
            $invoicePayment->user_type   = 'Customer';
            $invoicePayment->type        = 'PAYPAL';
            $invoicePayment->created_by  = $invoice->created_by;
            $invoicePayment->payment_id  = $invoicePayment->id;
            $invoicePayment->category    = 'Invoice';
            $invoicePayment->amount      = $amount;
            $invoicePayment->date        = date('Y-m-d');
            $invoicePayment->payment_id  = $payments->id;
            $invoicePayment->description = 'Invoice ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id);
            $invoicePayment->account     = 0;

//                \App\Models\Transaction::addTransaction($invoicePayment);

//                Utility::userBalance('customer', $invoice->customer_id, $request->amount, 'debit');
//
//                Utility::bankAccountBalance($request->account_id, $request->amount, 'credit');

            //Slack Notification
            $setting  = Utility::settings($invoice->created_by);
            $customer = Customer::find($invoice->customer_id);
            if(isset($setting['payment_notification']) && $setting['payment_notification'] == 1)
            {
                $msg = __("New payment of").' ' . $amount . __("created for").' ' . $customer->name . __("by").' '. $invoicePayment->type . '.';
                Utility::send_slack_msg($msg,$invoice->created_by);
            }

            //Telegram Notification
            $setting  = Utility::settings($invoice->created_by);
            $customer = Customer::find($invoice->customer_id);
            if(isset($setting['telegram_payment_notification']) && $setting['telegram_payment_notification'] == 1)
            {
                $msg = __("New payment of").' ' . $amount . __("created for").' ' . $customer->name . __("by").' '. $invoicePayment->type . '.';
                Utility::send_telegram_msg($msg,$invoice->created_by);
            }

            //Twilio Notification
            $setting  = Utility::settings($invoice->created_by);
            $customer = Customer::find($invoice->customer_id);
            if(isset($setting['twilio_payment_notification']) && $setting['twilio_payment_notification'] ==1)
            {
                $msg = __("New payment of").' ' . $amount . __("created for").' ' . $customer->name . __("by").' '.  $invoicePayment->type . '.';
                Utility::send_twilio_msg($customer->contact,$msg,$invoice->created_by);
            }

            return redirect()->back()->with('success', __('Payment successfully added'));
//            }
//            else
//            {
//                return redirect()->back()->with('error', __('Transaction has been ' . $status));
//            }

        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('error', __('Transaction has been failed.'));
        }

    }



}
