<?php namespace RafMuseum\UserTimelogs\Classes;

use RafMuseum\UserTimelogs\Models\UserTimelog;
use Redirect;
use Flash;

class Clock
{
    /**
     * For clocking users times in.
     * @param object $account
     */
    public static function in($account)
    {
        // First sign in.
        $signIn = $account->onSignin();

        // Then get the user.
        $user = $account->user();

        // Build the timelog object and save it.
        $timeLog = new UserTimelog();
        $timeLog->user_id = $user['id'];
        $timeLog->save();

        // Update the user `last_activity` to now.
        $user->last_activity = date("Y-m-d H:i:s");
        $user->update();

        // Return the default sign in object.
        return $signIn;
    }

    /**
     * For clocking users times out again.
     * @param object $session
     * @param string $type
     */
    public static function out($session, $type)
    {
        // Redirect if the user is already logged out.
        if (! $session->user()) return Redirect::to('/');

        // Get the logged in user.
        $user = $session->user();

        // Get this users active time log.
        $activeTimeLog = UserTimeLog::where('user_id', $user['id'])
            ->whereNull('signout_time')->first();

        // Update it with logout time (or last activity time if timedout).
        $activeTimeLog->signout_time = $type ? $user->last_activity : date('Y-m-d H:i:s');
        $activeTimeLog->save();

        // Use session onLogout to logout.
        $session->onLogout();

        if ($type == 'timeout') {
            Flash::warning("You have been logged out due to inactivity.");
        }

        if ($type == 'banned') {
            Flash::error("Your user has been banned from transcribing.");
        }

        // Done, redirect home.
        return  Redirect::to('/');
    }
}
