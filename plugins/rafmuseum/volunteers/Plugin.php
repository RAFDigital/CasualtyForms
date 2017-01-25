<?php namespace RafMuseum\Volunteers;

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
        UsersController::extendFormFields(function($form, $model, $context) {
            $form->addTabFields([
                'age' => [
                    'label' => 'Age',
                    'type' => 'dropdown',
                    'emptyOption' => 'Select age (optional)',
                    'options' => array(
                        'u18' => 'Under 18',
                        '18-24' => '18 - 24',
                        '25-34' => '25 - 34',
                        '35-44' => '35 - 44',
                        '45-59' => '35 - 59',
                        '60+' => '60 plus'
                    ),
                    'tab' => $this->name
                ],
                'sex' => [
                    'label' => 'Sex',
                    'type' => 'dropdown',
                    'emptyOption' => 'Select sex (optional)',
                    'options' => array(
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ),
                    'tab' => $this->name
                ],
                'location' => [
                    'label' => 'Location',
                    'type' => 'dropdown',
                    'emptyOption' => 'Select location (optional)',
                    'options' => Countries::$list,
                    'tab' => $this->name
                ],
                'ethnicity' => [
                    'label' => 'Ethnicity',
                    'type' => 'dropdown',
                    'emptyOption' => 'Select ethnicity (optional)',
                    // https://en.wikipedia.org/wiki/Classification_of_ethnicity_in_the_United_Kingdom#Ethnicity_categories
                    'options' => array(
                        'white' => 'White',
                        'mixed' => 'Mixed / multiple ethnic groups',
                        'asian' => 'Asian / Asian British',
                        'black' => 'Black / African / Caribbean / Black British',
                        'other' => 'Other ethnic group'
                    ),
                    'tab' => $this->name
                ],
                'last_activity' => [
                    'label' => 'Last Activity',
                    'type' => 'text',
                    'disabled' => true,
                    'tab' => $this->name
                ]
            ]);
        });
    }
}
