<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_id }}</title>
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }


    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    .last-item{
        font-weight: 700;
        text-align: right;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <span>{{ env('APP_NAME') }}</span>
                            </td>
                            <td>
                                {{ $invoice->invoice_id }}<br>
                                {{ __('Created') }}: {{ $invoice->created_at->isoFormat('LL') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>
                    {{ __('Title') }}
                </td>
                <td>
                    {{ __('Details') }}
                </td>
            </tr>  
            <tr class="item">
                <td>
                    {{ __('Payment ID') }}
                </td>
                <td>
                    {{ $invoice->payment_id }}
                </td>
            </tr>
            <tr class="item">
                <td>
                    {{ __('Plan') }}
                </td>
                <td>
                    {{ $invoice->plan->name }}
                </td>
            </tr>
            <tr class="item">
                <td>
                    {{ __('Expire at') }}
                </td>
                <td>
                    {{ Carbon\Carbon::parse($invoice->will_expire)->isoFormat('LL') }}
                </td>
            </tr>
            <tr class="item">
                <td>
                    {{ __('Gateway') }}
                </td>
                <td>
                    {{ $invoice->getway->name }}
                </td>
            </tr>
            <tr class="item">
                <td>{{ __('User Name') }}</td>
                <td>{{ $invoice->user->name }}</td>
            </tr>
            <tr class="item">
                <td>{{ __('Amount') }}</td>
                <td>{{ $invoice->amount }}$</td>
            </tr>
            <tr class="item">
                <td>{{ __('Tax') }}</td>
                <td>{{ (($invoice->amount / 100) * $invoice->tax), 2 }} ({{ $invoice->tax }} %)</td>
            </tr>
            <tfoot> 
            <tr>
                <td colspan="2" class="last-item">
                    {{ __('Total') }} {{ round($invoice->amount + ($invoice->amount * $invoice->tax / 100), 2)}}$
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
