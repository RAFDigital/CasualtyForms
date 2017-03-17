<?php namespace RafMuseum\Volunteers\Models;

use Backend\Models\ExportModel;
use RainLab\User\Models\User;

/**
 * Model
 */
class VolunteerExport extends ExportModel
{
    /**
     * Export the data.
     */
    public function exportData($columns, $sessionKey = null)
    {
        $users = new User;

        $users = $users->with([
            'country' => function($query){ $query->addSelect('*'); },
            'state' => function($query){ $query->addSelect('*'); }
        ]);

        $users = $users->get();

        $users->each(function($user) use ($columns) {
            $user->addVisible($columns);
        });

        // Here we convert the relations into json for export.
        $collection = collect($users->toArray());

        trace_log('$collection', $collection);

        $data = $collection->map(function ($item) {
            if (is_array($item)) {
                foreach($item as $key => $value) {
                    if (is_array($value)) {
                        // We want to only display the name of the field.
                        $item[$key] = $value['name'];
                    }
                }
            }
            return $item;
        });

        return $data->toArray();
    }
}
