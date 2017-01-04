<?php namespace RafMuseum\UserTimelogs\Classes;

use RafMuseum\UserTimelogs\Models\UserTimelog;
use Redirect;

class Clock
{
    public static function in($account)
    {
        trace_log('Clock in.', date('Y-m-d H:i:s'));

        // First sign in.
        $signIn = $account->onSignin();

        // Then get the user.
        $user = $account->user();

        // Build the timelog object and save it.
        $timeLog = new UserTimelog();
        $timeLog->user_id = $user['id'];
        $timeLog->save();

        // Return the default sign in object.
        return $signIn;
    }

    public static function out($session)
    {
        trace_log('Clock out.', date('Y-m-d H:i:s'));

        // Redirect if the user is already logged out.
        if( ! $session->user() ) return Redirect::to('/');

        // Get the logged in user.
        $user = $session->user();

        // Get this users active time log.
        $activeTimeLog = UserTimeLog::where('user_id', $user['id'])
            ->whereNull('signout_time')->first();

        // Update it with logout time.
        $activeTimeLog->signout_time = date('Y-m-d H:i:s');
        $activeTimeLog->save();

        // Use session onLogout to logout.
        $session->onLogout();

        // Done, redirect home.
        return  Redirect::to('/');
    }
}
