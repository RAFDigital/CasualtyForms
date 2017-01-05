<?php namespace RafMuseum\Processes;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'RafMuseum\Processes\Components\Page' => 'page'
        ];
    }

    public function registerSettings()
    {
    }
}
