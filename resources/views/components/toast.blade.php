<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
    <!-- Toast notifications will be inserted here -->
</div>

<style>
    .toast {
        min-width: 300px;
        max-width: 400px;
        padding: 1rem 1.25rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.3s ease-out;
        transition: all 0.3s ease;
    }

    .toast.hiding {
        animation: slideOut 0.3s ease-out;
        opacity: 0;
        transform: translateX(400px);
    }

    .toast-success {
        background-color: #10b981;
        color: white;
    }

    .toast-error {
        background-color: #ef4444;
        color: white;
    }

    .toast-warning {
        background-color: #f59e0b;
        color: white;
    }

    .toast-info {
        background-color: #3b82f6;
        color: white;
    }

    .toast-icon {
        flex-shrink: 0;
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-content {
        flex: 1;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }

    .toast-close {
        flex-shrink: 0;
        width: 1.5rem;
        height: 1.5rem;
        border: none;
        background: transparent;
        color: white;
        cursor: pointer;
        opacity: 0.8;
        font-size: 1.25rem;
        line-height: 1;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-close:hover {
        opacity: 1;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
</style>

<script>
    function showToast(message, type = 'success', duration = 3000) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };

        toast.innerHTML = `
            <div class="toast-icon">${icons[type]}</div>
            <div class="toast-content">${message}</div>
            <button class="toast-close" onclick="closeToast(this)">×</button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            closeToast(toast.querySelector('.toast-close'));
        }, duration);
    }

    function closeToast(button) {
        const toast = button.closest('.toast');
        toast.classList.add('hiding');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    // Show toast from Laravel session flash messages
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif

        @if(session('info'))
            showToast("{{ session('info') }}", 'info');
        @endif
    });
</script>
