<?php

namespace App\Http\Controllers;

use App\Http\Resources\PPOBTransactionResource;
use Illuminate\Http\Request;
use App\Models\AcctSavingsAccount;

class AcctSavingsAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        $acctaavingsaccount = AcctSavingsAccount::all();
        return $acctaavingsaccount;
    }

    public function getAcctSavingsAccountBalance(Request $request)
    {
        $fields = $request->validate([
            'member_id'         => 'required|string',
        ]);

        $acctaavingsaccount    =  AcctSavingsAccount::join('core_member', 'acct_savings_account.savings_account_id', '=', 'core_member.savings_account_id')
                                                    ->join('acct_savings', 'acct_savings_account.savings_id', '=', 'acct_savings.savings_id')
                                                    ->where('acct_savings_account.member_id', '=', $fields['member_id'])
                                                    ->get(
                                                        [
                                                            'acct_savings_account.savings_account_id', 
                                                            'acct_savings_account.savings_account_no', 
                                                            'acct_savings_account.savings_id', 
                                                            'acct_savings.savings_name',
                                                            'acct_savings_account.savings_account_last_balance'
                                                        ]);

        if ($acctaavingsaccount->isEmpty()){
            $acctaavingsaccount = array(
                'savings_account_id'            => "",
                'savings_account_no'            => "",
                'savings_id'                    => "",
                'savings_name'                  => "",
                'savings_account_last_balance'  => 0,
            );
            /* $acctaavingsaccount -> savings_account_id = "AAA";
            $acctaavingsaccount -> savings_account_no = "";
            $acctaavingsaccount -> savings_id = "";
            $acctaavingsaccount -> savings_name = "";
            $acctaavingsaccount -> savings_account_last_balance = 0; */
            
        } else {
            /*  if ($acctaavingsaccount['savings_account_id'] == 68184){
                $acctaavingsaccount = array(
                    'savings_account_id'            => $acctaavingsaccount['savings_account_id'],
                    'savings_account_no'            => $acctaavingsaccount['savings_account_no'],
                    'savings_id'                    => $acctaavingsaccount['savings_id'],
                    'savings_name'                  => "Damaspan",
                    'savings_account_last_balance'  => $acctaavingsaccount['savings_account_last_balance'],
                );
            } */
        }

        /* return $acctaavingsaccount;  */
        return new PPOBTransactionResource($acctaavingsaccount); 
    }
}
