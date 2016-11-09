Google user module
==================
Enables signing in using a google account.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vip9008/yii2-googleuser "*"
```

or add

```
"vip9008/yii2-googleuser": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply create web api credentials at [developers console](https://console.developers.google.com/)
and add your sign-in link to Authorized redirect URIs ([link_to_your_yii2_app]user/sign-in).
Then add the module to your configuration file  :

```
'modules' => [
    'user' => [
        'class' => 'vip9008\googleuser\Module',
        'apiTokens' => [
            'clientId' => 'API_CREDENTIALS_CLIENT_ID',
            'clientSecret' => 'API_CREDENTIALS_CLIENT_SECRET',
        ],
    ],
    .
    .
    .
],
```

Available actions
-----------------

```/user/index```
```/user/sign-in```
```/user/sign-out```
