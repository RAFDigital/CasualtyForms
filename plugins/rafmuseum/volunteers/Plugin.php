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

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'rafmuseum.volunteers::lang.plugin.name',
            'description' => 'rafmuseum.volunteer::lang.plugin.description',
            'author'      => 'RAF Museum',
            'icon'        => 'icon-user'
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
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
            $form->addTabFields($config['fields']);
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

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'RafMuseum\Volunteers\Components\VolunteerAccount' => 'volunteeraccount',
            'RafMuseum\Volunteers\Components\Dashboard' => 'dashboard'
        ];
    }
}
