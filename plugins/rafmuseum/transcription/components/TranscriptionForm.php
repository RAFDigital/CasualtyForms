<?php namespace RafMuseum\Transcription\Components;

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
        // Get the right Casualty Form.
        $casualtyForm = CasualtyForm::find($inputs['id']);
        // Update the values.
        foreach($inputs as $key => $value) {
            $casualtyForm[$key] = $value ? $value : null;
        }
        // Update model.
        $casualtyForm->update();

        Flash::success('Form transcribed.');

        return Redirect::to('/volunteer/transcribe/list');
    }
}
