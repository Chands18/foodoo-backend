<body style="display: inline-block; font-size:10px; text-align: center; width: 100%;">
    <p>Customer : {{ $item->user->name }}</p>
    <p>Product  : {{ $item->food->name }}</p>   
    <p>Qty : {{ $item->quantity }}</p>   
    <p>Trx ID : {{ $item->id }}</p>   
    <p>Total : {{ number_format($item->total) }}</p>
    <p>Created : {{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y H:i')}} </p>

</body>
