<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminHomeController extends Controller
{
    /** /admin : redirige vers la première page autorisée du rôle (compatible route:cache, sans closure) */
    public function __invoke(){
        return redirect()->route(auth()->user()->adminHomeRoute());
    }
}