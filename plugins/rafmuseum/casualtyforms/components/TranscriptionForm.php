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
        // Add the toggle control for this page.
        $this->addCss('/themes/casualty-forms/assets/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css');
        $this->addJs('/themes/casualty-forms/assets/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js');

        // Get the transcription stage ('new' or 'approve').
        $stage = $this->property('stage');

        if ($stage == 'new') {
            // See if there are any started forms to finish.
            $form = CasualtyForm::toFinish()->first();
        } elseif ($stage == 'approve') {
            // Get a form that needs approving.
            $form = CasualtyForm::toApprove()->first();
        }

        if (! $form && $stage == 'new') {
            // If there are no started forms, create a new one.
            $form = new CasualtyForm();
            $form->started_by_id = $this->page['user']['id'];
            $form->save();

            // Create the expected filename.
            $form->filename = $this->idToFilename($form->id);

            // Get the full file path.
            $filePath = base_path() . config('cms.storage.media.path') . $form->filename;

            // Check if it exists.
            if ( ! file_exists($filePath)) {
                // Nuke it, tell the user, and move on.
                $form->delete();
                Flash::error('The image file for form ' . $form->id . ' is missing.');
                return Redirect::to('/volunteer');
            }

            $form->update();
        }

        // Make some vars available in the front end.
        $this->page['form'] = $form;
        $this->page['stage'] = $stage;
    }

    /**
     * Save the current form as additional page.
     */
    public function onSaveAdditional()
    {
        return $this->onSave(true);
    }

    /**
     * Save the current form.
     * @param bool $additionalPage If this record is an "Additional page"
     */
    public function onSave($additionalPage = false)
    {
        // Get the right Casualty Form to update.
        $casualtyForm = CasualtyForm::find(post('id'));

        // Update the values.
        // $casualtyForm->fill(post()); // Annoyingly doesn't work.
        foreach(post() as $key => $value) {
            $casualtyForm[$key] = $value ? $value : null;
        }

        // Stupid case for the checkbox.
        if (! post('medical_information')) {
            $casualtyForm['medical_information'] = 0;
        }

        // And additional form.
        if ($additionalPage) {
            $casualtyForm['additional_page'] = 1;
        }

        $formsCompleted = null;

        // Now get the number of any forms completed by the user.
        if (post('completed_by')) {
            $formsCompleted = CasualtyForm::where(
                'completed_by_id', post('completed_by')
            )->count() + 1; // Add one to include this one.
        }

        // Update model.
        $casualtyForm->update();

        Flash::success('Form transcribed.');

        if (in_array($formsCompleted, array_keys(config('casualtyforms.surveys')))) {
            // Redirect to survey page if based on config settings.
            return Redirect::to('/volunteer/survey/' . $formsCompleted);
        }

        return Redirect::to('/volunteer');
    }

    /**
     * This converts the plain ID into the correct filename structure.
     * @param int $id The recrod ID
     */
    protected function idToFilename($id)
    {
        // Get the imagefile config.
        $imageFile = config('casualtyforms.imagefile');
        // Get first number group and sequence number.
        $group = floor($id / 10000) + 1;
        $sequence = sprintf('%04d', $id);

        return $imageFile['dir'] . DIRECTORY_SEPARATOR . $imageFile['prefix'] .
               $group . $imageFile['separator'] . $sequence . $imageFile['type'];
    }
}
