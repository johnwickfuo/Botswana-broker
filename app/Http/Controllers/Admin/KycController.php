<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\User;
use App\Mail\NewNotification;
use App\Models\Kyc;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{

    public function processKyc(Request $request)
    {
        $request->validate([
            'kyc_id'  => 'required|integer|exists:kycs,id',
            'action'  => 'required|in:Accept,Reject',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $application = Kyc::find($request->kyc_id);
        $user = User::where('id', $application->user_id)->first();

        // will use API key
        if ($request->action == 'Accept') {
            User::where('id', $user->id)
                ->update([
                    'account_verify' => 'Verified',
                ]);
            $application->status = "Verified";
            $application->save();

            AuditLog::record('kyc.verified', $application, "Approved KYC for user #{$user->id} ({$user->email})", ['user_id' => $user->id]);
        } else {
            if (Storage::disk('public')->exists($application->frontimg) and Storage::disk('public')->exists($application->backimg)) {
                Storage::disk('public')->delete($application->frontimg);
                Storage::disk('public')->delete($application->backimg);
            }

            // Update the user verification status
            $user->account_verify = 'Rejected';
            $user->save();

            AuditLog::record('kyc.rejected', $application, "Rejected KYC for user #{$user->id} ({$user->email})", ['user_id' => $user->id]);

            // delete the application form database so user can resubmit application
            $application->delete();
        }

        try {
            Mail::to($user->email)->send(new NewNotification($request->message, $request->subject, $user->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send KYC status notification email to user. User: ' . $user->name . ' (' . $user->email . '), KYC ID: ' . $application->id . ', Action: ' . $request->action . ', Subject: ' . $request->subject . '. Error: ' . $e->getMessage());
        }

        return redirect()->route('kyc')->with('success', 'Action Sucessful!');
    }
}
