<?php
namespace vip9008\googleuser\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $data;

    private $_user;

    public function rules()
    {
        return [
            // email is required and will be used as username
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    /**
     * Logs in a user using the provided email.
     * Register new user if not found and login.
     */
    public function login()
    {
        if ($this->user === null) {
            // user not registered
            $this->_user = new User();
            $this->_user->email = $this->email;
        }
        $this->_user->data = $this->data;
        $this->_user->save();
        
        return Yii::$app->user->login($this->_user);
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findIdentityByEmail($this->email);
        }

        return $this->_user;
    }
}
