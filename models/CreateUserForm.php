<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CreateUserForm extends Model
{
    public $username;
    public $password;
    public $email;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['username', 'password', 'email'], 'required'],
            [['username', 'password', 'email'], 'trim'],
            [['username'],'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Only alphanumeric characters are allowed.'],
            [['password'],'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Only alphanumeric characters are allowed.'],
            [ 'email', 'email'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
        ];
    }

}
