import "./bootstrap";
import Alpine from "alpinejs";

// Make Alpine available globally
window.Alpine = Alpine;

// Start Alpine
Alpine.start();

// Custom Alpine components
document.addEventListener("alpine:init", () => {
    // Flash message auto-hide
    Alpine.data("flashMessage", () => ({
        show: true,
        init() {
            if (this.$el.dataset.autoHide !== "false") {
                setTimeout(() => {
                    this.show = false;
                }, 5000);
            }
        },
    }));

    // Modal component
    Alpine.data("modal", () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
    }));

    // Dropdown component
    Alpine.data("dropdown", () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
    }));

    // Form validation
    Alpine.data("form", () => ({
        errors: {},
        validate() {
            // Basic form validation logic
            return Object.keys(this.errors).length === 0;
        },
    }));
});
