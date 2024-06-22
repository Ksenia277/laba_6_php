<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegistrForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;
    public $role = 1;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'name'], 'required'],
            ['email', 'email'],
            [['username', 'email', 'password', 'name'], 'string', 'max' => 255],
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->name = $this->name;
        $user->role = $this->role;

        return $user->save() ? $user : null;
    }
}