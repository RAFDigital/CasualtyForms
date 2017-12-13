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

            $model->hasMany = [
                'forms_completed' => [
                    'RafMuseum\CasualtyForms\Models\CasualtyForm',
                    'key' => 'completed_by_id',
                    'count' => true
                ],
                'forms_approved' => [
                    'RafMuseum\CasualtyForms\Models\CasualtyForm',
                    'key' => 'approved_by_id',
                    'count' => true
                ]
            ];
        });

        UsersController::extendFormFields(function($form, $model, $context) {
            $configFile = __DIR__ . '/config/volunteer_fields.yaml';
            $config = Yaml::parse(File::get($configFile));
            $form->addTabFields($config['fields']);
        });

        UsersController::extendListColumns(function($list, $model) {
            $list->addColumns([
                'forms_completed' => [
                    'label' => 'Forms Completed',
                    'relation' => 'forms_completed',
                    'valueFrom' => 'count',
                    'default' => 0
                ],
                'forms_approved' => [
                    'label' => 'Forms Approved',
                    'relation' => 'forms_approved',
                    'valueFrom' => 'count',
                    'default' => 0
                ]
            ]);
        });

        Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            if (get_class($controller) === 'RainLab\User\Controllers\Users') {
                // We want to inject some JS if we are in the Users controller.
                $controller->addJs('/plugins/rafmuseum/volunteers/assets/javascript/exportbutton.js');
            }
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
            'RafMuseum\Volunteers\Components\Totaliser' => 'totaliser'
        ];
    }
}
