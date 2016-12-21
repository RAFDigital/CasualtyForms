<?php namespace RafMuseum\Transcription\Components;

use Cms\Classes\ComponentBase;
use Input;
use Flash;
use Redirect;
use Log;
use RafMuseum\CasualtyForms\Models\CasualtyForm;

class TranscriptionForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Trasncription Form',
            'description' => 'Transcription web form.'
        ];
    }

    public function onSave()
    {
        // Get the right Casualty Form.
        $casualtyForm = CasualtyForm::find(Input::get('id'));
        // Update the values.
        $casualtyForm->first_name = Input::get('first_name');
        $casualtyForm->rank = Input::get('rank');

        $casualtyForm->update();

        Flash::success('Form transcribed.');

        return Redirect::back();
    }
}
