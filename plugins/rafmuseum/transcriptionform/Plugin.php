<?php namespace RafMuseum\TranscriptionForm;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'RafMuseum\TranscriptionForm\Components\TranscriptionForm' => 'transcriptionform'
        ];
    }

    public function registerSettings()
    {
    }
}
