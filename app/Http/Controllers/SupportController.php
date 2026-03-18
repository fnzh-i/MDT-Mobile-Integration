<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        Mail::raw($request->message, function ($mail) {
            $mail->to('justin.choa.coi@pcu.edu.ph')
            ->from(config('mail.from.address'), 'MDT Support')
            ->subject('MDT Support Request');
        });

        return back()->with('support_success', 'Your message has been sent!');
    }
}