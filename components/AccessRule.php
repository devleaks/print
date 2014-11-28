<?php

namespace app\components;

/**
 * Usage:

public function behaviors()
{
    return [
        'access' => [
            'class' => 'yii\web\AccessControl',
            'ruleConfig' => [
                'class' => 'app\components\AccessRule' // <==== HERE IT IS!
            ],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['moderator', 'admin'],
                ],
            ],
        ],
    ];
}

 */

class AccessRule extends \yii\filters\AccessRule
{
    protected function matchRole($user)
    {
        if (empty($this->roles)) {
            return true;
        }
        foreach ($this->roles as $role) {
            if ($role === '?' && $user->getIsGuest()) {
                return true;
            } elseif ($role === '@' && !$user->getIsGuest()) {
                return true;
            } elseif (!$user->getIsGuest()) {
                // user is not guest, let's check his role (or do something else)
                if ($role === $user->identity->role) {
                    return true;
                }
            }
        }
        return false;
    }
}