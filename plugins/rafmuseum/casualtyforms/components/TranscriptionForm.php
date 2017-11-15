<?php namespace RafMuseum\CasualtyForms\Components;

use DateTime;
use Flash;
use Redirect;
use Session;
use Yaml;
use Cms\Classes\ComponentBase;
use RafMuseum\CasualtyForms\Models\CasualtyForm;
use RainLab\User\Models\User;

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
        // Get the form fields from the config.
        $formFields = Yaml::parseFile('plugins/rafmuseum/casualtyforms/models/casualtyform/fields.yaml');

        $formsPath = base_path() . config('cms.storage.media.path');

        // Add necessary files for this page.
        $this->loadAssets();

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
            $form->started_at = date('Y-m-d H:i:s');
            $form->save();

            // Create the expected filename.
            $form->filename = $this->idToFilename($form->id);

            // Get the full file path.
            $filePath = $formsPath . $form->filename;

            // Check if it exists.
            if ( ! file_exists($filePath)) {
                // Nuke it.
                $form->delete();

                // Let's see if this is THE END, let the user know either way.
                if ($form->id > $form->countFiles()) {
                    Flash::info('All forms have been transcribed, thank you.');
                } else {
                    Flash::error('The image file for form ' . $form->id . ' is missing.');
                }

                // Move on, mate, it's over.
                return Redirect::to('/volunteer');
            }

            $form->update();
        }

        if ($form) {
            // Get the neighbouring form filenames.
            $form->filenameBefore = $this->idToFilename($form->id - 1);
            $form->filenameAfter = $this->idToFilename($form->id + 1);

            // Check if the files even exist.
            if ( ! file_exists($formsPath . $form->filenameBefore)) $form->filenameBefore = false;
            if ( ! file_exists($formsPath . $form->filenameAfter)) $form->filenameAfter = false;

            // Get the date fields and convert them to human redable.
            $form->convertDates('j F Y');

            // Get the special states for the front end.
            $this->page['fieldSpecialStates'] = $form->getFieldSpecialStates();
        }

        // Check for survey
        $this->surveyCheck($this->page['user']->id);

        // Make some vars available in the front end.
        $this->page['form'] = $form;
        $this->page['formFields'] = $formFields['fields'];
        $this->page['stage'] = $stage;
    }

    /**
     * Save the current form.
     */
    public function onSave()
    {
        // Get the right Casualty Form to update.
        $casualtyForm = CasualtyForm::find(post('id'));

        // Update the values.
        $casualtyForm->fill(post());

        // Get the date fields and convert them before submitting to the DB.
        $casualtyForm->convertDates('Y-m-d H:i:s');

        // Nullify the parent_form_id if the form is no longer a child form.
        if( ! post('child_form')) $casualtyForm->parent_form_id = null;

        // Add the right timestamps and messages for each of the stages.
        if ($userId = post('completed_by_id')) {
            $casualtyForm->completed_at = date('Y-m-d H:i:s');
            Flash::success('Form transcribed.');
            // And check if survey criteria met.
            $this->surveyCheck(post('completed_by_id'));
        } else if (post('approved_by_id')) {
            $casualtyForm->approved_at = date('Y-m-d H:i:s');
            Flash::success('Form approved.');
        }

        // Update model.
        $casualtyForm->update();

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
        // Get sequence number (to 5 chars).
        $sequence = sprintf('%05d', $id);

        return $imageFile['dir'] . DIRECTORY_SEPARATOR .
               $imageFile['prefix'] . $sequence . $imageFile['type'];
    }

    /**
     * For checking if any surveys need to be presented.
     * @param int $userId The user ID
     */
    protected function surveyCheck($userId)
    {
        // Get the survey config.
        $surveyConfig = config('casualtyforms.surveys');
        // Get checks for both types of survey.
        $surveyChecks = array(
            'transcriptions' => CasualtyForm::completedByUser($userId),
            'sessions' => User::find($userId)->sessions
        );

        foreach ($surveyChecks as $type => $check) {
            // Do the check.
            if (in_array($check, array_keys($surveyConfig[$type]))
                && Session::get("surveys.$type.$check") === null) {

                // Trigger survey popup and set vars.
                $this->page['survey'] = array(
                    'link' => $surveyConfig[$type][$check],
                    'copy' => 'Thanks for taking the time to transcribe  for us.'
                        . ' Would you take some time to complete a survey?'
                );

                // Don't ask again.
                Session::push("surveys.$type.$check", true);
            }
        }
    }

    /**
     * Adds the css and js files required for the transcription form.
     */
    protected function loadAssets()
    {
        // Add the toggle control for this page.
        $this->addCss('assets/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css');
        $this->addJs('assets/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js');

        // Add js libs for the transcription form component.
        $this->addJs('assets/javascript/tourconfig.js');
        $this->addJs('assets/javascript/transcriptionform.js');

        // Add the css for the image radio selector.
        $this->addCss('formwidgets/parentform/assets/css/parentform.css');

        // Add Bootstrap tour.
        $this->addJs('assets/vendor/bootstrap-tour-0.11.0/build/js/bootstrap-tour.min.js');
        $this->addCss('assets/vendor/bootstrap-tour-0.11.0/build/css/bootstrap-tour.min.css');
    }
}
