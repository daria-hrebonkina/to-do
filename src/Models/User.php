<?php

namespace Models;


use core\Model;

/**
 * Class User
 * @package Models
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 */
class User extends Model
{
    protected static $fillable = ['id', 'login', 'password'];

}