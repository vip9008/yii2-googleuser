<?php
namespace vip9008\googleuser\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\authclient\clients\GoogleOAuth;
use vip9008\googleapisclient\auth\OAuth2;
use vip9008\googleapisclient\Client;
use vip9008\googleuser\models\LoginForm;

class UserController extends Controller {
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'actions' => ['index', 'sign-in'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['sign-out'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'sign-out' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['sign-in']);
    }

    public function actionSignOut()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSignIn() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // GOOGLE API APP TOKENS
        global $GOOGLE_API_APP;

        $redirect_uri = Url::to(['user/sign-in'], true);

        $session = Yii::$app->session;
        $request = Yii::$app->request;

        // create GoogleOAuth object
        $google = new GoogleOAuth([
            'scope' => implode(' ', [
                'openid',
                'profile',
                'email',
            ]),
            'clientId' => $GOOGLE_API_APP['clientId'],
            'clientSecret' => $GOOGLE_API_APP['clientSecret'],
            'authUrl' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'tokenUrl' => 'https://www.googleapis.com/oauth2/v4/token',
        ]);

        if ($request->get('code', false)) {
            if ($session['state'] != $request->get('state')) {
                echo 'INVALID STATE PARAMETER 401';
            } else {
                unset($session['state']);

                $code = $request->get('code');
                $auth = $google->fetchAccessToken($code, ['redirect_uri' => $redirect_uri]);

                $json = new \stdClass();
                $json->access_token = $auth->params['access_token'];
                $json->token_type = $auth->params['token_type'];

                $api_client = new Client();
                $api_client->setClientId($GOOGLE_API_APP['clientId']);
                $api_client->setClientSecret($GOOGLE_API_APP['clientSecret']);
                $api_client->setRedirectUri($redirect_uri);

                $user_data = $this->decodeData($api_client, $auth->params['id_token']);

                if ($user_data['payload']['email_verified'] && $user_data['payload']['aud'] == $GOOGLE_API_APP['clientId']) {
                    // user authenticated successfully
                    $json->account = $user_data['payload']['iss'];
                    $json->email = $user_data['payload']['email'];
                    $json->name = $user_data['payload']['name'];
                    if (isset($user_data['payload']['picture'])) {
                        $json->picture = $user_data['payload']['picture'];
                    } else {
                        $json->picture = '';
                    }
                    $json->locale = $user_data['payload']['locale'];

                    $model = new LoginForm();
                    $model->email = $json->email;
                    $model->data = Json::encode($json);

                    if ($model->login()) {
                        return $this->goBack();
                    } else {
                        // login error
                        Yii::$app->getSession()->setFlash('error', 'Failed while registering user login.');
                    }
                } else {
                    // authentication error
                    Yii::$app->getSession()->setFlash('error', 'Failed to verify user authentication.');
                }

                return $this->goHome();
            }
        } else {
            $state = sha1(openssl_random_pseudo_bytes(1024));
            $session['state'] = $state;

            $url = $google->buildAuthUrl([
                'state' => $state,
                'redirect_uri' => $redirect_uri,
                'prompt' => 'select_account',
                'hl' => 'en',
            ]);

            Yii::$app->getResponse()->redirect($url);
        }
    }

    protected function decodeData(Client $client, $id_token) {
        $client_auth = new OAuth2($client);
        $ticket = $client_auth->verifyIdToken($id_token);
        $user_data = null;
        if ($ticket) {
            $user_data = $ticket->getAttributes();
        }
        return $user_data;
    }
}