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

    public function getAllWithdrawlTransactions(Request $request){
        $user = User::find(auth()->id());
        if($request->method() == 'POST'){
            $this->validate($request, ['amount' => 'required|numeric']);
            $amount = $request->amount;
            $amountToBeCharged = $amount;
            $withdrawlRate = $user->account_type == 'INDIVIDUAL' ? 0.015 : 0.025;

            if($user->account_type == 'INDIVIDUAL'){
                if(Carbon::now()->isFriday()){
                    $withdrawlRate = 0;
                }else {
                    if($amount <= 1000){
                        $withdrawlRate = 0;
                    }else{
                        $amountToBeCharged = $amount - 1000;

                        $totalWithdrawlInCurrentMonth = $this->transaction->getTotalWithdrawlInCurrentMonth($user->id);
                        $total = $totalWithdrawlInCurrentMonth + $amountToBeCharged;
                        if($total <= 5000){
                            $withdrawlRate = 0;
                        } else{
                            $amountToBeCharged = $total - 5000;
                        }
                    }
                }
            } else{
                if($amount > 50000){
                    $withdrawlRate =  0.015;
                }
            }

            $fee = ($amountToBeCharged * $withdrawlRate) / 100;
            $totalAmountToBeDecreaseFromBalance = $amount + $fee;

            if($totalAmountToBeDecreaseFromBalance > $user->balance){
                $data['message'] = "You do not have sufficient amount to withdrawl. Your current balance is $user->balance. Your withdrwal amount with fee is $totalAmountToBeDecreaseFromBalance";
                return back()->with($data);
            }

            $formToSave = [
                'user_id' => $user->id,
                'transaction_type' => 'WITHDRAWL',
                'amount' => $amount,
                'fee' => $fee,
                'date' => Carbon::now(),
            ];
            try{
                DB::beginTransaction();
                $data['transaction'] = $this->transaction->setWithdrawl($formToSave);
                $user->balance = $user->balance - $totalAmountToBeDecreaseFromBalance;
                $user->save();
                DB::commit();
            } catch(\Exception $e){
                DB::rollBack();
                $data['message'] = $e->getMessage();
                return back()->with($data);
            }
            $data['transactions'] = $this->transaction->getAllDepositedTransactions(auth()->id());
            $data['user'] = $user;
            $data['message'] = 'Data saved successfully.';
            return back()->with($data);
        }
        try{
            $data['title'] = 'All Withdrawl Transactions';
            $data['transactions'] = $this->transaction->getAllWithDrawlTransactions(auth()->id());
            $data['user'] = $user;
            return view('withdrawlTransactions', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e);
        }
    }
}
