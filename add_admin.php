<?php
/**
 * Standalone script to add or update user 'admin' with password 'Na12345678'
 * and full permissions (System Administrator & group_id = 1).
 *
 * Usage:
 *   php add_admin.php
 *   php add_admin.php [username] [password]
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';
require __DIR__ . '/console/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/common/config/main-local.php',
    require __DIR__ . '/console/config/main.php',
    require __DIR__ . '/console/config/main-local.php'
);

$application = new yii\console\Application($config);

$username = $argv[1] ?? 'admin';
$password = $argv[2] ?? 'Na12345678';

echo "=== Adding/Updating User: {$username} ===\n";

// 1. User
$user = \common\models\User::findOne(['username' => $username]);
$isNew = false;
if (!$user) {
    $user = new \common\models\User();
    $user->username = $username;
    $user->email = $username . '@narono.com';
    $isNew = true;
}

$user->setPassword($password);
$user->generateAuthKey();
$user->status = \common\models\User::STATUS_ACTIVE;
$user->group_id = 1; // 1 = Administrator in usergroup table

if (!$user->save(false)) {
    echo "ERROR: Failed to save user:\n";
    print_r($user->getErrors());
    exit(1);
}

$action = $isNew ? "Created" : "Updated";
echo "[OK] {$action} user '{$user->username}' (ID: {$user->id})\n";
echo "[OK] Group set to: 1 (Administrator)\n";
echo "[OK] Status set to: 1 (Active)\n";

// 2. AuthManager / RBAC
$auth = Yii::$app->authManager;
if ($auth) {
    $roleName = 'System Administrator';
    $adminRole = $auth->getRole($roleName);
    if (!$adminRole) {
        $adminRole = $auth->createRole($roleName);
        $adminRole->description = 'System Administrator (Full Permissions)';
        $auth->add($adminRole);
        echo "[OK] Created RBAC role '{$roleName}'\n";
    }

    // Assign all permissions to role
    $perms = $auth->getPermissions();
    $addedPerms = 0;
    foreach ($perms as $perm) {
        if (!$auth->hasChild($adminRole, $perm)) {
            $auth->addChild($adminRole, $perm);
            $addedPerms++;
        }
    }
    if ($addedPerms > 0) {
        echo "[OK] Added {$addedPerms} permissions to '{$roleName}'\n";
    }

    // Assign System Administrator role to user
    if (!$auth->getAssignment($adminRole->name, $user->id)) {
        $auth->assign($adminRole, $user->id);
        echo "[OK] Assigned '{$roleName}' to user ID {$user->id}\n";
    }

    // Assign all roles to user
    $roles = $auth->getRoles();
    foreach ($roles as $r) {
        if (!$auth->getAssignment($r->name, $user->id)) {
            $auth->assign($r, $user->id);
        }
    }
    echo "[OK] Assigned all roles to user ID {$user->id}\n";
}

echo "\n============================================\n";
echo " SUCCESS!\n";
echo " Username: {$username}\n";
echo " Password: {$password}\n";
echo " Role: System Administrator (Full Permissions)\n";
echo "============================================\n";

