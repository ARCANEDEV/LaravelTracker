<?php namespace Arcanedev\LaravelTracker\Models;

use Arcanedev\LaravelTracker\Contracts\Models\User as UserContract;

/**
 * Class     User
 *
 * @package  Arcanedev\LaravelTracker\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class User extends AbstractModel implements UserContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
}
