const AUTO_SUBMIT_DELAY_MS = 600;

document.querySelectorAll('details[data-menu]').forEach((menu) => {
    document.addEventListener('click', (event) => {
        if (menu.open && !menu.contains(event.target)) {
            menu.open = false;
        }
    });
});

document.querySelectorAll('[data-auto-submit]').forEach((input) => {
    let timer;

    input.addEventListener('input', () => {
        clearTimeout(timer);

        timer = setTimeout(() => {
            if (input.reportValidity()) {
                input.form.requestSubmit();
            }
        }, AUTO_SUBMIT_DELAY_MS);
    });
});
