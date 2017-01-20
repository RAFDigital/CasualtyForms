<?php namespace RafMuseum\CasualtyForms\Components;

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
            'description' => 'Transcription web form.'
        ];
    }

    public function onSave()
    {
        // Get all the inputs.
        $inputs = Input::get();

        // Get the right Casualty Form to update.
        $casualtyForm = CasualtyForm::find($inputs['id']);

        // Update the values.
        foreach($inputs as $key => $value) {
            $casualtyForm[$key] = $value ? $value : null;
        }

        $formsCompleted = null;

        // Now get the number of any forms completed by the user.
        if( isset($inputs['completed_by']) ) {
            $formsCompleted = CasualtyForm::where(
                'completed_by_id', $inputs['completed_by']
            )->count();
        }

        // Update model.
        $casualtyForm->update();

        Flash::success('Form transcribed.');

        if($formsCompleted == '0' || $formsCompleted == '20') {
            // Redirect to survey page if this is the first, or 20th
            // form transcribed.
            return Redirect::to('/volunteer/survey');
        }

        return Redirect::to('/volunteer/transcribe/list');
    }
}
