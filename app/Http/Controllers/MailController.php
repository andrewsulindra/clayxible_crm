<?php

namespace App\Http\Controllers;

use App\Mail\weeksEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail()
    {
        $details = [
            'title' => 'Mail from Laravel 6',
            'body' => 'This is a test email.'
        ];

        Mail::to('andrew.sulindra@gmail.com')->send(new weeksEmail());

        return 'Email sent!';
    }
}
