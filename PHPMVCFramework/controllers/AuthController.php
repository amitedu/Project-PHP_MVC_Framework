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

            if ($registerModel->validate()) {
                echo 'success';
            }

            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $registerModel
            ]);
        }

        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }
}
