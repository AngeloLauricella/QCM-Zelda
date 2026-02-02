import { Controller } from '@hotwired/stimulus';

/**
 * Profile Menu Controller
 * Gère l'affichage/masquage du menu déroulant du profil utilisateur
 */
export default class extends Controller {
    static targets = ['trigger', 'dropdown'];

    connect() {
        // Bind handlers so we can remove them on disconnect
        this._boundOutsideClick = this.handleClickOutside.bind(this);
        document.addEventListener('click', this._boundOutsideClick);
    }

    disconnect() {
        if (this._boundOutsideClick) {
            document.removeEventListener('click', this._boundOutsideClick);
            this._boundOutsideClick = null;
        }
    }

    /**
     * Basculer l'affichage du menu
     */
    toggle(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        if (!this.hasDropdownTarget || !this.hasTriggerTarget) return;

        const isHidden = this.dropdownTarget.hasAttribute('hidden');

        if (isHidden) {
            this.show();
        } else {
            this.hide();
        }
    }

    /**
     * Afficher le menu
     */
    show() {
        if (!this.hasDropdownTarget || !this.hasTriggerTarget) return;
        this.dropdownTarget.removeAttribute('hidden');
        this.triggerTarget.setAttribute('aria-expanded', 'true');
    }

    /**
     * Masquer le menu
     */
    hide() {
        if (!this.hasDropdownTarget || !this.hasTriggerTarget) return;
        this.dropdownTarget.setAttribute('hidden', '');
        this.triggerTarget.setAttribute('aria-expanded', 'false');
    }

    /**
     * Fermer si click en dehors du menu
     */
    handleClickOutside(event) {
        // Si le click est en dehors du menu, le fermer
        if (!this.element.contains(event.target)) {
            this.hide();
        }
    }
}
