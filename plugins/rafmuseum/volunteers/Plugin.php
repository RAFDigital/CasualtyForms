<?php namespace RafMuseum\Volunteers;

use System\Classes\PluginBase;
use Rainlab\User\Controllers\Users as UsersController;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        UsersController::extendFormFields(function($form, $model, $context) {
            $form->addTabFields([
                'forms_transcribed' => [
                    'label' => 'Forms transcribed',
                    'type' => 'number',
                    'tab' => 'Volunteers'
                ]
            ]);
        });
    }
}
