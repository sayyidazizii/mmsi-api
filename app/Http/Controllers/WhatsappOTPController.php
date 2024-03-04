<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoreMember;
use App\Models\WhatsappOTP;
use App\Models\User;
use App\Models\SystemSetting;
use App\Models\LogLogin;
use GuzzleHttp\Exception\BadResponseException;
use DateTime;

class WhatsappOTPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
    }

    public function send($member_no)
    {
        // $fields = $request->validate([
        //     'member_id' => 'required',
        // ]);

        /* return $member_no; */

        $member_id = CoreMember::select('member_id')
        ->where('member_no', $member_no)
        ->first()
        ->member_id;

        /* return $member_id; */

        $member_phone = CoreMember::select('member_phone')
        ->where('member_no', $member_no)
        ->first()
        ->member_phone;

        $otp_code = random_int(100000, 999999);
        $whatsappotp = WhatsappOTP::create([
            'member_id'         => $member_id,
            'otp_code'          => $otp_code,
            'created_on'        => date('Y-m-d H:i:s'), 
        ]);

        $client = new \GuzzleHttp\Client();
        $token  = 'bPnrMuLisbVPrCXiD7nmyMv9kkK4HYw2g6cuQyS6XzfYy262dV';

        try {
            $response = $client->request('POST', 'https://app.ruangwa.id/api/send_message', [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/x-www-form-urlencoded',
                ],
                'form_params'   => [
                    'number'    => $member_phone,
                    'token'     => $token,
                    'message'   => "Kode OTP Anda ".$otp_code." untuk Aplikasi KopKar Menjangan Enam",
                ]
            ]);

            $response = $response->getBody()->getContents();

            return response()->json([
                'message'   => 'Kode OTP Sudah Dikirim ke Whatsapp',
            ], 200);
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            $jsonBody = (string) $response->getBody();

            return response()->json([
                'message'   => "Kode OTP Gagal Dikirim",
            ], 400);
        }
    }

    public function verification(Request $request)
    {
        $fields = $request->validate([
            'otp_code'          => 'required',
            'member_no'         => 'required|string',
            'password'          => 'required|string',
            'system_version'    => 'required|string',
            'imei'              => 'required|string',
        ]);

        /* $fields = $request->validate([
            'member_id'         => 'required',
            'otp_code'          => 'required',
            'member_no'         => 'required|string',
            'password'          => 'required|string',
            'system_version'    => 'required|string',
        ]); */

        $date = new DateTime;
        $date->modify('-5 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');

        $check_otp = WhatsappOTP::select()
        ->where('otp_code', $fields['otp_code'])
        ->where('created_on','>=',$formatted_date)
        ->first();

        

        if($check_otp){
            $user = User::where('member_no', $fields['member_no'])
            ->first();

            $token = $user->createToken('token-name')->plainTextToken;
    
            $response = [
                'data'  => $user,
                'token' => $token
            ];
            $user->token = $token;

            $version = SystemSetting::select('system_version')->first();

            $user->system_version = $version['system_version'];

            $user_state = User::findOrFail($user['user_id']);
            $user_state->member_imei = $fields['imei'];
            $user_state->log_state = 1;
            $user_state->save();
            
            $user->member_imei  = $fields['imei'];
            $user->member_token = "yo";

            $userlogin = User::where('member_no', $fields['member_no'])
                ->first();

            $message                        = "Login Berhasil";

            $log_login                      = new LogLogin();
            $log_login->member_id           = $userlogin['member_id'];
            $log_login->member_no           = $userlogin['member_no'];
            $log_login->imei                = $userlogin['member_imei'];
            $log_login->log_state           = $userlogin['log_state'];
            $log_login->block_state         = $userlogin['block_state'];
            $log_login->log_login_remark    = $message;

            $expired_on = date("Y-m-d H:i:s", strtotime('+1 hours'));

            $userlogin->member_user_status = 1;
            $userlogin->expired_on = $expired_on;

            if ($userlogin->save()){
                if($log_login->save())
                {
                    return response($user, 201);
                }
            }

            /* return $userlogin; */
            // return response()->json([
            //     'status'    => 1,
            //     'message'   => 'Kode OTP Sesuai',
            // ], 200);
        }else{
            return response()->json([
                'status'    => 0,
                'message'   => "Kode OTP Salah / Sudah Kadaluarsa",
            ], 400);
        }
    }

    public function resend($member_no)
    {
        // $fields = $request->validate([
        //     'member_id' => 'required',
        // ]);

        /* return $member_no; */

        $member_id = CoreMember::select('member_id')
        ->where('member_no', $member_no)
        ->first()
        ->member_id;

        /* return $member_id; */

        $member_phone = CoreMember::select('member_phone')
        ->where('member_no', $member_no)
        ->first()
        ->member_phone;

        $otp_code = random_int(100000, 999999);
        $whatsappotp = WhatsappOTP::create([
            'member_id'         => $member_id,
            'otp_code'          => $otp_code,
            'created_on'        => date('Y-m-d H:i:s'), 
        ]);

        $client = new \GuzzleHttp\Client();
        $token  = 'bPnrMuLisbVPrCXiD7nmyMv9kkK4HYw2g6cuQyS6XzfYy262dV';

        try {
            $response = $client->request('POST', 'https://app.ruangwa.id/api/send_message', [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/x-www-form-urlencoded',
                ],
                'form_params'   => [
                    'number'    => $member_phone,
                    'token'     => $token,
                    'message'   => "Kode OTP Anda ".$otp_code." untuk Aplikasi KopKar Menjangan Enam",
                ]
            ]);

            $response = $response->getBody()->getContents();

            return response()->json([
                'message'   => 'Kode OTP Sudah Dikirim ke Whatsapp',
            ], 200);
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            $jsonBody = (string) $response->getBody();

            return response()->json([
                'message'   => "Kode OTP Gagal Dikirim",
            ], 400);
        }
    }
}
