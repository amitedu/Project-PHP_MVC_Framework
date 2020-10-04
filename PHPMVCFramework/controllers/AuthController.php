<?php

namespace app\controllers;

use app\core\Request;
use app\core\Controller;
use app\core\Application;
use app\models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $user = new User();

        if ($request->getMethod() === 'post') {
            $user->loadData($request->getBody());

            if ($user->validate()) {
                return 'not success';
            }
        }

        $this->setLayout('auth');
        return $this->render('login');
    }


    public function register(Request $request)
    {
        $user = new User();

        if ($request->getMethod() === 'post') {
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()) {
                return 'registration success';
            }

            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $user
            ]);
        }

        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }
}
