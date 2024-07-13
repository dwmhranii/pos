<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class AssignController extends Controller
{
    public function actionSuperadmin($userId)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole('superadmin');
        $auth->assign($role, $userId);
        echo "Role superadmin telah diberikan kepada user dengan ID $userId.\n";
    }
}
