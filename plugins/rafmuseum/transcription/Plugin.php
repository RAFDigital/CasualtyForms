<?php namespace RafMuseum\Transcription;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'RafMuseum\Transcription\Components\TranscriptionForm' => 'transcriptionform'
        ];
    }

    public function registerSettings()
    {
    }
}
