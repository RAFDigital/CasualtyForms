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

        // Let's include the related columns.
        $forms = $forms->with([
            'completed_by' => function($query){ $query->addSelect('*'); },
            'approved_by' => function($query){ $query->addSelect('*'); }
        ]);

        // Do a different query based on options.
        if ($this->approved_forms_only) {
            $forms = $forms->approved()->get();
        } else {
            $forms = $forms->get();
        }

        $forms->each(function($form) use ($columns) {
            $form->addVisible($columns);
        });

        // Here we convert the relations into json for export.
        $collection = collect($forms->toArray());

        $data = $collection->map(function ($item) {
            if (is_array($item)){
                foreach($item as $key => $value) {
                    if (is_array($value)) {
                        // We want to only display the name.
                        $item[$key] = $value['name'];
                    }
                }
            }
            return $item;
        });

        return $data->toArray();
    }
}
