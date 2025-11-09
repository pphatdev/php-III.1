import { deepMerge } from "../../libs/deep-merge.js";

export class Modal {
    constructor(options = {}) {
        const defaultOptions = {
            title: 'Create new User',
            formId: 'user-form',
            userListId: 'user-list',
            buttonSelector: '[data-action="create-user"]',
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
        if (!window._modalBound) {
            document.body.addEventListener('click', (event) => {
                const button = event.target.closest(this.options.buttonSelector);

                // get all data-set attributes from button
                const params = button ? { ...button.dataset } : {};

                if (button) {
                    this.open(params);
                }
            });
            window._modalBound = true;
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
        this.card.className = 'dialog-content w-full max-w-lg max-h-[90vh] bg-white rounded-xl overflow-hidden flex flex-col';
        this._styleModalCard(this.card, 1001);

        this.card.innerHTML = (`
            <div class="py-2.5 px-4 flex items-center gap-5 z-10 w-full sticky bg-white/90 backdrop-blur-sm border-b top-0 justify-between">
                <div class="flex flex-col">
                    <h2 class="font-semibold text-lg">${typeof this.options.title === 'function' ? this.options.title(params) : this.options.title}</h2>
                </div>
                <button type="reset" class="text-gray-400 hover:text-gray-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            <main class="w-full flex flex-col overflow-y-auto p-5">
                ${this.options.content(params) || ''}
                <div class="inline-flex justify-end mt-6 gap-2 pt-2">
                    <button type="reset" class="button py-1.5 bg-destructive text-destructive-foreground cancel-btn">Cancel</button>
                    <button type="submit" class="button py-1.5 gap-1 bg-primary text-primary-foreground submit-btn">Save</button>
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