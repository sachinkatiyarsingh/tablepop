<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>invoice</title>
    
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
    
    .invoice_right{
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
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
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
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <img src="{{ asset('resources/images') }}/logo.png" style="width: 200px;height: 23px;">
                            </td>
                            
                            <td class="invoice_right">
                                <h3>Company Details</h3>
                                 Email : {{ $company['email'] ?? '' }} <br>
                                 Phone No. : {{ $company['phoneNo'] ?? '' }} <br>
                                 Address : {{ $company['address'] ?? '' }} <br>
                              
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{ $user['name'] ?? ' ' }}<br>
                                {{ $user['email'] ?? ' ' }}<br>
                                {{ $transaction['phoneNumber'] ?? ' ' }}<br>
                                {{ $transaction['street'] ?? ' ' }} {{ $transaction['country'] ?? ' ' }}<br>  
                            </td>
                            <td class="invoice_right">
                                Invoice Number: INV-{{ $transaction['transactionId'] ?? ' ' }}<br>
                                Order Number: {{ $transaction['transactionId'] ?? ' ' }}<br>
                                Order Date: {{ date("l d M,Y",strtotime($transaction['created_at'] ?? '')) ?? ' ' }}<br>
                                Payment Method: {{ $transaction['paymentMethod'] ?? ' ' }}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
            
        <table class="table table-striped">
            <thead>
                <tr  class="invoice_right">
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    
                </tr>
            </thead>
            <tbody>
                    <tr class="invoice_right">
                    <td>1</td>
                    <td>{{ $eventName ?? '' }}</td>
                    <td>1</td>
                    
                    <td class="right">{{ $transaction['amount']  ?? ''}}</td>
                   
                   
                    </tr>
          
            </tbody>
            </table>
            @php
                $price = !empty($transaction['amount']) ? $transaction['amount'] : 0;
                $vat = !empty($transaction['vat']) ? $transaction['vat'] : 0;
                if(!empty($vat)){
                    $vatAmount = $price*($vat/100);
                }else {
                    $vatAmount = 0;
                }
                $totalAmount = !empty($transaction['totalAmount']) ? $transaction['totalAmount'] : 0;
            @endphp
            <div class="col-lg-4 col-sm-5 ml-auto">
                <table class="table table-clear">
                <tbody>
                    <tr style="text-align: right">
                     <td>
                         <strong>Price</strong>
                        </td>
                        <td>$ {{ number_format($price,2)  ?? ''}}</td>
                        </tr>
                <tr style="text-align: right">
                <td>
                 <strong>VAT ({{ $vat  ?? ''}} %)</strong>
                </td>
                <td>$ {{ number_format($vatAmount,2) }}</td>
                </tr>
                <tr style="text-align: right">
                <td>
                <strong>Total</strong>
                </td>
                <td>
                <strong>$ {{ number_format($totalAmount,2)  ?? ''}}</strong>
                </td>
                </tr>
                </tbody>
                </table>
                
                </div>
    </div>
</body>
</html>