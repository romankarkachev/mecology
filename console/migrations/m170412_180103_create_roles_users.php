<?php

use yii\db\Migration;

/**
 * Создается роль "Полные права" и один пользователь с этой ролью.
 */
class m170412_180103_create_roles_users extends Migration
{
    public function up()
    {
        $role_adm = Yii::$app->authManager->createRole('root');
        $role_adm->description = 'Полные права';
        Yii::$app->authManager->add($role_adm);

        $user_adm = new \dektrium\user\models\User();
        $user_adm->username = 'root';
        $user_adm->email = 'root@gmail.com';
        $user_adm->password = '1Qazxsw2';
        $user_adm->confirmed_at = time();
        $user_adm->save();

        $user_adm->profile->name = 'Полные права';
        $user_adm->profile->save();
        Yii::$app->authManager->assign($role_adm, $user_adm->id);
    }

    public function down()
    {
        $role_adm = Yii::$app->authManager->getRole('root');
        Yii::$app->authManager->remove($role_adm);

        $user = \dektrium\user\models\User::findOne(['username' => 'root']);
        if ($user != null) $user->delete();
    }
}
