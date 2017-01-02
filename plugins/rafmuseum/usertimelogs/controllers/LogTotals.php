<?php namespace RafMuseum\UserTimelogs\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use DB;

class LogTotals extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\ReorderController'];

    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RafMuseum.UserTimelogs', 'main-menu-item');
    }

    public function listExtendQuery($query)
    {
        // Big ol' custom query for the user timelog totals.
        // These have to have corresponding columns in the totals.yaml to display.
        $query->select(
            'user_id',
            DB::raw('(select name from `users` where `rafmuseum_usertimelogs_logs`.`user_id` = `users`.`id`) as `name`'),
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(signout_time) - TIME_TO_SEC(signin_time))) as time_logged')
        )->groupBy('user_id')->get();
    }
}
