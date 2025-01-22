<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>NIWA Service Application Payment Invoice</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Nunito:700" />


    <style>
        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: 'Roboto', sans-serif !important;
            font-size: 16px;
            margin-bottom: 10px;
            line-height: 24px;
            color: #8094ae;
            font-weight: 400;
        }

        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        table table table {
            table-layout: auto;
        }

        a {
            text-decoration: none;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }
    </style>

</head>

<body width="100%"
    style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f5f6fa;">
    <center style="width: 100%; background-color: #f5f6fa;">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f5f6fa">
            <tr>
                <td style="padding: 40px 0;">
                    <table style="width:100%;max-width:620px;margin:0 auto;">
                        <tbody>
                            <tr>
                                <td style="text-align: center; padding-bottom:25px">
                                    <a href="#"><img style="width: 100%;height: auto;"
                                        src="{{ url('assets/images/logo.jpg') }}"
                                        alt="NIWA logo"></a>
                                    <p
                                        style="font-size: 1.5rem; font-family: Nunito, sans-serif; font-weight: 700; line-height: 1.2; color: #364a63; padding-top: 12px;">
                                        National Inland Waterways Authority<br />EXPRESS</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:100%;max-width:620px;margin:0 auto;background-color:#ffffff;">
                        <tbody>
                            <tr>
                                <td style="padding: 30px 30px 20px">
                                    <p style="margin-bottom: 10px;">Hi {{ $payment->employer->contact_firstname }} {{ $payment->employer->contact_surname }},</p>
                                    <p style="margin-bottom: 10px;">Your payment has been received successfully!
                                    </p>
                                    <br/>
                                    @php
                                    $text = $payment->payment_status == 1 ? 'Receipt' : 'Invoice';
                                @endphp
                                <hr>
                                <table width="100%">
                                    <tr class="invoice-head mx-3">
                                        <td class="invoice-contact">
                                            <span class="overline-title">{{ $text }} To</span>
                                            <div class="invoice-contact-info">
                                                @if (isset($payment->employer))
                                                    <h4 class="title">{{ $payment->employer->company_name }}</h4>
                                                    <ul class="list-plain">
                                                        <li><em class="icon ni ni-map-pin-fill fs-18px"></em><span>{{ $payment->employer->company_address }}<br>{{ isset($payment->employer->lga->name) && !empty($payment->employer->lga->name) ? $payment->employer->lga->name : 'Name Not Available' }}
                                                                ,
                                                                {{ $payment->employer->state ? $payment->employer->state->name : '' }} </span></li>
                                                        <li><em
                                                                class="icon ni ni-call-fill fs-14px"></em><span>{{ $payment->employer->company_phone }}</span>
                                                        </li>
                                                    </ul>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="invoice-desc" align="right">
                                            <h3 class="title">{{ $text }}</h3>
                                            <table class="list-plain" align="right">
                                                <tr class="invoice-id">
                                                    <td width="100px">{{ $text }}
                                                        ID:</td>
                                                    <td width="150px" align="right">{{ $payment->invoice_number }}</td>
                                                </tr>
                                                <tr class="invoice-date">
                                                    <td>Date:</td>
                                                    <td align="right">{{ date('d M, Y', strtotime($payment->invoice_generated_at)) }}</td>
                                                </tr>
                                                <tr class="invoice-date">
                                                    <td>RRR:</td>
                                                    <td align="right">{{ $payment->rrr }}</td>
                                                </tr>
                                                <tr class="invoice-date">
                                                    <td>Status:</td>
                                                    <td align="right">{{ $payment->payment_status == 1 ? 'PAID' : 'UNPAID' }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr><!-- .invoice-head -->
                                </table>
                                <br />
                                <hr>
                                <br />
                                <br />
                                <div class="invoice-bills">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="">Item ID</th>
                                                    <th class="w-150px">Description</th>
                                                    <th>Qty</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>{{ enum_payment_types()[$payment->payment_type] }}
                                                    <td>1</td>
                                                    <td>₦{{ number_format($payment->amount, 2) }}</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr style="font-weight: bold;">
                                                    <td colspan="2"></td>
                                                    <td colspan="2">Grand Total</td>
                                                    <td>&#8358;{{ number_format($payment->amount, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div><!-- .invoice-bills -->
                                    {{-- <p style="margin-bottom: 10px;">You can download attached invoice for details.</p> --}}
                                    <br/>
                                    <p style="margin-bottom: 15px;">For further information, kindly contact us at <a style="color: #0fac81; text-decoration:none;"
                                            href="mailto:info@niwa.gov.ng">info@niwa.gov.ng</a>, or visit our website
                                        at <a style="color: #0fac81; text-decoration:none;" target="_blank"
                                            href="https://niwa.gov.ng">www.niwa.gov.ng</a> anytime. </p>
                                    <p></p><br/>

                                    <p>
                                        Kind Regards,<br/>
                                        NIWA Team
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:100%;max-width:620px;margin:0 auto;">
                        <tbody>
                            <tr>
                                <td style="text-align: center; padding:25px 20px 0;">
                                    <p style="font-size: 13px;">Copyright © <?php echo date('Y'); ?> P2E Technologies. All rights
                                        reserved.
                                        <br>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
