// public/js/menu-filters.js - Version autonome
(function() {
    'use strict';

    function initMenuFilters() {
        console.log('🎯 MenuFilters starting...');

        const buttons = document.querySelectorAll('.filter-link');
        const sections = document.querySelectorAll('.category-section');

        console.log(`Buttons: ${buttons.length}, Sections: ${sections.length}`);

        if (!buttons.length || !sections.length) {
            console.error('❌ No elements found');
            return;
        }

        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                // Active state
                buttons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.style.backgroundColor = '';
                });
                this.classList.add('active');
                this.style.backgroundColor = '#FF2D20';

                // Filter
                const cat = this.getAttribute('data-category');

                sections.forEach(section => {
                    section.style.display =
                        cat === 'all' ||
                        section.id === 'category-' + cat
                        ? 'block' : 'none';
                });
            });
        });

        console.log('✅ MenuFilters initialized');
    }

    // Auto-init
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMenuFilters);
    } else {
        initMenuFilters();
    }
})();
