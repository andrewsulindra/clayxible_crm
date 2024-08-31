<?php

namespace App\Http\Controllers;

use App\Mail\weeksEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail()
    {
        $data = [
            'title' => 'Mail from Laravel 6',
            'body' => 'This is a test email.'
        ];

        $recipients = ['andrew.sulindra@gmail.com', 'blizzard_endru@yahoo.com'];

        Mail::to($recipients)->send(new weeksEmail($data));

        return 'Email sent!';
    }
}
