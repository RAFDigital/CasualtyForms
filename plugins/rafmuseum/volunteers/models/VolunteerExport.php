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
        $users = User::all();

        $users->each(function($user) use ($columns) {
            $user->addVisible($columns);
        });

        return $users->toArray();
    }
}
