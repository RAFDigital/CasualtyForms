<?php namespace RafMuseum\UserTimelogs;

use System\Classes\PluginBase;
use RafMuseum\UserTimelogs\Models\UserTimelog;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }
}

class Clock
{
    public static function in($account)
    {
        //trace_log('Clock in.', date('Y-m-d H:i:s'));

        // First sign in.
        $signIn = $account->onSignin();

        // Then get the user.
        $user = $account->user();

        // Build the timelog object and save it.
        $timeLog = new UserTimelog();
        $timeLog->user_id = $user['id'];
        $timeLog->signin_time = date('Y-m-d H:i:s');
        $timeLog->save();

        //trace_log($timeLog);

        // Return the default sign in object.
        return $signIn;
    }

    public static function out($session)
    {
        //trace_log('Clock out.', date('Y-m-d H:i:s'));

        // Return the logout function from the session.
        return $session->onLogout();
    }
}
