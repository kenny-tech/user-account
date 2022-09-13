<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Account;
use App\Models\TransactionLog;

class AccountController extends Controller
{
    public function getAll()
    {
        try {
            $accounts = auth()->user()->accounts;

            if($accounts != null) {
                return response()->json([
                    'success' => true,
                    'data' => $accounts
                ]);
            } else {
                return $this->sendError($accounts = [], 'No account found');
            }
        } catch (\Exception $e) {
            return $this->sendError($accounts = [], $e->getMessage());
        }
    }
 
    public function create(Request $request)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'account_name' => 'required|unique:accounts',
            'user_id' => 'required' 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
           $data = [
                'user_id' => $request->user_id,
                'account_name' => $request->account_name,
                'account_number' => rand(1111111111,9999999999),
                'balance' => 0
           ];
           // create account
           $account = Account::create($data);

           // return response
           if ($account!=null) {
               return $this->sendSuccess($account, 'success');
           } else {
               return $this->sendError($account = [], 'Unable to create account. Please try again');
           }
        } catch (\Exception $e) {
            return $this->sendError($user = [], $e->getMessage());
        }
    }

    public function fund(Request $request)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'account_number' => 'required',
            'amount' => 'required',
            'user_id' => 'required' 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {

            $user_id = $request->user_id;
            $account_number = $request->account_number;
            $amount = $request->amount;
            $description = $request->description;

            // check if account number is correct
            $account = Account::select('id', 'balance')->where('user_id', $user_id)->where('account_number', $account_number)->first();

            if($account == null) {
                return $this->sendError($account = [], 'Wrong account number');
            } else {
                // get user balance and account id
                $account_id = $account->id;
                $balance = $account->balance;

                $new_balance = $amount + $balance;
                // update user's balance
                $data = [
                    'balance' => $new_balance
                ];
                $update_balance = Account::where('user_id', $user_id)->where('account_number', $account_number)->update($data);

                // log transaction 
                $transaction = [
                    'transaction_type' => 'credit',
                    'amount' => $amount,
                    'description' => $description,
                    'user_id' => $user_id,
                    'account_id' => $account_id
                ];
                $log_transaction = TransactionLog::create($transaction);

                // return response
                if ($update_balance and $log_transaction != null) {
                    return $this->sendSuccess($transaction, 'success');
                } else {
                    return $this->sendError($transaction = [], 'Unable to fund account. Please try again');
                }
            }
        } catch (\Exception $e) {
            return $this->sendError($user = [], $e->getMessage());
        }
    }

    public function transfer_fund(Request $request)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'account_number' => 'required',
            'amount' => 'required',
            'user_id' => 'required' 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {

            $user_id = $request->user_id;
            $account_number = $request->account_number;
            $amount = $request->amount;
            $description = $request->description;

            // check if account number exists
            $account = Account::select('id', 'balance')->where('account_number', $account_number)->first();

            if($account == null) {
                return $this->sendError($account = [], 'Account number does not exist');
            } else {
                // get user balance and account id
                $account_id = $account->id;
                $balance = $account->balance;

                $new_balance = $amount + $balance;
                // update user's balance
                $data = [
                    'balance' => $new_balance
                ];
                $update_balance = Account::where('account_number', $account_number)->update($data);

                // log transaction 
                $transaction = [
                    'transaction_type' => 'debit',
                    'amount' => $amount,
                    'description' => $description,
                    'user_id' => $user_id,
                    'account_id' => $account_id
                ];
                $log_transaction = TransactionLog::create($transaction);

                // return response
                if ($update_balance and $log_transaction != null) {
                    return $this->sendSuccess($transaction, 'success');
                } else {
                    return $this->sendError($transaction = [], 'Unable to transfer funds. Please try again');
                }
            }
        } catch (\Exception $e) {
            return $this->sendError($user = [], $e->getMessage());
        }
    }

    public function withdraw_fund(Request $request)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'account_number' => 'required',
            'amount' => 'required',
            'user_id' => 'required' 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {

            $user_id = $request->user_id;
            $account_number = $request->account_number;
            $amount = $request->amount;
            $description = $request->description;

            // check if account number exists
            $account = Account::select('id', 'balance')->where('account_number', $account_number)->first();

            if($account == null) {
                return $this->sendError($account = [], 'Account number does not exist');
            } else {
                // get user balance and account id
                $account_id = $account->id;
                $balance = $account->balance;

                if ($balance == 0 or $amount > $balance) {
                    return $this->sendError($account = [], 'Insufficient fund');
                } else {
                    $new_balance = $balance - $amount;
                    // update user's balance
                    $data = [
                        'balance' => $new_balance
                    ];
                    $update_balance = Account::where('account_number', $account_number)->update($data);
    
                    // log transaction 
                    $transaction = [
                        'transaction_type' => 'debit',
                        'amount' => $amount,
                        'description' => $description,
                        'user_id' => $user_id,
                        'account_id' => $account_id
                    ];
                    $log_transaction = TransactionLog::create($transaction);
    
                    // return response
                    if ($update_balance and $log_transaction != null) {
                        return $this->sendSuccess($transaction, 'success');
                    } else {
                        return $this->sendError($transaction = [], 'Unable to withdraw funds. Please try again');
                    }
                }
            }
        } catch (\Exception $e) {
            return $this->sendError($user = [], $e->getMessage());
        }
    }
 
}
