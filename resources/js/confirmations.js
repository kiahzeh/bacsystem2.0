// Confirmation dialog for important actions
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Password verification for sensitive actions
function verifyPasswordAndProceed(action, message) {
    const password = prompt(message || 'Please enter your password to continue:');
    if (password) {
        // Add password to the form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = action;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;

        const passwordInput = document.createElement('input');
        passwordInput.type = 'hidden';
        passwordInput.name = 'password';
        passwordInput.value = password;

        form.appendChild(csrfInput);
        form.appendChild(passwordInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Add confirmation to all delete buttons
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.dataset.confirmMessage || 'Are you sure you want to delete this item?';
            const form = this.closest('form');
            
            confirmAction(message, () => {
                if (this.dataset.requirePassword) {
                    verifyPasswordAndProceed(form.action, 'Please enter your password to delete:');
                } else {
                    form.submit();
                }
            });
        });
    });

    // Add confirmation to all update buttons
    const updateButtons = document.querySelectorAll('[data-confirm-update]');
    updateButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.dataset.confirmMessage || 'Are you sure you want to update this item?';
            const form = this.closest('form');
            
            confirmAction(message, () => {
                if (this.dataset.requirePassword) {
                    verifyPasswordAndProceed(form.action, 'Please enter your password to update:');
                } else {
                    form.submit();
                }
            });
        });
    });
}); 