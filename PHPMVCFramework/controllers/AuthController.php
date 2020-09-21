<?php

namespace app\controllers;

use app\core\Request;
use app\core\Controller;
use app\core\Application;
use app\models\RegisterModel;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $registerModel = new RegisterModel();

        if ($request->getMethod() === 'post') {
            $registerModel->loadData($request->getBody());

            if ($registerModel->validate() && $registerModel->register()) {
                return 'success';
            }
        }

        $this->setLayout('auth');
        return $this->render('login');
    }


    public function register(Request $request)
    {
        $registerModel = new RegisterModel();

        if ($request->getMethod() === 'post') {
            $registerModel->loadData($request->getBody());

            if ($registerModel->validate() && $registerModel->register()) {
                echo 'success';
            }

            // var_dump($registerModel->errors);
            echo $registerModel->errors ? 1 : 0;

            $this->render('register');
        }

        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }
}
