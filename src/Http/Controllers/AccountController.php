<?php
namespace Lakshya\AccountDeletion\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Lakshya\AccountDeletion\Models\DeletedUser;
use Illuminate\Support\Facades\Config;

class AccountController extends Controller
{
    public function requestDeletion(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $otp = rand(100000, 999999);
        Otp::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'created_at' => now(),
                'validated_at' => null, 
            ]
        );

        
        Mail::to($request->email)->send(new \App\Mail\OtpMail($otp));
        return response()->json(['message' => 'OTP sent to your email.']);
    }

    public function confirmDeletion(Request $request)
    {
        $request->validate(['email' => 'required|email', 'otp' => 'required|digits:6']);
        
        $otpRecord = Otp::where('email', $request->email)->first();

        // Check for OTP  if it exists and is valid
        if (!$otpRecord || $otpRecord->otp !== $request->otp || $otpRecord->created_at->addMinutes(1) < now()) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        // Mark the OTP as validated
        $otpRecord->validated_at = now();
        $otpRecord->save();

        $userTable = config('accountdeletion.user_table');
        $user = \DB::table($userTable)->where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        DeletedUser::create([
            'email' => $user->email,
            'name' => $user->name,
            'deleted_data' => json_encode($user),
            'deleted_at' => now(),
        ]);

        \DB::table($userTable)->where('email', $request->email)->delete();

        return response()->json(['message' => 'Account deleted successfully.']);
    }

    public function resendOtp(Request $request){
    $request->validate(['email' => 'required|email']);

    $otpRecord = Otp::where('email', $request->email)->first();

    if ($otpRecord && $otpRecord->created_at->addMinutes(1) > now()) {
        return response()->json(['message' => 'Please wait before requesting a new OTP.']);
    }
    $otp = rand(100000, 999999);

    Otp::updateOrCreate(
        ['email' => $request->email],
        [
            'otp' => $otp,
            'created_at' => now(),
            'validated_at' => null,
        ]
    );

    Mail::to($request->email)->send(new \App\Mail\OtpMail($otp));
    return response()->json(['message' => 'OTP resent to your email.']);
}


}
