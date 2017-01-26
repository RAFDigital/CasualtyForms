<?php namespace RafMuseum\Volunteers\Components;

use Cms\Classes\ComponentBase;

class Dashboard extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Dashboard Component',
            'description' => 'Overview of volunteer progress.'
        ];
    }

    public function onRun()
    {
        // Get the user.
        $user = $this->page['user'];
    }
}
