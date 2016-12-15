<?php namespace RafMuseum\Volunteers;

use System\Classes\PluginBase;
use Rainlab\User\Controllers\Users as UsersController;

class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = ['RainLab.User'];

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
