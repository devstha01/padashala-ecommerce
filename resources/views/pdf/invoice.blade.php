{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
    {{--<meta charset="UTF-8">--}}
    {{--<title>Invoice</title>--}}
    {{--<link rel="stylesheet" href="{{asset('frontend/assets/css/bootstrap.min.css')}}" type="text/css">--}}
    {{--<link rel="stylesheet" href="{{asset('frontend/assets/css/print.css')}}" type="text/css">--}}
{{--</head>--}}
{{--<body>--}}
<table class="table table-borderless">
    <tr>
        <td colspan="8">
            <img alt=" " src="{{asset('image/gghl-logo.png')}}" class="top-left-image">
        </td>
        <td colspan="4">
            <div class="text-center">
                <b>Retail/Tax Invoice</b>
            </div>
            <div><b>Invoice Date:</b> <f
                        class="float-right">{{\Carbon\Carbon::parse($order->order_date)->format('d M, Y H:i')}}</f></div>
        </td>
    </tr>
    <tr class="bg-grey">
        <td colspan="6" class="text-center"><b>Sold By:</b></td>
        <td colspan="6" class="text-center"><b>Sold To:</b></td>
    </tr>
    <tr>
        <td colspan="2">
            <b>
                Seller Name: <br>
                Seller Address: <br>
                Seller Reg No. <br>
                Seller VAT No.
            </b>
        </td>
        <td colspan="4">
            {{$merchant->getBusiness->name??''}} <br>
            {{$merchant->address??''}} {{$merchant->city??''}}, {{$merchant->getCountry->name??''}} <br>
            {{$merchant->getBusiness->registration_number??''}} <br>
            <br>
        </td>
        <td colspan="3">
            <b>
                Customer Name: <br>
                Contact No: <br>
                Email Address: <br>
                Buyer PAN/VAT: <br>
            </b>
        </td>
        <td colspan="3">
            {{$order->getUser->name??''}} {{ $order->getUser->surname??'' }}<br>
            {{$order->getUser->contact_number??''}} <br>
            {{$order->getUser->email??''}}<br>

        </td>
    </tr>
    <tr class="bg-dark-grey">
        <td colspan="6" class="text-center"><b>Payment Term:</b></td>
        <td colspan="6" class="text-center"><b>Shipping Address</b></td>
    </tr>
    <tr>
        <td colspan="6" class="text-center p-2" style="font-size: 14px">{{$method}}</td>
        <td colspan="6" class="text-center">{{$order->address ??''}} {{$order->city ??''}}
            , {{$order->getCountry->name ??''}}</td>
    </tr>
    <tr class="border">
        <td colspan="2" class="border"><b>Invoice No.</b></td>
        <td colspan="4" class="border">{{$orderItem->first()->invoice}}</td>
        <td colspan="2" class="border"><b>Order Id:</b></td>
        <td colspan="4" class="border"></td>
    </tr>
    <tr>
        <td colspan="12"></td>
    </tr>
</table>
<table class="table table-borderless">
    <tr class="bg-grey">
        <td colspan="4"><b>Product Description</b></td>
        <td><b>Qty</b></td>
        <td><b>Unit Price</b></td>
        <td><b>Gross Amount</b></td>
        <td><b>Discount</b></td>
        {{--<td><b>Tax Rate[%]</b></td>--}}
        <td colspan="2"><b>Tax Amount</b></td>
        <td colspan="2"><b>Net Amount (InclusiveTax)</b></td>
    </tr>
    @foreach($orderItem as $item)
        <tr>
            <td colspan="4">{{$item->getProduct->name}}, {{$item->getProductVariant->name}} </td>
            <td>{{$item->quantity}}</td>
            <td>{{$item->sell_price}}</td>
            <td>{{$item->net_price}}</td>
            <td></td>
            <td colspan="2">{{$item->net_tax+0}}</td>
            <td colspan="2">{{$item->net_price+$item->net_tax}}</td>
        </tr>
    @endforeach
    @for($i= (0+count($orderItem));$i<8;$i++)
        <tr>
            <td colspan="12">&nbsp;</td>
        </tr>
    @endfor
    <tr class="border">
        <td class="border" colspan="4"><b>Shipping Charges</b></td>
        <td class="border" colspan="8"></td>
    </tr>
    <tr class="border">
        <td class="border" colspan="4"></td>
        <td class="border" colspan="2"><b>Total Gross Amount</b></td>
        <td class="border" colspan="2"><b>Total Discount</b></td>
        {{--<td class="border" colspan="2"><b>Final Net Amount</b></td>--}}
        <td class="border" colspan="2"><b>Tax Amount</b></td>
        <td class="border" colspan="2"><b>Total Amount</b></td>
    </tr>
    <tr class="border">
        <td class="border" colspan="4"></td>
        <td class="border" colspan="2">{{$total}}</td>
        <td class="border" colspan="2"></td>
        {{--<td colspan="2" class="border"></td>--}}
        <td class="border" colspan="2">{{$tax}}</td>
        <td class="border" colspan="2">{{$net_total}}</td>
    </tr>
</table>
<table class="table table-borderless">
    <tr>
        <td colspan="8"></td>
        <td colspan="4" class="text-center"><b>For Seller</b></td>
    </tr>
    <tr>
        <td colspan="8">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam at distinctio earum, enim
            facilis hic illo ipsam, officiis quaerat recusandae, veniam voluptatibus! A distinctio ducimus hic molestias
            odit rerum sequi.
        </td>
        <td colspan="4" class="border"></td>
    </tr>
    <tr>
        <td colspan="8"></td>
        <td colspan="4" class="text-center">[Authorized Signature]</td>
    </tr>
    <tr>
        <td colspan="7" class="border text-center"><b>Additional Information:</b></td>
        <td colspan="5" class="bg-grey"><b>This purchase made through</b></td>
    </tr>
    <tr>
        <td colspan="7" class="border"></td>
        <td colspan="5">
            <img alt=" " src="{{asset('image/gghl-logo.png')}}" class="top-left-image">
        </td>
    </tr>
    <tr>
        <td colspan="12" class="border"></td>
    </tr>
    <tr>
        <td class="text-center bg-grey" colspan="12">
            For queries
        </td>
    </tr>
</table>
{{--</body>--}}
{{--</html>--}}