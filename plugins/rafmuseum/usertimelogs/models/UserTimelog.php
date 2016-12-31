<?php namespace RafMuseum\UserTimelogs\Models;

use Model;

/**
 * Model
 */
class UserTimelog extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
    ];

    /*
     * Relationships
     */
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User', 'table' => 'users']
    ];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rafmuseum_usertimelogs_logs';
}
