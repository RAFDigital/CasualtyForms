/**
 * TranscriptionForm.js
 * All the scripts for the transcription form component.
 */
var TransriptionForm = (function(exports) {
    'use strict';

    /* Consts */
    var TOGGLE_ILLEGIBLE_CLASS = '.toggle-illegible',
        APPROVAL_STAGE_DATA_FIELD = '[name="approved_by_id"]',
        ILLEGIBLE_CHAR = { text: '?', datepicker: '0001-01-01' },
        FORM_TAG = 'form',
        INPUT_SELECTOR = 'input',
        SUBMIT_BUTTON = '[type="submit"]',
        INPUT_GROUP_CLASS = '.input-group',
        FORM_CONTROL_CLASS = '.form-control',
    /* Vars */
        backToApproval = false;

    /**
     * Function to action the user logout.
     * @param {object} event The click event.
     */
    function toggleIllegibleClick(event) {
        var $this = $(this),
            $input = $this.parents(INPUT_GROUP_CLASS).find(FORM_CONTROL_CLASS);

        $input.trigger('change'); // For approval change detection.
        $this.toggleClass('marked');
        $this.find('span').toggleClass('hidden');

        // Change the input.
        if ($this.hasClass('marked')) {
            $input.val(ILLEGIBLE_CHAR[$input.attr('type')]).prop('readonly', true);
        } else {
            $input.val('').prop('readonly', false);
        }

        event.preventDefault();
    }

    /**
     * Function for when fields have been changed in the approval stage.
     * @param {object} event The event object.
     * @param {object} $approvedDataField The jQuery object for the hidden field.
     */
    function approvalChange(event, $approvedDataField) {
        var $submitButton = $(SUBMIT_BUTTON),
            input = event.target,
            confirmMessage = 'Changing this value means the form goes back ' +
            'into the approval stage. Are you sure you want to do this?';

        // Let's ask the user if they want to do this.
        if (!confirm(confirmMessage)) {
            // This is a fix for the jQuery toggle things.
            if ($(input.parentNode).hasClass('toggle')) {
                $(input.parentNode).toggleClass('off');
            }

            // Change input back.
            input.checked = input.defaultChecked;
            input.value = input.defaultValue;

            return;
        }

        // Change the hidden approval input to a completed input.
        $approvedDataField.attr('name', 'completed_by_id');

        // Change the submit button visually.
        $submitButton.text('Save')
            .removeClass('btn-success')
            .addClass('btn-primary');

        $(input.form).find('.alert-approval').show();

        // We only want to do all this once.
        backToApproval = true;
    }

    /**
     * This translates the awful object you get from serializeArray to
     * something usable.
     * @param {object} object The form item object
     * @param {object} item The key value pair.
     * @return {object} The formatted object.
     */
    function translateSeriasedData(object, item) {
        object[item.name] = item.value;

        return object;
    }

    /**
     * Checks dates and sets custom validity
     * @param {string} first First date name.
     * @param {string} last Last date name.
     * @param {object} data The form data.
     * @param {object} input The current input element.
     */
    function compareDates(first, last, data, input) {
        // Check we have both the dates present.
        if (data[first] && data[last]) {
            var firstDate = new Date(data[first]),
                lastDate = new Date(data[last]);

            // Now do the check.
            if (firstDate >= lastDate) {
                input.setCustomValidity('The last report date must be after the first.');
            } else {
                input.setCustomValidity('');
            }
        }
    }

    /**
     * Exposed function for checking dates are in the right order.
     * @param {object} input The current input element.
     */
    exports.checkDates = function(input) {
        var $form = $(input.form),
            data = $form.serializeArray().reduce(translateSeriasedData, {});

        // Check the birth/death dates.
        compareDates('birth_date', 'death_date', data, input);
        // Check the report dates.
        compareDates('report_date_first', 'report_date_last', data, input)
    }

    /**
     * Event handler for mousemove to detect inactivity.
     */
    jQuery(document).ready(function($) {
        var $illegibleToggles = $(TOGGLE_ILLEGIBLE_CLASS),
            $approvedDataField = $(APPROVAL_STAGE_DATA_FIELD);

        if ($illegibleToggles.length > 0) {
            // If we have some toggles on the page add the event listeners.
            $illegibleToggles.each(function(index) {
                $(this).click(toggleIllegibleClick);
            });
        }

        if ($approvedDataField.length > 0) {
            // Listen for changed inputs when
            $(this).on('change', INPUT_SELECTOR, function(event) {
                if (! backToApproval)
                    approvalChange(event, $approvedDataField);
            });
        }
    });

    return exports;
}(TransriptionForm || {}));
