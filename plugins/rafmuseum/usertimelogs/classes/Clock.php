<?php namespace RafMuseum\UserTimelogs\Classes;

use RafMuseum\UserTimelogs\Models\UserTimelog;
use Session;
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

        // Get a unique session ID and set in session.
        $sessionId = str_random(32);
        Session::put('session_id', $sessionId);

        // Build the timelog object and save it.
        $timeLog = new UserTimelog();
        $timeLog->user_id = $user['id'];
        $timeLog->session_id = $sessionId;
        $timeLog->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $timeLog->save();

        // Update the user `last_activity` to now.
        $user->last_activity = date("Y-m-d H:i:s");
        $user->update();

        // Increment the sessions.
        $user->increment('sessions');

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

        // Get the logged in user and the session ID.
        $user = $session->user();
        $sessionId = Session::get('session_id');

        // Get this users active time log.
        $activeTimeLog = UserTimeLog::where('user_id', $user['id'])
                         ->where('session_id', $sessionId)->first();

        if ($activeTimeLog) {
            // Update it with logout time (or last activity time if timedout).
            $activeTimeLog->signout_time = $type
                ? $user->last_activity
                : date('Y-m-d H:i:s');
            $activeTimeLog->save();
        }

        // Use session onLogout to logout and forget session.
        $session->onLogout();
        Session::forget('session_id');

        if ($type == 'timeout') {
            Flash::warning("You have been logged out due to inactivity.");
        } else {
            Flash::warning("You have been logged out.");
        }

        // Done, redirect home.
        return  Redirect::to('/');
    }
}
