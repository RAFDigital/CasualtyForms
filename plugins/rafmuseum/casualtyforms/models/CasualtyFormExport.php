<?php namespace RafMuseum\CasualtyForms\Models;

use Backend\Models\ExportModel;

/**
 * Model
 */
 class CasualtyFormExport extends ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $forms = CasualtyForm::all();
        $forms->each(function($form) use ($columns) {
            $form->addVisible($columns);
        });
        return $forms->toArray();
    }
}
