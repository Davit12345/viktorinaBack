<?php


namespace app\controllers\api\admin;


use app\helpers\Errors;
use app\helpers\Functions;
use app\models\Users;
use Yii;
use yii\db\Exception;

class UsersController extends ApiBaseController

{

    public function actionLogin()
    {
        $model = new \app\models\LoginForm();
        $model->username = Yii::$app->request->post('username');
        $model->password = Yii::$app->request->post('password');

        if ($model->username != 'admin') {
            $error = 'qaqy helav shef ches ap';
            return $this->createErrorResponse($error);

        }


        if ($model->login()) {

            $user = Yii::$app->user->identity;

            $token = $this->generateJwt($user);

            $refresh_token = $this->generateRefreshToken($user);
            return $this->createResponse([
                'user' => $user,
                'token' => (string)$token,
                'refresh_token' => $refresh_token->token
            ]);
        } else {
            return $this->createResponse(['error' => $model->errors], true);
        }
    }


    private function generateJwt(\app\models\Users $user)
    {
        try {
            $jwt = Yii::$app->jwt;

        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die();
        }
        $signer = $jwt->getSigner('HS256');
        $key = $jwt->getKey();
        $time = time();

        $jwtParams = Yii::$app->params['jwt'];

        return $jwt->getBuilder()
            ->issuedBy($jwtParams['issuer'])
            ->permittedFor($jwtParams['audience'])
            ->identifiedBy($jwtParams['id'], true)
            ->issuedAt($time)
            ->expiresAt($time + $jwtParams['expire'])
            ->withClaim('uid', $user->id)
            ->getToken($signer, $key);
    }

    /**
     * @throws yii\base\Exception
     */
    private function generateRefreshToken(\app\models\Users $user, \app\models\User $impersonator = null)
    {
        $refreshToken = \Yii::$app->security->generateRandomString(200);

        // TODO: Don't always regenerate - you could reuse existing one if user already has one with same IP and user agent
        $userRefreshToken = new \app\models\UserRefreshTokens([
            'user_id' => $user->id,
            'token' => $refreshToken,
            'ip' => Yii::$app->request->userIP,
            'user_agent' => Yii::$app->request->userAgent,
            'created' => gmdate('Y-m-d H:i:s'),
        ]);
        if (!$userRefreshToken->save()) {
            Errors::ex('Failed to save the refresh token: ', Errors::INTERNAL_SERVER_ERROR);

        }

        // Send the refresh-token to the user in a HttpOnly cookie that Javascript can never read and that's limited by path
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'refresh-token',
            'value' => $refreshToken,
            'httpOnly' => true,
            'sameSite' => 'none',
            'secure' => true,
            'path' => '/api/profile/refresh-token',  //endpoint URI for renewing the JWT token using this refresh-token, or deleting refresh-token
        ]));

        return $userRefreshToken;
    }

}
