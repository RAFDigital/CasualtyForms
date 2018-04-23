<?php namespace RafMuseum\UserTimelogs;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = ['RafMuseum.Volunteers'];

    public function registerPermissions()
    {
        return [
            'rafmuseum.usertimelogs.access_logs' => [
                'label' => 'Access the timelogs',
                'tab' => 'User Timelogs',
                'roles' => ['developer', 'owner', 'museum-staff']
            ]
        ];
    }
}
