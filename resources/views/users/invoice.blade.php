<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Document</title>
    <link rel="stylesheet" href="css/pdf.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Invoice</h1>
        <hr>
        <div style="padding-bottom: 20px;margin-bottom: 20px">
            <div class="text-left" style="float: left">
                <span class="fw-bold">Date :</span> <span>{{$transaction->date->format('d F Y')}}</span>
            </div>
            <div class="text-right" style="float: right">
                <span>Invoice No : </span> <span>{{$transaction->id}}</span>
            </div>
        </div>
        <hr>
        <div style="padding-bottom: 100px">
            <div class="text-left" style="float: left">
                <strong>Invoices To :</strong>
                <div>{{$transaction->name}}</div>
                <div>{{$transaction->address}}</div>
                <div>{{$transaction->city}}</div>
                <div>{{$transaction->country}}</div>
            </div>
            <div class="text-right" style="float: right">
                <strong>Pay To :</strong>
                {{config('app.name')}}
            </div>
        </div>
        <hr>
        <table class="table table-bordered">
        <thead>
            <tr class="active">
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Premium account {{$transaction->subcription?->plan->name}}</td>
                <td>{{$transaction->formated_amount}} € </td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="active">
                <th scope="row" colspan="2" class="text-right">
                    HT : {{$transaction->formated_amount}} €
                </th>
            </tr>
            <tr class="active">
                <th scope="row" colspan="2" class="text-right">
                    TVA : {{$transaction->formated_amount}} €
                </th>
            </tr>
            <tr class="active">
                <th scope="row" colspan="2" class="text-right">
                    Total : {{$transaction->formated_amount}} €
                </th>
            </tr>
        </tfoot>
    </table>
    </div>
</body>
</html>
