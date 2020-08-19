<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LessonsController extends Controller
{
    public function basic()	{
	    return 'Access to basic content';
	}
	public function pro()
	{
	    return 'Access to pro content!';
	}
}
