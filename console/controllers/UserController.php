<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use common\models\User;

class UserController extends Controller
{
    /**
     * Adds or updates user admin with password and assigns System Administrator full permissions.
     * 
     * Usage:
     *   php yii user/create-admin
     *   php yii user/create-admin [username] [password]
     *
     * @param string $username Default 'admin'
     * @param string $password Default 'Na12345678'
     * @return int
     */
    public function actionCreateAdmin($username = 'admin', $password = 'Na12345678')
    {
        $this->stdout("--- Creating/Updating Admin User ---\n", Console::FG_YELLOW);

        // 1. Find or create user
        $user = User::findOne(['username' => $username]);
        $isNew = false;
        if (!$user) {
            $user = new User();
            $user->username = $username;
            $user->email = $username . '@narono.com';
            $isNew = true;
        }

        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->group_id = 1; // 1 = Administrator in usergroup table

        if (!$user->save(false)) {
            $this->stderr("Failed to save user: " . print_r($user->getErrors(), true) . "\n", Console::FG_RED);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $actionText = $isNew ? "Created" : "Updated";
        $this->stdout("✓ {$actionText} user '{$user->username}' (ID: {$user->id}) with group_id = 1 (Administrator)\n", Console::FG_GREEN);

        // 2. Setup RBAC full permissions
        $auth = Yii::$app->authManager;
        if ($auth) {
            // Ensure System Administrator role exists
            $adminRole = $auth->getRole('System Administrator');
            if (!$adminRole) {
                $adminRole = $auth->createRole('System Administrator');
                $adminRole->description = 'System Administrator (Full Permissions)';
                $auth->add($adminRole);
                $this->stdout("✓ Created role 'System Administrator'\n", Console::FG_GREEN);
            }

            // Assign all permissions to System Administrator role
            $allPermissions = $auth->getPermissions();
            $permCount = 0;
            foreach ($allPermissions as $perm) {
                if (!$auth->hasChild($adminRole, $perm)) {
                    $auth->addChild($adminRole, $perm);
                    $permCount++;
                }
            }
            if ($permCount > 0) {
                $this->stdout("✓ Assigned {$permCount} permissions to 'System Administrator' role\n", Console::FG_GREEN);
            }

            // Assign System Administrator role to user
            if (!$auth->getAssignment($adminRole->name, $user->id)) {
                $auth->assign($adminRole, $user->id);
                $this->stdout("✓ Assigned 'System Administrator' role to user '{$user->username}'\n", Console::FG_GREEN);
            }

            // Also assign all existing roles so any role check passes
            $allRoles = $auth->getRoles();
            foreach ($allRoles as $role) {
                if (!$auth->getAssignment($role->name, $user->id)) {
                    $auth->assign($role, $user->id);
                }
            }
            $this->stdout("✓ Assigned all system roles to user '{$user->username}'\n", Console::FG_GREEN);
        }

        $this->stdout("\nSuccess! User '{$user->username}' is ready with password '{$password}'.\n", Console::FG_GREEN, Console::BOLD);
        return ExitCode::OK;
    }
}

