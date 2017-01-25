<?php namespace RafMuseum\Volunteers;

use Yaml;
use File;
use System\Classes\PluginBase;
use Rainlab\User\Controllers\Users as UsersController;
use Rainlab\User\Models\User as UsersModel;
use RafMuseum\Volunteers\Models\Countries;

class Plugin extends PluginBase
{
    /**
     * @var string Plugin name.
     */
    public $name = 'Volunteers';

    /**
     * @var array Plugin dependencies
     */
    public $require = ['RainLab.User'];

    public function registerComponents()
    {
        return [
            'RafMuseum\Volunteers\Components\VolunteerAccount' => 'volunteeraccount'
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        UsersModel::extend(function($model) {
            $model->addFillable([
                'age',
                'sex',
                'location',
                'ethnicity'
            ]);
        });

        UsersController::extendFormFields(function($form, $model, $context) {
            $configFile = __DIR__ . '/config/volunteer_fields.yaml';
            $config = Yaml::parse(File::get($configFile));
            $form->addTabFields($config);
        });
    }
}
