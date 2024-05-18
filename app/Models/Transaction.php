<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getUserTransactions(int $user_id){
        return self::orderBy('updated_at', 'asc')->where('user_id', $user_id)->get();
    }

    public function getAllDepositedTransactions(int $user_id){
        return self::orderBy('updated_at', 'asc')->where(['user_id'=>$user_id, 'transaction_type'=>'DEPOSIT'])->get();
    }

    public function storeDeposit(array $formData){
        return self::create($formData);
    }

    public function getAllWithDrawlTransactions(int $user_id){
        return self::orderBy('updated_at', 'asc')->where(['user_id'=>$user_id, 'transaction_type'=>'WITHDRAWL'])->get();
    }

    public function getTotalWithdrawlInCurrentMonth(int $user_id){
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $lastDayOfMonth = Carbon::now()->endOfMonth();
        return self::where(['user_id'=>$user_id, 'transaction_type'=>'WITHDRAWL'])->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])->sum('amount');
    }

    public function setWithdrawl(array $formData){
        return self::create($formData);
    }
}
