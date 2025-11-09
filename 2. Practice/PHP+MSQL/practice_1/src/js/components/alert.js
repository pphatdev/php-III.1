const icons = {
    info: `<svg viewBox="0 0 24 24" fill="currentColor" class="size-7 text-destructive"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -15 8.66l.005 -.324a10 10 0 0 1 14.995 -8.336m-5 11.66a1 1 0 0 0 -1 1v.01a1 1 0 0 0 2 0v-.01a1 1 0 0 0 -1 -1m0 -7a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-4a1 1 0 0 0 -1 -1" /></svg>`,
    success: `<svg viewBox="0 0 24 24" fill="currentColor" class="size-7 text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>`,
    error: `<svg viewBox="0 0 24 24" fill="currentColor" class="size-7 -rotate-45 text-destructive"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4.929 4.929a10 10 0 1 1 14.141 14.141a10 10 0 0 1 -14.14 -14.14zm8.071 4.071a1 1 0 1 0 -2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0 -2h-2v-2z" /></svg>`,
}

export const showAlert = (message, type = 'info') => {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} shadow-xl rounded-lg text-sm min-w-[280px] max-w-[300px]`;

    alert.innerHTML = `
        <div class="flex w-full justify-start items-center gap-2">
            ${icons[type] ?? icons.info}
            <span class="leading-4">${message}</span>
        </div>
    `;
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        padding-left: 0.75rem;
        border-radius: 6px;
        font-weight: 500;
        z-index: 2000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        background-color: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    `;
    document.body.appendChild(alert);
    // Animate in
    const progressBar = document.createElement('div');
    progressBar.style.position = 'absolute';
    progressBar.style.left = '0';
    progressBar.style.bottom = '0';
    progressBar.style.height = '2px';
    progressBar.style.width = '100%';
    progressBar.style.background = type === 'error' ? '#ff6f69' : '#22c55e';
    progressBar.style.transition = `width 0ms linear`;
    alert.appendChild(progressBar);
    setTimeout(() => {
        alert.style.transform = 'translateX(0)';
    }, 10);
    // Set processing timeout (e.g., 5 seconds)
    const PROCESSING_TIMEOUT = 5000;
    let startTime = Date.now();
    let elapsed = 0;
    let removeTimeout;
    let hideTimeout;
    const updateProgressBar = () => {
        const now = Date.now();
        elapsed = now - startTime;
        const remaining = Math.max(PROCESSING_TIMEOUT - elapsed, 0);
        const percent = ((remaining / PROCESSING_TIMEOUT) * 100);
        progressBar.style.width = `${percent}%`;
        if (remaining > 0) {
            progressBar._raf = requestAnimationFrame(updateProgressBar);
        }
    };
    const removeAlert = () => {
        alert.style.transform = 'translateX(100%)';
        hideTimeout = setTimeout(() => {
            if (alert.parentNode) {
                document.body.removeChild(alert);
            }
        }, 300);
        if (progressBar._raf) cancelAnimationFrame(progressBar._raf);
    };
    const startRemoveTimer = () => {
        startTime = Date.now() - elapsed;
        removeTimeout = setTimeout(removeAlert, PROCESSING_TIMEOUT - elapsed);
        updateProgressBar();
    };
    const clearRemoveTimer = () => {
        clearTimeout(removeTimeout);
        clearTimeout(hideTimeout);
        if (progressBar._raf) cancelAnimationFrame(progressBar._raf);
    };
    alert.addEventListener('mouseenter', () => {
        clearRemoveTimer();
    });
    alert.addEventListener('mouseleave', () => {
        startRemoveTimer();
    });
    startRemoveTimer();
};