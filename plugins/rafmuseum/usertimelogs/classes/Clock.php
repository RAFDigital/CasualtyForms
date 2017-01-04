<?php namespace RafMuseum\UserTimelogs\Classes;

use RafMuseum\UserTimelogs\Models\UserTimelog;

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

        // Get the logged in user.
        $user = $session->user();

        // Get this users active time log.
        $activeTimeLog = UserTimeLog::where('user_id', $user['id'])
            ->whereNull('signout_time')->first();

        // Update it with logout time.
        $activeTimeLog->signout_time = date('Y-m-d H:i:s');
        $activeTimeLog->save();

        // Return the logout function from the session.
        return $session->onLogout();
    }
}
