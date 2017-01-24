<?php namespace RafMuseum\Volunteers;

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
                    'options' => array(
                        null => 'Select age (optional)',
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
                    'options' => array(
                        null => 'Select sex (optional)',
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ),
                    'tab' => $this->name
                ],
                'location' => [
                    'label' => 'Location',
                    'type' => 'text',
                    'tab' => $this->name
                ],
                'ethnicity' => [
                    'label' => 'Ethnicity',
                    'type' => 'dropdown',
                    // https://en.wikipedia.org/wiki/Classification_of_ethnicity_in_the_United_Kingdom#Ethnicity_categories
                    'options' => array(
                        null => 'Select ethnicity (optional)',
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
                    'type' => 'datepicker',
                    'tab' => $this->name
                ]
            ]);
        });
    }
}
