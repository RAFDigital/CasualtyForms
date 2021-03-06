<?php namespace RafMuseum\Volunteers\Components;

use RainLab\User\Components\Account as AccountComponent;
use RafMuseum\UserTimelogs\Classes\Clock;

class VolunteerAccount extends AccountComponent
{
    public function componentDetails()
    {
        return [
            'name' => 'Volunteer Account',
            'description' => 'Volunteer extention for the users plugin.'
        ];
    }

    public function onVolunteerSignin()
    {
        return Clock::in($this);
    }

    /**
     * Update the user
     */
    public function onUpdate()
    {
        $user = $this->user();

        if( ! post('subscriber')) $user->subscriber = 0;

        parent::onUpdate();

        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }
    }
}
