import { deepMerge } from "../../libs/deep-merge.js";

export class Dialog {
    constructor(options = {}) {
        const defaultOptions = {
            title: 'Create new User',
            formId: 'user-form',
            userListId: 'user-list',
            buttonSelector: '[data-action]',
            content: () => { },
            onSubmit: () => { }
        };

        this.options = deepMerge(defaultOptions, options);
        this.overlay = null;
        this.card = null;
        this.init();
    }


    async init() {
        // Only bind once
        if (!window._dialogBound) {
            document.body.addEventListener('click', (event) => {
                const button = event.target.closest(this.options.buttonSelector);

                // get all data-set attributes from button
                const params = button ? { ...button.dataset } : {};

                if (button) {
                    this.open(params);
                }
            });
            window._dialogBound = true;
        }
    }

    async open(params = {}) {
        // Remove previous overlay/card if any
        if (this.overlay && this.overlay.parentNode) this.overlay.parentNode.removeChild(this.overlay);
        if (this.card && this.card.parentNode) this.card.parentNode.removeChild(this.card);

        this.overlay = null;
        this.card = null;

        // Create overlay
        this.overlay = this._createOverlay(1000);

        // Create popup card
        this.card = document.createElement('form');
        this.card.setAttribute('id', this.options.formId);
        this.card.className = 'dialog-content w-full max-w-sm max-h-[90vh] bg-white rounded-xl overflow-hidden flex flex-col';
        this._styleModalCard(this.card, 1001);

        this.card.innerHTML = (`
            <main class="w-full flex justify-center flex-col overflow-y-auto p-5">
                <svg viewBox="0 0 24 24" fill="currentColor" class="size-16 mx-auto text-destructive/70">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M17 3.34a10 10 0 1 1 -15 8.66l.005 -.324a10 10 0 0 1 14.995 -8.336m-5 11.66a1 1 0 0 0 -1 1v.01a1 1 0 0 0 2 0v-.01a1 1 0 0 0 -1 -1m0 -7a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-4a1 1 0 0 0 -1 -1" />
                </svg>
                <div class="py-2.5 px-4 flex text-center items-center gap-5 z-10 w-full sticky bg-white/90 backdrop-blur-sm top-0 justify-between">
                    ${this.options.content(params) || ''}
                </div>
                <div class="inline-flex justify-center mt-6 gap-2 pt-2">
                    <button type="reset" class="button py-1.5 bg-destructive text-destructive-foreground cancel-btn">No, cancel</button>
                    <button type="submit" class="button py-1.5 gap-1 bg-primary text-primary-foreground submit-btn">Yes, continue</button>
                </div>
            </main>

        `);

        document.body.appendChild(this.overlay);
        document.body.appendChild(this.card);


        // Trigger transition
        requestAnimationFrame(() => {
            this.overlay.style.opacity = '1';
            this.card.style.opacity = '1';
            this.card.style.transform = 'translate(-50%, -50%) scale(1)';
        });

        // Shake card on overlay click
        this.overlay.addEventListener('click', () => {
            this.card.classList.add('shake');
            setTimeout(() => {
                this.card.classList.remove('shake');
            }, 400);
        });

        this.close();
        this.submit();
    }

    _createOverlay(zIndex) {
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = 0;
        overlay.style.left = 0;
        overlay.style.width = '100vw';
        overlay.style.height = '100vh';
        overlay.style.background = 'rgba(0,0,0,0.5)';
        overlay.style.zIndex = zIndex;
        overlay.style.opacity = '0';
        overlay.style.transition = 'opacity 0.3s';
        return overlay;
    }

    _styleModalCard(card, zIndex) {
        card.style.position = 'fixed';
        card.style.top = '50%';
        card.style.left = '50%';
        card.style.transform = 'translate(-50%, -50%) scale(0.8)';
        card.style.boxShadow = '0 10px 25px rgba(0,0,0,0.4)';
        card.style.zIndex = zIndex;
        card.style.opacity = '0';
        card.style.transition = 'opacity 0.3s, transform 0.3s';
    }

    submit() {
        const form = document.getElementById(this.options.formId);
        if (!form) return;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            await this.options.onSubmit({event, data});
        });
    }

    close() {
        const form = document.getElementById(this.options.formId);
        if (!form) return;

        // Remove previous event listener if any
        if (form._resetHandler) {
            form.removeEventListener('reset', form._resetHandler);
        }

        form._resetHandler = async (event) => {
            event.preventDefault();
            if (this.overlay && this.card) {
                this.overlay.style.opacity = '0';
                this.card.style.opacity = '0';
                this.card.style.transform = 'translate(-50%, -50%) scale(0.8)';
                setTimeout(() => {
                    if (this.overlay && this.overlay.parentNode) this.overlay.parentNode.removeChild(this.overlay);
                    if (this.card && this.card.parentNode) this.card.parentNode.removeChild(this.card);
                    this.overlay = null;
                    this.card = null;
                }, 300);
            }
        };
        form.addEventListener('reset', form._resetHandler);
    }
}