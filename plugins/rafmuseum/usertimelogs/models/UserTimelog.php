<?php namespace RafMuseum\UserTimelogs\Models;

use DB;
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
        // `user_id` in table.
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

    /**
     * Scope a query to only include forms that aren't complete.
     */
    public function scopeLogTotals($query)
    {
        return $query->select(
            'user_id',
            DB::raw('(select name from `users` where `rafmuseum_usertimelogs_logs`.`user_id` = `users`.`id`) as `name`'),
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(signout_time) - TIME_TO_SEC(signin_time))) as `time_logged`')
        )->groupBy('user_id');
    }
}
