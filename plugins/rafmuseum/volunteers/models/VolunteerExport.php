<?php namespace RafMuseum\Volunteers\Models;

use Backend\Models\ExportModel;
use RainLab\User\Models\User;

/**
 * Model
 */
class VolunteerExport extends ExportModel
{
    /**
     * @var array Fillable fields
     */
    protected $fillable = ['subscribed_users_only'];

    /**
     * Export the data.
     */
    public function exportData($columns, $sessionKey = null)
    {
        $users = new User;

        // Include the country, state, and form count relationships.
        $users = $users->with([
            'country', 'state',
            'forms_completed', 'forms_approved'
        ]);

        // Do a different query based on options.
        if ($this->subscribed_users_only) {
            $users = $users->where('subscriber', true);
        }

        $users = $users->get();

        $users->each(function($user) use ($columns) {
            $user->addVisible($columns);
        });

        // Here we convert the relations into json for export.
        $collection = collect($users->toArray());

        $data = $collection->map(function ($item) {
            foreach($item as $key => $value) {
                if (is_array($value)) {
                    // Need to map the right values to the item.
                    if ($key == 'forms_completed' || $key == 'forms_approved') {
                        if(isset($value[0])) {
                            $item[$key] = $value[0]['count'];
                        } else {
                            $item[$key] = 0;
                        }
                    } else {
                        $item[$key] = $value['name'];
                    }
                }
            }
            
            return $item;
        });

        return $data->toArray();
    }
}
