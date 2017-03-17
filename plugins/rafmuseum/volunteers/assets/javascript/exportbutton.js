/**
 * ExportButton.js
 * This injects a button into the Users toolbar for exporting.
 */

 jQuery(document).ready(function($) {
     var $createUsersButton = $('.toolbar-item a[href$="/rainlab/user/users/create"]');
     var $exportUsersButton = $createUsersButton.clone();

     //$createUsersButton.text('New Volunteer');

     // Change the properties of the cloned button.
     $exportUsersButton.text('Export').attr({
         href: '../../rafmuseum/volunteers/volunteers/export',
         class: 'btn oc-icon-download btn-default'
     });

     // Insert the new eport button.
     $exportUsersButton.insertAfter($createUsersButton);
});
