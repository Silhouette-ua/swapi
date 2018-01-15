<?php

namespace App\Http\Controllers;

class ErrorController extends Controller
{
    protected $message = 'Something went wrong';

    public function index()
    {
        if (session()->has('errorMessage')) {
            $this->message = session()->get('errorMessage');
            session()->forget('errorMessage');
        }

        return view('error/index', ['message' => $this->message]);
    }
}
