<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 *
 * @property integer id
 * @property float balance
 */
class User extends Model
{
    public $timestamps = false;

    protected $fillable = ['email', 'password'];
    protected $hidden = ['password'];
}