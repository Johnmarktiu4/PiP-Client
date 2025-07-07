<?php

namespace App\Controllers;

class Menu extends BaseController
{
    public function index(): string
    {
        return view('menu');
    }
}
