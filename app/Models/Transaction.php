<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getUserTransactions($user_id){
        return self::orderBy('updated_at', 'asc')->where('user_id', $user_id)->get();
    }

    public function getAllDepositedTransactions($user_id){
        return self::orderBy('updated_at', 'asc')->where(['user_id'=>$user_id, 'transaction_type'=>'DEPOSIT'])->get();
    }

    public function storeDeposit(array $formData){
        return self::create($formData);
    }
}
