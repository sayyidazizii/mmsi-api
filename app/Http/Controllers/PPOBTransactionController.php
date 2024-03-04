<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PPOBTransactionResource;
use App\Models\AcctSavingsTransferMutation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PPOBTransaction;
use App\Models\PPOBCompanyCipta;

class PPOBTransactionController extends Controller
{

    public function index()
    {
        //
        $ppob_transaction = PPOBTransactionResource::collection(PPOBTransaction::all());
        return $ppob_transaction;
    }

    public function store(Request $request)
    {
        $user = User::where('member_id', $request->member_id)->first();
        //
        $ppob_transaction                                       = new PPOBTransaction();
        $ppob_transaction->ppob_transaction_no                  = $request->ppob_transaction_no;
        $ppob_transaction->ppob_unique_code                     = $request->ppob_unique_code;
        $ppob_transaction->ppob_company_id                      = $request->ppob_company_id;
        $ppob_transaction->ppob_agen_id                         = $request->ppob_agen_id;
        $ppob_transaction->ppob_agen_name                       = $request->ppob_agen_name;
        $ppob_transaction->ppob_product_category_id             = $request->ppob_product_category_id;
        $ppob_transaction->ppob_product_id                      = $request->ppob_product_id;
        $ppob_transaction->savings_account_id                   = $request->savings_account_id;
        $ppob_transaction->savings_id                           = $request->savings_id;
        $ppob_transaction->member_id                            = $request->member_id;
        $ppob_transaction->branch_id                            = $request->branch_id;
        $ppob_transaction->transaction_id                       = $request->transaction_id;
        $ppob_transaction->ppob_transaction_amount              = $request->ppob_transaction_amount;
        $ppob_transaction->ppob_transaction_default_amount      = $request->ppob_transaction_default_amount;
        $ppob_transaction->ppob_transaction_admin_amount        = $request->ppob_transaction_admin_amount;
        $ppob_transaction->ppob_transaction_company_amount      = $request->ppob_transaction_company_amount;
        $ppob_transaction->ppob_transaction_fee_amount          = $request->ppob_transaction_fee_amount;
        $ppob_transaction->ppob_transaction_commission_amount   = $request->ppob_transaction_commission_amount;
        $ppob_transaction->ppob_transaction_date                = $request->ppob_transaction_date;
        $ppob_transaction->ppob_transaction_status              = $request->ppob_transaction_status;
        $ppob_transaction->ppob_transaction_remark              = $request->ppob_transaction_remark;
        $ppob_transaction->ppob_transaction_token               = $request->ppob_transaction_token;
        $ppob_transaction->data_state                           = $request->data_state;
        $ppob_transaction->created_id                           = $request->created_id;
        $ppob_transaction->imei                                 = $user['member_imei'];
        if($ppob_transaction->save())
        {
            return $ppob_transaction;
        }
    }

    public function show($id)
    {
        $ppob_transaction = PPOBTransaction::findOrFail($id);
        return new PPOBTransactionResource($ppob_transaction);
    }
    public function shows($id)
    {
        $ppob_transaction = new PPOBTransaction;

        $find = $ppob_transaction->find($id);

        return $find;
    }

    public function update(Request $request, $id)
    {
        $user = User::where('member_id', $request->member_id)->first();
        //
        $ppob_transaction                                           = PPOBTransaction::findOrFail($id);
        $ppob_transaction->ppob_transaction_no                      = $request->ppob_transaction_no;
        $ppob_transaction->ppob_unique_code                         = $request->ppob_unique_code;
        $ppob_transaction->ppob_company_id                          = $request->ppob_company_id;
        $ppob_transaction->ppob_agen_id                             = $request->ppob_agen_id;
        $ppob_transaction->ppob_agen_name                           = $request->ppob_agen_name;
        $ppob_transaction->ppob_product_category_id                 = $request->ppob_product_category_id;
        $ppob_transaction->ppob_product_id                          = $request->ppob_product_id;
        $ppob_transaction->savings_account_id                       = $request->savings_account_id;
        $ppob_transaction->savings_id                               = $request->savings_id;
        $ppob_transaction->member_id                                = $request->member_id;
        $ppob_transaction->branch_id                                = $request->branch_id;
        $ppob_transaction->transaction_id                           = $request->transaction_id;
        $ppob_transaction->ppob_transaction_amount                  = $request->ppob_transaction_amount;
        $ppob_transaction->ppob_transaction_default_amount          = $request->ppob_transaction_default_amount;
        $ppob_transaction->ppob_transaction_admin_amount            = $request->ppob_transaction_admin_amount;
        $ppob_transaction->ppob_transaction_company_amount          = $request->ppob_transaction_company_amount;
        $ppob_transaction->ppob_transaction_fee_amount              = $request->ppob_transaction_fee_amount;
        $ppob_transaction->ppob_transaction_commission_amount       = $request->ppob_transaction_commission_amount;
        $ppob_transaction->ppob_transaction_date                    = $request->ppob_transaction_date;
        $ppob_transaction->ppob_transaction_status                  = $request->ppob_transaction_status;
        $ppob_transaction->ppob_transaction_remark                  = $request->ppob_transaction_remark;
        $ppob_transaction->ppob_transaction_token                   = $request->ppob_transaction_token;
        $ppob_transaction->data_state                               = $request->data_state;
        $ppob_transaction->created_id                               = $request->created_id;
        $ppob_transaction->imei                                     = $user['member_imei'];
        if($ppob_transaction->save())
        {
            return $ppob_transaction;
        }
    }

    public function destroy($id)
    {
        //
        $ppob_transaction = PPOBTransaction::findOrFail($id);
        if($ppob_transaction->delete()){
            return $ppob_transaction;
        }
    }
    
    public function success_transaction($member_id)
    {
        

        $response = array(
            'error'						=> FALSE,
            'error_msg'					=> "",
            'error_msg_title'			=> "",
            'ppobtransaction'				=> "",
        );

        
        if($response["error"] == FALSE){
            $database 			        = env('DB_DATABASE3', 'forge');
            $ppob_company_id_json	    = PPOBCompanyCipta::where('ppob_company_database', '=', $database)->where('data_state', '=', 0)->first();
            $ppob_company_id            = $ppob_company_id_json['ppob_company_id'];

            $ppobtransaction            = PPOBTransaction::select('ppob_transaction.ppob_transaction_id', 'ppob_transaction.ppob_transaction_no', 'ppob_transaction.ppob_transaction_date', 'ppob_transaction.created_on', 'ppob_transaction.ppob_transaction_amount', 'ppob_transaction.ppob_transaction_status', 'ppob_product.ppob_product_name', 'ppob_product_category.ppob_product_category_name', 'ppob_transaction.ppob_transaction_remark')
                ->join('ppob_product', 'ppob_transaction.ppob_product_id', '=', 'ppob_product.ppob_product_id')
                ->join('ppob_product_category', 'ppob_transaction.ppob_product_category_id', '=', 'ppob_product_category.ppob_product_category_id')
                ->where('ppob_transaction.ppob_company_id', '=', $ppob_company_id)
                ->where('ppob_transaction.member_id', '=', $member_id)
                ->where('ppob_transaction.ppob_transaction_status', '=', 1)
                ->orderBy('ppob_transaction.ppob_transaction_id', 'DESC')
                ->limit(10)
                ->get();


            if(!$ppobtransaction){
                $response['error'] 				= TRUE;
                $response['error_msg_title'] 	= "No Data";
                $response['error_msg'] 			= "Error Query Data";
            }else{
                if (empty($ppobtransaction)){
                    $response['error'] 				= TRUE;
                    $response['error_msg_title'] 	= "No Data";
                    $response['error_msg'] 			= "Data Does Not Exist";
                } else {
                    $no = 0;
                    foreach ($ppobtransaction as $key => $val) {
                        if($val['ppob_transaction_amount'] == null){
                            $val['ppob_transaction_amount'] = 0;
                        }

                        $ppobtransaction[$key]['ppob_transaction_title']			= $val['ppob_product_category_name'];
                        $ppobtransaction[$key]['ppob_transaction_date']				= date('d M Y H:i:s', strtotime($val['created_on']));
                        $ppobtransaction[$key]['ppob_transaction_description']		= "No. trx ".$val['ppob_transaction_no']." Transaksi ".$val['ppob_product_category_name']." ".$val['ppob_product_name']." ".$val['ppob_transaction_remark'];
                        $ppobtransaction[$key]['ppob_transaction_amount']			= $val['ppob_transaction_amount'];
                        $ppobtransaction[$key]['ppob_transaction_status_name']		= "Sukses";

                        $no++;
                        
                    }
                    
                    $response['error'] 					= FALSE;
                    $response['error_msg_title'] 		= "Success";
                    $response['error_msg'] 				= "Data Exist";
                    $response['ppobtransaction'] 		= $ppobtransaction;
                }
            }
        }

        return $response;
    }
    
    public function fail_transaction($member_id)
    {
        $response = array(
            'error'						=> FALSE,
            'error_msg'					=> "",
            'error_msg_title'			=> "",
            'ppobtransaction'				=> "",
        );

        
        if($response["error"] == FALSE){
            $database 			        = env('DB_DATABASE3', 'forge');
            $ppob_company_id_json	    = PPOBCompanyCipta::where('ppob_company_database', '=', $database)->where('data_state', '=', 0)->first();
            $ppob_company_id            = $ppob_company_id_json['ppob_company_id'];

            $ppobtransaction            = PPOBTransaction::select('ppob_transaction.ppob_transaction_id', 'ppob_transaction.ppob_transaction_no', 'ppob_transaction.ppob_transaction_date', 'ppob_transaction.created_on', 'ppob_transaction.ppob_transaction_amount', 'ppob_transaction.ppob_transaction_status', 'ppob_product.ppob_product_name', 'ppob_product_category.ppob_product_category_name', 'ppob_transaction.ppob_transaction_remark')
                ->join('ppob_product', 'ppob_transaction.ppob_product_id', '=', 'ppob_product.ppob_product_id')
                ->join('ppob_product_category', 'ppob_transaction.ppob_product_category_id', '=', 'ppob_product_category.ppob_product_category_id')
                ->where('ppob_transaction.ppob_company_id', '=', $ppob_company_id)
                ->where('ppob_transaction.member_id', '=', $member_id)
                ->where('ppob_transaction.ppob_transaction_status', '=', 2)
                ->orderBy('ppob_transaction.ppob_transaction_id', 'DESC')
                ->limit(10)
                ->get();


            if(!$ppobtransaction){
                $response['error'] 				= TRUE;
                $response['error_msg_title'] 	= "No Data";
                $response['error_msg'] 			= "Error Query Data";
            }else{
                if (empty($ppobtransaction)){
                    $response['error'] 				= TRUE;
                    $response['error_msg_title'] 	= "No Data";
                    $response['error_msg'] 			= "Data Does Not Exist";
                } else {
                    $no = 0;
                    foreach ($ppobtransaction as $key => $val) {
                        if($val['ppob_transaction_amount'] == null){
                            $val['ppob_transaction_amount'] = 0;
                        }

                        $ppobtransaction[$key]['ppob_transaction_title']			= $val['ppob_product_category_name'];
                        $ppobtransaction[$key]['ppob_transaction_date']				= date('d M Y H:i:s', strtotime($val['created_on']));
                        $ppobtransaction[$key]['ppob_transaction_description']		= "No. trx ".$val['ppob_transaction_no']." Transaksi ".$val['ppob_product_category_name']." ".$val['ppob_product_name']." ".$val['ppob_transaction_remark'];
                        $ppobtransaction[$key]['ppob_transaction_amount']			= $val['ppob_transaction_amount'];
                        $ppobtransaction[$key]['ppob_transaction_status_name']		= "Gagal";

                        $no++;
                        
                    }
                    
                    $response['error'] 					= FALSE;
                    $response['error_msg_title'] 		= "Success";
                    $response['error_msg'] 				= "Data Exist";
                    $response['ppobtransaction'] 		= $ppobtransaction;
                }
            }
        }

        return $response;
    }

    public function getAcctSavingsAccountPPOBInHistory($member_id){
        $response = array(
            'error'								    => FALSE,
            'error_msg'							    => "",
            'error_msg_title'					    => "",
            'acctsavingsaccountppobinouthistory'	=> "",
        );

        $data = array(
            'member_id'			=> $member_id,
        );

        if($response["error"] == FALSE){
            
            $acctsavingsaccountlist			= AcctSavingsTransferMutation::select('acct_savings_transfer_mutation.savings_transfer_mutation_id', 'acct_savings_transfer_mutation.savings_transfer_mutation_date',  'acct_savings_transfer_mutation_to.mutation_id', 'acct_mutation.mutation_name', 'acct_savings_transfer_mutation_to.savings_account_id', 'acct_savings_account.savings_account_no', 'acct_savings_transfer_mutation_to.savings_id', 'acct_savings.savings_code', 'acct_savings.savings_name', 'acct_savings_transfer_mutation_to.member_id', 'core_member.member_name', 'acct_savings_transfer_mutation_to.savings_transfer_mutation_to_amount', 'acct_savings_transfer_mutation.created_on')->join('acct_savings_transfer_mutation_to', 'acct_savings_transfer_mutation.savings_transfer_mutation_id', '=', 'acct_savings_transfer_mutation_to.savings_transfer_mutation_id')->join('acct_mutation', 'acct_savings_transfer_mutation_to.mutation_id', '=', 'acct_mutation.mutation_id')->join('acct_savings_account', 'acct_savings_transfer_mutation_to.savings_account_id', '=', 'acct_savings_account.savings_account_id')->join('acct_savings', 'acct_savings_transfer_mutation_to.savings_id', '=', 'acct_savings.savings_id')->join('core_member', 'acct_savings_transfer_mutation_to.member_id', '=', 'core_member.member_id')->where('acct_savings_transfer_mutation.data_state', '=', 0)->where('acct_savings_transfer_mutation_to.member_id', '=', $data['member_id'])->where('acct_savings_transfer_mutation.savings_transfer_mutation_status', '=', 3)->orderBy('acct_savings_transfer_mutation.savings_transfer_mutation_id', 'DESC')->limit(10)->get();

            
            if(!$acctsavingsaccountlist){
                $response['error'] 				= TRUE;
                $response['error_msg_title'] 	= "No Data";
                $response['error_msg'] 			= "Error Query Data";
            }else{
                if (empty($acctsavingsaccountlist)){
                    $response['error'] 				= TRUE;
                    $response['error_msg_title'] 	= "No Data";
                    $response['error_msg'] 			= "Data Does Not Exist";
                } else {

                    foreach ($acctsavingsaccountlist as $key => $val){
                        $acctsavingsaccountppobinouthistory[$key]['ppob_transaction_title']		= 'PPOB Masuk';
                        $acctsavingsaccountppobinouthistory[$key]['ppob_transaction_date']			= date('d M Y H:i:s', strtotime($val['created_on']));
                        $acctsavingsaccountppobinouthistory[$key]['ppob_transaction_description']	= 'Bagi Hasil PPOB Ke Rekening '.$val['savings_account_no'].' a/n '.$val['member_name'];
                        $acctsavingsaccountppobinouthistory[$key]['ppob_transaction_amount']		= $val['savings_transfer_mutation_to_amount'];
                    }
                        
                    $response['error'] 								    = FALSE;
                    $response['error_msg_title'] 					    = "Success";
                    $response['error_msg'] 							    = "Data Exist";
                    $response['acctsavingsaccountppobinouthistory'] 	= $acctsavingsaccountppobinouthistory;
                }
            }
        }

        return $response;
    }
    
    public function getAcctSavingsAccountPPOBOutHistory($member_id){
        $response = array(
            'error'									=> FALSE,
            'error_msg'								=> "",
            'error_msg_title'						=> "",
            'acctsavingsaccountppobinouthistory'	=> "",
        );

        $data = array(
            'member_id'			=> $member_id,
        );

        if($response["error"] == FALSE){

            $acctsavingsaccountlist			= AcctSavingsTransferMutation::select('acct_savings_transfer_mutation.savings_transfer_mutation_id', 'acct_savings_transfer_mutation.savings_transfer_mutation_date',  'acct_savings_transfer_mutation_from.mutation_id', 'acct_mutation.mutation_name', 'acct_savings_transfer_mutation_from.savings_account_id', 'acct_savings_account.savings_account_no', 'acct_savings_transfer_mutation_from.savings_id', 'acct_savings.savings_code', 'acct_savings.savings_name', 'acct_savings_transfer_mutation_from.member_id', 'core_member.member_name', 'acct_savings_transfer_mutation_from.savings_transfer_mutation_from_amount', 'acct_savings_transfer_mutation.created_on')->join('acct_savings_transfer_mutation_from', 'acct_savings_transfer_mutation.savings_transfer_mutation_id', '=', 'acct_savings_transfer_mutation_from.savings_transfer_mutation_id')->join('acct_mutation', 'acct_savings_transfer_mutation_from.mutation_id', '=', 'acct_mutation.mutation_id')->join('acct_savings_account', 'acct_savings_transfer_mutation_from.savings_account_id', '=', 'acct_savings_account.savings_account_id')->join('acct_savings', 'acct_savings_transfer_mutation_from.savings_id', '=', 'acct_savings.savings_id')->join('core_member', 'acct_savings_transfer_mutation_from.member_id', '=', 'core_member.member_id')->where('acct_savings_transfer_mutation.data_state', '=', 0)->where('acct_savings_transfer_mutation_from.member_id', '=', $data['member_id'])->where('acct_savings_transfer_mutation.savings_transfer_mutation_status', '=', 3)->orderBy('acct_savings_transfer_mutation.savings_transfer_mutation_id', 'DESC')->limit(10)->get();

            
            if(!$acctsavingsaccountlist){
                $response['error'] 				= TRUE;
                $response['error_msg_title'] 	= "No Data";
                $response['error_msg'] 			= "Error Query Data";
            }else{
                if (empty($acctsavingsaccountlist)){
                    $response['error'] 				= TRUE;
                    $response['error_msg_title'] 	= "No Data";
                    $response['error_msg'] 			= "Data Does Not Exist";
                } else {

                    foreach ($acctsavingsaccountlist as $key => $val){
                        $acctsavingsaccountppobinouthistory[$key]['ppob_transaction_title']			= 'PPOB Keluar';
                        $acctsavingsaccountppobinouthistory[$key]['ppob_transaction_date']			= date('d M Y H:i:s', strtotime($val['created_on']));
                        $acctsavingsaccountppobinouthistory[$key]['ppob_transaction_description']		= 'Transaksi PPOB Dari Rekening '.$val['savings_account_no'].' a/n '.$val['member_name'];
                        $acctsavingsaccountppobinouthistory[$key]['ppob_transaction_amount']			= $val['savings_transfer_mutation_from_amount'];
                    }
                        
                    $response['error'] 									= FALSE;
                    $response['error_msg_title'] 						= "Success";
                    $response['error_msg'] 								= "Data Exist";
                    $response['acctsavingsaccountppobinouthistory'] 	= $acctsavingsaccountppobinouthistory;
                }
            }
        }


        return $response;
    }
}
