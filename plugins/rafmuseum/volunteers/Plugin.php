<?php namespace RafMuseum\Volunteers;

use Yaml;
use File;
use Page;
use Event;
use Backend;
use BackendMenu;
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
            'RafMuseum\Volunteers\Components\VolunteerAccount' => 'volunteeraccount',
            'RafMuseum\Volunteers\Components\Dashboard' => 'dashboard'
        ];
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        UsersModel::extend(function($model) {
            $model->implement = ['RainLab.Location.Behaviors.LocationModel'];

            $model->addFillable([
                'age',
                'sex',
                'ethnicity'
            ]);
        });

        UsersController::extendFormFields(function($form, $model, $context) {
            $configFile = __DIR__ . '/config/volunteer_fields.yaml';
            $config = Yaml::parse(File::get($configFile));
            $form->addTabFields($config);
        });

        Event::listen('backend.menu.extendItems', function($manager) {
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'users' => [
                    'label'       => 'Volunteers',
                    'url'         => Backend::url('rainlab/user/users'),
                    'icon'        => 'icon-user',
                    'permissions' => ['rainlab.users.*'],
                    'order'       => 100,
                ],
                'export' => [
                    'label'       => 'Export',
                    'url'         => Backend::url('rafmuseum/volunteers/volunteers/export'),
                    'icon'        => 'icon-download',
                    'permissions' => ['rafmuseum.volunteers.*'],
                    'order'       => 200,
                ]
            ]);
        });
    }
}
