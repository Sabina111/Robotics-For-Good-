$(document).ready(function() {
    // Check for URL parameters to show messages
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    const message = urlParams.get('message');

    if (status && message) {
        showMessage(status === 'success' ? 'success' : 'error', message);
    }

    // Handle form submission
    $('#registration-form').on('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitButton = $(this).find('button[type="submit"]');
        const originalText = submitButton.text();
        submitButton.prop('disabled', true).text('Submitting...');

        // Serialize form data
        const formData = $(this).serialize();

        // Send AJAX request
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message);
                    $('#registration-form')[0].reset();
                } else {
                    showMessage('error', response.message);
                }
            },
            error: function(xhr, status, error) {
                showMessage('error', 'Sorry, there was an error processing your registration. Please try again.');
                console.error('Registration error:', error);
            },
            complete: function() {
                // Reset button state
                submitButton.prop('disabled', false).text(originalText);
            }
        });
    });

    // Function to show messages
    function showMessage(type, message) {
        const messageDiv = $('#form-messages');
        messageDiv.removeClass('alert-success alert-danger')
            .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
            .html(message)
            .fadeIn();

        // Scroll to message
        $('html, body').animate({
            scrollTop: messageDiv.offset().top - 100
        }, 500);

        // Hide message after 5 seconds
        setTimeout(function() {
            messageDiv.fadeOut();
        }, 5000);
    }
}); 