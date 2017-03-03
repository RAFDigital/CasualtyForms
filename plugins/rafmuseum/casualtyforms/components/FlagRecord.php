<?php namespace RafMuseum\CasualtyForms\Components;

use Cms\Classes\ComponentBase;
use RafMuseum\CasualtyForms\Models\CasualtyForm;

class FlagRecord extends ComponentBase
{
    /**
     * Identifier value to load the record from the database.
     * @var string
     */
    public $identifierValue;

    public function componentDetails()
    {
        return [
            'name'        => 'Flag Record',
            'description' => 'Provides functionality for user flagging of records.'
        ];
    }

    public function onRun()
    {
        // Prepare vars.
        $this->identifierValue = $this->page['identifierValue'] = $this->property('identifierValue');
    }

    /**
     * AJAX function for form flagging.
     */
    public function onFlagRecord()
    {
        // Get the right Casualty Form to update.
        $casualtyForm = CasualtyForm::find($this->property('identifierValue'));

        // Update the values.
        $casualtyForm->fill(post());

        // Update.
        $casualtyForm->update();

        // Replace the component content with the flagged alert.
        return ['#flagRecordComponent' => $this->renderPartial('@flagged-alert')];
    }
}
