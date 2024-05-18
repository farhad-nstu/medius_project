<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = [
            [
                'user_id' => 1,
                'transaction_type' => 'DEPOSIT',
                'amount' => 30000,
                'date' => '2024-05-18'
            ],
            [
                'user_id' => 1,
                'transaction_type' => 'DEPOSIT',
                'amount' => 20000,
                'date' => '2024-05-15'
            ],
            [
                'user_id' => 1,
                'transaction_type' => 'WITHDRAWL',
                'amount' => 40000,
                'fee' => 10.5,
                'date' => '2024-05-18'
            ],
            [
                'user_id' => 2,
                'transaction_type' => 'DEPOSIT',
                'amount' => 30000,
                'date' => '2024-05-18'
            ],
            [
                'user_id' => 2,
                'transaction_type' => 'DEPOSIT',
                'amount' => 20000,
                'date' => '2024-05-15'
            ],
            [
                'user_id' => 2,
                'transaction_type' => 'WITHDRAWL',
                'amount' => 40000,
                'fee' => 10.5,
                'date' => '2024-05-18'
            ]
        ];
        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
