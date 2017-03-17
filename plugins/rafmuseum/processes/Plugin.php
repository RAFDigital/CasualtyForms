<?php namespace RafMuseum\Processes;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = ['RafMuseum.Volunteer'];

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'RafMuseum\Processes\Components\Page' => 'page'
        ];
    }
}
