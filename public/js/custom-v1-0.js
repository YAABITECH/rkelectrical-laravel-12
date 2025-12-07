const themeSwitcher = document.querySelector('#theme-switcher');
if (themeSwitcher) {
    themeSwitcher.addEventListener('click', () => {
        const body = document.querySelector('body');
        body.dataset.bsTheme = body.dataset.bsTheme === 'light' ? 'dark' : 'light';
        const icon = themeSwitcher.querySelector('i');
        if (body.dataset.bsTheme === 'dark') {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        } else {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    });
}

function addToast(message,autohideDelay = 2500,toastColor = 'text-bg-dark',toastPosition = 'position-fixed bottom-0 start-50 translate-middle-x') {
    const toastContainer = document.querySelector('.toast-container');
    if (toastContainer) {
        toastContainer.classList = `toast-container ${toastPosition} p-3`;
        const toastHTML = `
        <div class="toast align-items-center ${toastColor} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        `;
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        const newToast = new bootstrap.Toast(toastContainer.lastElementChild, {
            autohide: true,
            delay: autohideDelay
        });
        newToast.show();
    }
}