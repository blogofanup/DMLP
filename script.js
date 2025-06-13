document.addEventListener('DOMContentLoaded', () => {
    const leadForm = document.getElementById('leadForm');
    const formMessages = document.getElementById('form-messages');

    if (leadForm) {
        leadForm.addEventListener('submit', async (event) => {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(leadForm);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                message: formData.get('message')
            };

            // Basic client-side validation
            if (!data.name || !data.email) {
                displayMessage('Please fill in all required fields (Name and Email).', 'error');
                return;
            }

            try {
                // Show a loading message
                displayMessage('Submitting your request...', 'info');

                const response = await fetch('submit.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // Indicate that we are sending JSON
                    },
                    body: JSON.stringify(data), // Send data as JSON
                });

                const result = await response.json(); // Assuming PHP responds with JSON

                if (response.ok && result.status === 'success') {
                    displayMessage('Thank you for your inquiry! We will get back to you soon.', 'success');
                    leadForm.reset(); // Clear the form
                } else {
                    displayMessage(result.message || 'There was an error submitting your form. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                displayMessage('An unexpected error occurred. Please try again later.', 'error');
            }
        });
    }

    /**
     * Displays a message in the formMessages div.
     * @param {string} message - The message to display.
     * @param {string} type - The type of message ('success', 'error', 'info').
     */
    function displayMessage(message, type) {
        formMessages.textContent = message;
        formMessages.className = `message-box ${type}`; // Apply dynamic classes
        formMessages.classList.remove('hidden');

        // Hide message after a few seconds, unless it's an error
        if (type !== 'error') {
            setTimeout(() => {
                formMessages.classList.add('hidden');
                formMessages.textContent = '';
            }, 5000); // Hide after 5 seconds
        }
    }
});
