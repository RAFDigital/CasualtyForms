<?php namespace RafMuseum\CasualtyForms\Models;

use Backend\Models\ExportModel;

/**
 * Model
 */
 class CasualtyFormExport extends ExportModel
{
    /**
     * @var array Fillable fields
     */
    protected $fillable = ['approved_forms_only'];

    /**
     * Export the data.
     */
    public function exportData($columns, $sessionKey = null)
    {
        $forms = new CasualtyForm;

        // Do a different query based on options.
        if($this->approved_forms_only) {
            $forms = $forms->whereNotNull('approved_by_id')->get();
        } else {
            $forms = $forms->all();
        }

        $forms->each(function($form) use ($columns) {
            $form->addVisible($columns);
        });

        return $forms->toArray();
    }
}
