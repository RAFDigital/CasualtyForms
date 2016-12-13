<?php namespace RafMuseum\TranscriptionForm\Components;

use Cms\Classes\ComponentBase;
use Input;
use Flash;
use Redirect;
use RafMuseum\CasualtyForms\Models\CasualtyForm;

class TranscriptionForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Trasncription Form',
            'description' => 'Whatever'
        ];
    }

    public function onSave()
    {
        $casualtyForm = new CasualtyForm();
        $casualtyForm->first_name = Input::get('first_name');
        $casualtyForm->rank = Input::get('rank');

        $casualtyForm->save();

        Flash::success('Form added.');

        return Redirect::back();
    }
}
