<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionServiceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use ValidatesRequests;

    public function __construct(private Transaction $transaction) {}

    public function index(){
        try{
            $data['title'] = 'All Deposited Transactions';
            $data['transactions'] = $this->transaction->getUserTransactions(auth()->id());
            $data['user'] = auth()->user();
            return view('home', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e);
        }
    }

    public function getAllDepositedTransactions(Request $request){
        if($request->method() == 'POST'){
            $this->validate($request, ['amount' => 'required|numeric']);
            $formToSave = [
                'user_id' => auth()->id(),
                'amount' => $request->amount,
                'transaction_type' => 'DEPOSIT',
                'date' => Carbon::now(),
            ];
            try{
                DB::beginTransaction();
                $data['transaction'] = $this->transaction->storeDeposit($formToSave);
                $user = User::find(auth()->id());
                $user->balance = $user->balance + $request->amount;
                $user->save();
                DB::commit();
            } catch(\Exception $e){
                DB::rollBack();
                $data['message'] = $e->getMessage();
                return back()->with($data);
            }
            $data['transactions'] = $this->transaction->getAllDepositedTransactions(auth()->id());
            $data['user'] = auth()->user();
            $data['message'] = 'Data saved successfully.';
            return back()->with($data);
        }
        try{
            $data['title'] = 'All Deposited Transactions';
            $data['transactions'] = $this->transaction->getAllDepositedTransactions(auth()->id());
            $data['user'] = auth()->user();
            return view('depositedTransactions', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e);
        }
    }
}
