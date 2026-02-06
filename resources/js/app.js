import './bootstrap';

// =========================================================
// 1. IMPORTATION DU JS DE BOOTSTRAP (INDISPENSABLE !)
// =========================================================
// C'est cette ligne qui fait fonctionner le menu Burger sur mobile
import 'bootstrap';

// =========================================================
// 2. TON CODE JS PERSONNALISÉ (Filtres, Boutons, etc.)
// =========================================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Menu Dragon d\'Or et Bootstrap chargés');

    // --- GESTION DES FILTRES (Catégories) ---
    const filterButtons = document.querySelectorAll('.filter-link');
    const categorySections = document.querySelectorAll('.category-section');

    if(filterButtons.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                // Gestion du style "Actif"
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Récupération de la catégorie
                const categoryToShow = this.getAttribute('data-category');

                // Afficher / Masquer
                if (categoryToShow === 'all') {
                    categorySections.forEach(section => {
                        section.style.display = 'block';
                        section.classList.add('fade-in');
                    });
                } else {
                    categorySections.forEach(section => {
                        section.style.display = 'none';
                        section.classList.remove('fade-in');
                    });

                    const targetId = 'category-' + categoryToShow;
                    const targetSection = document.getElementById(targetId);

                    if (targetSection) {
                        targetSection.style.display = 'block';
                        targetSection.classList.add('fade-in');
                    }
                }
            });
        });
    }

    // --- CRÉATION AUTOMATIQUE DES BOUTONS +/- ---
    const quantityInputs = document.querySelectorAll('.quantity-input');

    quantityInputs.forEach(input => {
        // Wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'quantity-wrapper';
        wrapper.style.cssText = 'display: flex; align-items: center; justify-content: center; gap: 5px; margin-bottom: 10px;';

        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        // Bouton MOINS
        const minusBtn = document.createElement('button');
        minusBtn.type = 'button';
        minusBtn.textContent = '-';
        minusBtn.className = 'btn btn-sm btn-outline-secondary'; // Classes Bootstrap
        minusBtn.style.fontWeight = 'bold';

        // Bouton PLUS
        const plusBtn = document.createElement('button');
        plusBtn.type = 'button';
        plusBtn.textContent = '+';
        plusBtn.className = 'btn btn-sm btn-outline-secondary'; // Classes Bootstrap
        plusBtn.style.fontWeight = 'bold';

        wrapper.insertBefore(minusBtn, input);
        wrapper.appendChild(plusBtn);

        // Logique
        minusBtn.addEventListener('click', () => {
            let val = parseInt(input.value);
            if (val > 1) input.value = val - 1;
        });

        plusBtn.addEventListener('click', () => {
            let val = parseInt(input.value);
            if (val < 10) input.value = val + 1;
        });

        // Style Input
        input.style.textAlign = 'center';
        input.style.border = '1px solid #ced4da';
        input.style.borderRadius = '4px';
        input.style.width = '50px';
    });

    // --- ANIMATION DU BOUTON AJOUTER ---
    const addButtons = document.querySelectorAll('.add-button-link');

    addButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const originalText = this.innerText;
            this.innerText = '⏳ Ajout...';
            this.style.opacity = '0.7';
        });
    });

});
