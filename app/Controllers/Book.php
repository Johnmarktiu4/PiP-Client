<?php

namespace App\Controllers;

class Book extends BaseController
{
    public function index(): string
    {
        return view('book');
    }
}
