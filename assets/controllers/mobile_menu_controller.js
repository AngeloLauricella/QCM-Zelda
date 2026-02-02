import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['toggle', 'menu', 'overlay'];
    static values = { breakpoint: Number };

    connect() {
        // Valeur par défaut du breakpoint mobile (en pixels)
        this.breakpointValue = this.breakpointValue || 576;
        
        // Ajouter les event listeners
        this._boundHandleClick = this.handleClick.bind(this);
        this._boundHandleResize = this.handleResize.bind(this);
        this._boundHandleEscape = this.handleEscape.bind(this);
        this._boundClose = this.close.bind(this);

        this.element.addEventListener('click', this._boundHandleClick);

        // Écouter les changements de taille de fenêtre
        window.addEventListener('resize', this._boundHandleResize);

        // Écouter la touche Échap
        document.addEventListener('keydown', this._boundHandleEscape);

        // Fermer le menu si on clique en dehors (sur l'overlay)
        if (this.hasOverlayTarget) {
            this.overlayTarget.addEventListener('click', this._boundClose);
        }
    }

    disconnect() {
        // Nettoyer les event listeners
        if (this._boundHandleClick) {
            this.element.removeEventListener('click', this._boundHandleClick);
            this._boundHandleClick = null;
        }

        if (this._boundHandleResize) {
            window.removeEventListener('resize', this._boundHandleResize);
            this._boundHandleResize = null;
        }

        if (this._boundHandleEscape) {
            document.removeEventListener('keydown', this._boundHandleEscape);
            this._boundHandleEscape = null;
        }

        if (this._boundClose && this.hasOverlayTarget) {
            this.overlayTarget.removeEventListener('click', this._boundClose);
            this._boundClose = null;
        }
    }

    handleClick(event) {
        // Vérifier si c'est le bouton toggle
        if (event.target.closest('.header-toggle')) {
            event.preventDefault();
            this.toggle();
        }
        
        // Fermer le menu si on clique sur un lien
        if (event.target.closest('.mobile-nav a')) {
            this.close();
        }
    }

    handleResize() {
        // Fermer le menu si on passe au-dessus du breakpoint
        if (window.innerWidth >= this.breakpointValue) {
            this.close();
        }
    }

    handleEscape(event) {
        // Fermer le menu si on appuie sur Échap
        if (event.key === 'Escape' && this.menuTarget.classList.contains('active')) {
            event.preventDefault();
            this.close();
        }
    }

    toggle() {
        // Basculer l'état du menu
        if (this.menuTarget.classList.contains('active')) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        // Ouvrir le menu
        this.menuTarget.classList.add('active');
        this.overlayTarget.classList.add('active');
        this.toggleTarget.setAttribute('aria-expanded', 'true');
        this.menuTarget.setAttribute('aria-hidden', 'false');
        
        // Empêcher le scroll du body
        document.body.classList.add('mobile-menu-open');
        
        // Mettre le focus sur le premier lien du menu
        const firstLink = this.menuTarget.querySelector('a');
        if (firstLink) {
            firstLink.focus();
        }
    }

    close() {
        // Fermer le menu
        this.menuTarget.classList.remove('active');
        this.overlayTarget.classList.remove('active');
        this.toggleTarget.setAttribute('aria-expanded', 'false');
        this.menuTarget.setAttribute('aria-hidden', 'true');
        
        // Réautoriser le scroll du body
        document.body.classList.remove('mobile-menu-open');
        
        // Remettre le focus sur le bouton toggle
        this.toggleTarget.focus();
    }
}
