@extends('master')
@section('content')
    <h6> Welcome, {{ $user->name }}. Your current balance is {{ $user->balance }} BDT.</h6>
    <br>

    @if(Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="table">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#depositModal">Withdrawl</button>

        <!-- Modal -->
        <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('withdrawl') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="depositModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="amount">Deposit amount</label>
                        <input type="text" name="amount" id="amount" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    </div>
                </form>
            </div>
        </div>

        <br>

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

