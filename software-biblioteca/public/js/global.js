document.addEventListener('DOMContentLoaded', function() {
    // Desactivar clic derecho
    document.addEventListener('contextmenu', function (event) {
        event.preventDefault();
    });

    document.addEventListener('keydown', function(event) {
        // F12
        if (event.key === "F12") {
            event.preventDefault();
        }

        // Ctrl + Shift + I
        if (event.ctrlKey && event.shiftKey && event.key === 'I') {
            event.preventDefault();
        }

        // Ctrl + U
        if (event.ctrlKey && event.key === 'u') {
            event.preventDefault();
        }
    });
});