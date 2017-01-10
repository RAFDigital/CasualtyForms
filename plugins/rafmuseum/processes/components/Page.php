<?php namespace RafMuseum\Processes\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Components\Session;
use Redirect;

class Page extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Page',
            'description' => 'Global page processes.'
        ];
    }

    public function onRun()
    {
        // Assign the project config vars.
        $this->page['config'] = array(
            'casualtyforms' => config('casualtyforms'),
            'cms' => config('cms')
        );

        // Get some global page vars going.
        $this->page['url'] = url();

        // Get the current user.
        $user = $this->page['user'];

        if( $user ) {
            // Get the timeout limit from config.
            $timeoutLimit = config('casualtyforms.timeoutLimit');

            // Calculate the time since the user's "last seen" date.
            $lastActivity = strtotime($user->last_activity);
            $now = strtotime(date("Y-m-d H:i:s"));
            $interval = ($now - $lastActivity);

            // Check if user has exeeded time limit.
            if( $interval >= $timeoutLimit ) {
                // Sign them out.
                return Redirect::to('volunteer/signout/timeout');
            } else {
                // If not, update the last activity to now.
                $user->last_activity = date("Y-m-d H:i:s");
                $user->update();
            }
        }
    }
}
