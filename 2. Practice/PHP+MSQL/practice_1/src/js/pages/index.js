import { showAlert } from "../components/alert.js";
import { Dialog } from "../components/dialog.js";
import { Modal } from "../components/modal.js";

function initalizeData() {
    new Modal({
        title: (params) => {
            const { id = '' } = params;
            return id && id !== '' ? 'Edit User' : 'Create New User';
        },
        formId: 'user-form',
        buttonSelector: '[data-action="create-user"], [data-action="edit-user"]',
        content: (params) => {
            const { id = '', name = '', position = '', email = '', role = '' } = params;
            return `
                <div class="grid grid-cols-1 gap-4">
                    <input type="hidden" id="id" name="id" value="${id}">
                    <div class="form-group">
                        <label for="name" class="form-label after:content-['_*'] after:text-destructive">User Name</label>
                        <input type="text" id="name" placeholder="e.g. John Doe" name="name" class="form-input" value="${name}">
                    </div>
                    <div class="form-group">
                        <label for="title" class="form-label after:content-['_*'] after:text-destructive">Title</label>
                        <input type="text" id="title" placeholder="e.g. Software Engineer" name="title" class="form-input" value="${position}">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label after:content-['_*'] after:text-destructive">Email</label>
                        <input type="email" id="email" placeholder="e.g. user@example.com" name="email" class="form-input" value="${email}">
                    </div>
                    <div class="form-group">
                        <label for="role" class="form-label after:content-['_*'] after:text-destructive">Role</label>
                        <select id="role" name="role" class="form-input appearance-none">
                            <option value="" disabled ${!role ? 'selected' : ''}> Select role </option>
                            <option value="Member" ${role === 'Member' ? 'selected' : ''}>Member</option>
                            <option value="Admin" ${role === 'Admin' ? 'selected' : ''}>Admin</option>
                            <option value="Owner" ${role === 'Owner' ? 'selected' : ''}>Owner</option>
                        </select>
                    </div>
                </div>
            `;
        },
        onSubmit: async (formData) => {
            const { event, data } = formData;
            const submitButton = event.target.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            // Show loading state
            submitButton.innerHTML = 'Saving...';
            submitButton.disabled = true;

            const requiredFields = ['name', 'title', 'email', 'role'];
            const validations = () => {
                let isValid = true;
                // remove border color first
                requiredFields.forEach((field) => {
                    const input = event.target.querySelector(`[name="${field}"]`);
                    input && input.style.removeProperty('border-color');
                });

                requiredFields.forEach((field) => {
                    if (!data[field] || data[field].trim() === '') {
                        isValid = false;
                        const input = event.target.querySelector(`[name="${field}"]`);
                        input && input.style.setProperty('border-color', 'red');
                    }
                });

                return isValid;
            }

            const isValid = validations();
            if (!isValid) {
                showAlert('Please fill in all required fields.', 'error');
                // Reset button state
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                return;
            }

            try {
                const response = await fetch('./src/schema/users/create_update.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    // Show success message
                    showAlert(result.message, 'success');

                    // Close modal
                    event.target.querySelector('button[type="reset"]').click();

                    // Reload the page to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showAlert(result.message, 'error');

                    if (result.field) {
                        const input = event.target.querySelector(`[name="${result.field}"]`);
                        input && input.style.setProperty('border-color', 'red');
                    }
                }
            } catch (error) {
                console.error('Error saving user:', error);
                showAlert('An error occurred while saving the user. Please try again.', 'error');
            } finally {
                // Reset button state
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        }
    });
}

function deleteUser() {
    new Dialog({
        title: 'Delete User',
        formId: 'delete-user-form',
        buttonSelector: '[data-action="delete-user"]',
        content: (params) => {
            const { id = '', name = '' } = params;
            return `
                <input type="hidden" id="id" name="id" value="${id}">
                <p>Are you sure you want to delete user <strong>${name}</strong>? This action cannot be <span class="underline text-destructive/70 underline-offset-2">undone</span> .</p>
            `;
        },
        onSubmit: async (formData) => {
            const { event, data } = formData;
            const submitButton = event.target.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            // Show loading state
            submitButton.innerHTML = 'Deleting...';
            submitButton.disabled = true;
            try {
                const response = await fetch('./src/schema/users/delete.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    // Show success message
                    showAlert(result.message, 'success');
                    // Close dialog
                    event.target.querySelector('button[type="reset"]').click();
                    // Reload the page to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
                else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                console.error('Error deleting user:', error);
                showAlert('An error occurred while deleting the user. Please try again.', 'error');
            }
            finally {
                // Reset button state
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        }
    });
}

(() => {
    initalizeData();

    deleteUser();
})();