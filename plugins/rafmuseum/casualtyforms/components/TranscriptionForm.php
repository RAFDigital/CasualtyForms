<?php namespace RafMuseum\CasualtyForms\Components;

use Cms\Classes\ComponentBase;
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

    public function onRun()
    {
        // Get the form here ay.
    }

    public function onSave()
    {
        // Get the right Casualty Form to update.
        $casualtyForm = CasualtyForm::find(post('id'));
        // $casualtyForm = new CasualtyForm();

        // Update the values.
        $casualtyForm->fill(post());

        $formsCompleted = null;

        // Now get the number of any forms completed by the user.
        if( post('completed_by') ) {
            $formsCompleted = CasualtyForm::where(
                'completed_by_id', post('completed_by')
            )->count();
        }

        // Update model.
        $casualtyForm->update();
        // $casualtyForm->save();

        Flash::success('Form transcribed.');

        if($formsCompleted == '0' || $formsCompleted == '20') {
            // Redirect to survey page if this is the first, or 20th
            // form transcribed.
            return Redirect::to('/volunteer/survey');
        }

        return Redirect::to('/volunteer/transcribe/list');
    }
}
