document.addEventListener('DOMContentLoaded', function() {
    const filterLinks = document.querySelectorAll('.filter-link');
    const products = document.querySelectorAll('.product-item');

    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            filterLinks.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const selectedCategory = this.getAttribute('data-filter');

            products.forEach(product => {
                const productCategory = product.getAttribute('data-category');
                if (selectedCategory === 'all' || productCategory == selectedCategory) {
                    product.classList.remove('is-hidden');
                } else {
                    product.classList.add('is-hidden');
                }
            });
        });
    });
});

function applyTheme() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        
        
        const toggle = document.getElementById('darkModeToggle');
        if (toggle) {
            toggle.checked = true;
        }
    }
}


function initToggle() {
    const toggle = document.getElementById('darkModeToggle');
    if (toggle) {
        toggle.addEventListener('change', () => {
            if (toggle.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    }
}

applyTheme();


window.addEventListener('DOMContentLoaded', initToggle);

let clickCounter = 0;
const bonusTrigger = document.getElementById('bonus');

if (bonusTrigger) {
    bonusTrigger.onclick = function(event) {
        clickCounter++;

        if (clickCounter === 5) {
            event.preventDefault(); 
            document.body.style.transform = "rotate(180deg)";
            document.body.style.transition = "transform 1s";
            alert("Doucement!!!");
            setTimeout(() => {
                document.body.style.transform = "rotate(0deg)";
            }, 3000);
            
            clickCounter = 0;
        }
    };
}