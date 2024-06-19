jQuery(document).ready(function ($) {
    $('#select_pdf').on('click', function (e) {
        e.preventDefault();

        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select PDF',
            button: {
                text: 'Select PDF'
            },
            multiple: false
        });

        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON();

            // Update hidden input with attachment ID
            $('#custom_pdf_id').val(attachment.id);

            // Remove any existing "Selected PDF" message
            $('#selected_pdf_display').remove();

            // Display the selected PDF's file name
            $('#select_pdf').after('<div id="selected_pdf_display"><p><strong>Selected PDF:</strong> <a href="' + attachment.url + '" target="_blank">' + attachment.filename + '</a></p></div>');

            // Fetch jpg image from pdf url and trigger image generation function
            generateImageFromPDF(attachment.url, attachment.filename);
        });

        file_frame.open();
    });

    // Function to generate an image from the first page of the PDF
    function generateImageFromPDF(pdfUrl, pdfFileName) {
        // AJAX call to send data to functions.php
        var xhr = new XMLHttpRequest();
        xhr.open('POST', ajaxurl, true); // Use WordPress's ajaxurl global variable
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Combine all variables into a single data string
        var data = 'action=generate_pdf_image' +
                   '&pdfUrl=' + encodeURIComponent(pdfUrl) +
                   '&pdfFileName=' + encodeURIComponent(pdfFileName);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Response from functions.php (if needed)
                console.log(xhr.responseText);
            }
        };

        // Send the data in a single request
        xhr.send(data);
    }
});
