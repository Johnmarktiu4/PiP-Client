<?php

namespace App\Controllers;

class ShoppingCart extends BaseController
{
    public function index(): string
    {
        return view('shopping_cart');
    }
}
