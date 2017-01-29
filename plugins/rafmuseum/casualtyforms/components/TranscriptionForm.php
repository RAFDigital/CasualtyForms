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
        $stage = $this->property('stage');

        if( $stage == 'new') {
            // See if there are any started forms to finish.
            $form = CasualtyForm::toFinish()->first();
        } elseif( $stage == 'approve' ) {
            // Get a form that needs approving.
            $form = CasualtyForm::toApprove()->first();
        }

        if( ! $form && $stage == 'new' ) {
            // If there are no started forms, create a new one.
            $form = new CasualtyForm();
            $form->started_by_id = $this->page['user']['id'];
            // Need to check for images here.
            $form->save();
        }

        // Make some vars available in the front end.
        $this->page['form'] = $form;
        $this->page['stage'] = $stage;
    }

    public function onSave()
    {
        // Get the right Casualty Form to update.
        $casualtyForm = CasualtyForm::find(post('id'));

        // Update the values.
        // $casualtyForm->fill(post()); // Annoyingly doesn't work.
        foreach(post() as $key => $value) {
            $casualtyForm[$key] = $value ? $value : null;
        }

        $formsCompleted = null;

        // Now get the number of any forms completed by the user.
        if( post('completed_by') ) {
            $formsCompleted = CasualtyForm::where(
                'completed_by_id', post('completed_by')
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

        return Redirect::to('/volunteer');
    }
}
