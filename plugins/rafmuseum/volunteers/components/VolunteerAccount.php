<?php namespace RafMuseum\Volunteers\Components;

use RainLab\User\Components\Account as AccountComponent;

class VolunteerAccount extends AccountComponent
{
    public function componentDetails()
    {
        return [
            'name' => 'Volunteer Account',
            'description' => 'Volunteer extention for the users plugin.'
        ];
    }
}
