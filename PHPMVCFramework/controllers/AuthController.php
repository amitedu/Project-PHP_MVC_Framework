<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\core\Request;

class AuthController extends Controller {

    public function login(Request $request)
    {
        if($request->getMethod() === 'post') {
            return 'handle login submitted data';
        }
        
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request)
    {
        if($request->getMethod() === 'post') {
            return "handle submitted data";
        }
        
        $this->setLayout('auth');
        return $this->render('register');
    }
    
}