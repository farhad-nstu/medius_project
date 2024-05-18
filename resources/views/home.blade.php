@extends('master')
@section('content')
    <h6> Welcome, {{ $user->name }}. Your current balance is {{ $user->balance }} BDT.</h6>

    <div class="table">
        <table class="table datatable">
            <thead>
                <tr>
                    <th>Transaction type</th>
                    <th>Amount</th>
                    <th>Fee</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_type }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->fee }}</td>
                        <td>{{ Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
