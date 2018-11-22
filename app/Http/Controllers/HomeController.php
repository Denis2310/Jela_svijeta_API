<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Meal;
use App\Category;
use App\Tag;

class HomeController extends Controller
{
    
    public function index() {
     
        return view('index');
    }
}
