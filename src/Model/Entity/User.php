<?php

declare(strict_types=1);
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity.
 *
 * @property int    $user_id
 * @property string $email
 * @property string $password
 */
class User extends Entity
{
    protected $_accessible = [
        'email' => true,
        'password' => true,
    ];

    protected $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password)
    {
        $hasher = new DefaultPasswordHasher();

        return $hasher->hash($password);
    }
}
