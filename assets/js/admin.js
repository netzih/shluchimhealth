/**
 * Admin JavaScript for Shluchim Health
 */

(function() {
    'use strict';

    // Confirm Delete Actions
    document.querySelectorAll('a[href*="delete="]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // Auto-dismiss Alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Character Counter for Textareas
    document.querySelectorAll('textarea[maxlength]').forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.style.cssText = 'text-align: right; font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;';

        const updateCounter = () => {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${remaining} characters remaining`;
            if (remaining < 50) {
                counter.style.color = '#ef4444';
            } else {
                counter.style.color = '#6b7280';
            }
        };

        textarea.parentNode.insertBefore(counter, textarea.nextSibling);
        updateCounter();
        textarea.addEventListener('input', updateCounter);
    });

    // Preview Featured Image
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    let preview = document.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'image-preview';
                        preview.style.cssText = 'max-width: 200px; margin-top: 1rem; border-radius: 6px;';
                        input.parentNode.appendChild(preview);
                    }
                    preview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Form Unsaved Changes Warning
    let formChanged = false;
    const forms = document.querySelectorAll('form:not([data-no-warn])');

    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                formChanged = true;
            });
        });

        form.addEventListener('submit', () => {
            formChanged = false;
        });
    });

    window.addEventListener('beforeunload', (e) => {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });

})();
