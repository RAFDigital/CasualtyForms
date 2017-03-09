/**
 * TranscriptionForm.js
 * All the scripts for the transcription form component.
 */

/**
 * ToggleIllegible
 * Module for the toggling of illegible fields.
 */
var ToggleIllegible = (function(exports) {
    'use strict';

    /* Vars. */
    var TOGGLE_ILLEGIBLE_CLASS = '.toggle-illegible',
        ILLEGIBLE_CHAR = { text: '?', datepicker: '0001-01-01' },
        INPUT_GROUP_CLASS = '.input-group',
        FORM_CONTROL_CLASS = '.form-control';

    /**
     * Function to action the user logout.
     */
    function toggleIllegibleClick(event) {
        var $this = $(this),
            $input = $this.parents(INPUT_GROUP_CLASS).find(FORM_CONTROL_CLASS);

        $this.toggleClass('marked');
        $this.find('span').toggleClass('hidden');

        // Change the input.
        if( $this.hasClass('marked') ) {
            $input.val(ILLEGIBLE_CHAR[$input.attr('type')]).prop('readonly', true);
        } else {
            $input.val('').prop('readonly', false);
        }

        event.preventDefault();
    }

    /**
     * Event handler for mousemove to detect inactivity.
     */
    jQuery(document).ready(function($) {
        var $illegibleToggles = $(TOGGLE_ILLEGIBLE_CLASS);

        if( $illegibleToggles.length > 0 ) {
            // If we have some toggles on the page add the event listeners.
            $illegibleToggles.each(function(index) {
                $(this).click(toggleIllegibleClick);
            });
        }
    });

    return exports;
}(ToggleIllegible || {}));
