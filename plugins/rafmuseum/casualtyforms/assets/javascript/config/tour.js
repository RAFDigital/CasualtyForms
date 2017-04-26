var tourConfig = {
    transcribe: [
        {
            title: 'Transcribe a new form',
            content: 'Welcome to the form transcription page. Click the arrows to progress through a brief tour.',
            orphan: true,
            backdrop: true
        },
        {
            element: '.image-zoom',
            title: 'Form scan',
            content: 'This is the casualty form scan. Use your mouse to zoom and drag the image around.',
            placement: 'bottom',
            backdrop: true
        },
        {
            element: '.tour-matchfields',
            title: 'Transribe for each field',
            content: 'Look for the corresponding field in the scanned image and type exactly what you see into the text box.',
            placement: 'top',
            backdrop: true
        },
        {
            element: '.tour-requiredfields',
            title: 'Required fields',
            content: 'All fields marked with an asterisk <span class="text-danger">*</span> are required.',
            backdrop: true
        },
        {
            element: '.tour-illegible .input-group-btn',
            title: 'Illegible data',
            content: 'If you are struggling to read the handwriting for a particular field, click on the dropdown and select "Mark illegible".',
            placement: 'top',
            backdrop: true,
            onShown: function() {
                console.log('Clicking this', $('.tour-illegible .input-group-btn .btn'));
                $('.tour-illegible .input-group-btn .btn').click();
            }
        },
        {
            element: '.tour-nodata',
            title: 'No data',
            content: 'Unrequired fields may not necessarily have data present on the form. In this case, leave the inputs blank.',
            placement: 'left',
            backdrop: true
        },
        {
            element: '.tour-save',
            title: 'Finish',
            content: 'Be as thorough as you can and try not to miss anything. When you are happy everything is transcribed, click "Save".',
            placement: 'left',
            backdrop: true
        }
    ],
    approve: [
        {
            title: 'Approve a form',
            content: 'Welcome to the form approval page. Click the arrows to progress through a brief tour.',
            orphan: true,
            backdrop: true
        },
        {
            element: '.tour-requiredfields',
            title: 'Check all data is correct',
            content: 'Here your job is to double-check that another helpful user has entered all present information correctly.',
            backdrop: true
        },
        {
            element: '.tour-nodata',
            title: 'Incorrect data',
            content: 'If you have noticed a mistake in the original transcription, or you can see some information that has been missed, feel free to correct/enter the new information. When information is changed in the approval stage, the form will be sent back for a second approval by another user.',
            placement: 'left',
            backdrop: true
        },
        {
            element: '.tour-approve',
            title: 'Finish',
            content: 'Be as thorough as you can and try not to miss anything. If everything looks good to you, click "Approve".',
            placement: 'left',
            backdrop: true
        }
    ]
};
