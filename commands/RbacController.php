<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Periksa apakah role 'superadmin' sudah ada
        if (!$auth->getRole('superadmin')) {
            $superAdmin = $auth->createRole('superadmin');
            $auth->add($superAdmin);
        }

        // Periksa apakah role 'staff' sudah ada
        if (!$auth->getRole('staff')) {
            $staff = $auth->createRole('staff');
            $auth->add($staff);
        }

        // Periksa apakah permission 'manageUser' sudah ada
        if (!$auth->getPermission('manageUser')) {
            $manageUser = $auth->createPermission('manageUser');
            $auth->add($manageUser);
        }

        // Berikan permission 'manageUser' kepada 'superadmin' jika belum ada
        $superAdmin = $auth->getRole('superadmin');
        $manageUser = $auth->getPermission('manageUser');
        if (!$auth->hasChild($superAdmin, $manageUser)) {
            $auth->addChild($superAdmin, $manageUser);
        }

        echo "RBAC telah diinisialisasi.\n";
    }
}
