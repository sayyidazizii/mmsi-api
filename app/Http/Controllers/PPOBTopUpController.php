<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PPOBTransactionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PPOBTopUp;

class PPOBTopUpController extends Controller
{
    public function store(Request $request)
    {
        //
        $ppob_topup = new PPOBTopUp();
        $ppob_topup->branch_id              = $request->branch_id;
        $ppob_topup->account_id             = $request->account_id;
        $ppob_topup->ppob_company_id        = $request->ppob_company_id;
        $ppob_topup->ppob_company_code      = $request->ppob_company_code;
        $ppob_topup->ppob_topup_no          = $request->ppob_topup_no;
        $ppob_topup->ppob_topup_date        = $request->ppob_topup_date;
        $ppob_topup->ppob_topup_amount      = $request->ppob_topup_amount;
        $ppob_topup->ppob_topup_status      = $request->ppob_topup_status;
        $ppob_topup->ppob_topup_remark      = $request->ppob_topup_remark;
        $ppob_topup->ppob_topup_token       = $request->ppob_topup_token;
        $ppob_topup->ppob_topup_token_void  = $request->ppob_topup_token_void;
        $ppob_topup->voided                 = $request->voided;
        $ppob_topup->voided_id              = $request->void_id;
        $ppob_topup->voided_on              = $request->voided_on;
        $ppob_topup->voided_remark          = $request->voided_remark;
        $ppob_topup->data_state             = $request->data_state;
        $ppob_topup->created_id             = $request->created_id;
        if($ppob_topup->save())
        {
            return $ppob_topup;
        }
    }
}
