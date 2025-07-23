document.addEventListener('DOMContentLoaded', () => {
    // Logo border animation
    const logo = document.querySelector('.logo');
    const borderColors = ['#8aff33', '#33ffe3', '#3333ff', '#ff33d1'];
    let currentIndex = 0;

    function blinkLogoBorder() {
        if (!logo) return;
        logo.style.border = `3px solid ${borderColors[currentIndex]}`;
        currentIndex = (currentIndex + 1) % borderColors.length;
    }

    setInterval(blinkLogoBorder, 700);

    // Label animation for input and textarea
    document.querySelectorAll('.input-group input, .input-group textarea').forEach(input => {
        input.addEventListener('focus', () => {
            input.nextElementSibling.classList.add('focused');
        });

        input.addEventListener('blur', () => {
            if (input.value === '') {
                input.nextElementSibling.classList.remove('focused');
            }
        });
    });

    // Form validation
    const form = document.getElementById('exchange-form');

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // prevent actual submission

        const skillOffer = document.getElementById('skill-offer');
        const skillSeek = document.getElementById('skill-seek');
        const message = document.getElementById('message');

        let isValid = true;

        // Reset any previous error borders
        [skillOffer, skillSeek, message].forEach(input => {
            input.style.border = '';
        });

        // Check each field
        if (skillOffer.value.trim() === '') {
            skillOffer.style.border = '2px solid red';
            isValid = false;
        }
        if (skillSeek.value.trim() === '') {
            skillSeek.style.border = '2px solid red';
            isValid = false;
        }
        if (message.value.trim().length < 5) {
            message.style.border = '2px solid red';
            isValid = false;
            alert("Message must be at least 5 characters.");
        }

        if (isValid) {
            alert('Request sent successfully!');
            form.reset();
            document.querySelectorAll('.input-group label').forEach(label => {
                label.classList.remove('focused');
            });
        } else {
            alert('Please fill all required fields properly.');
        }
    });

    // Back button functionality (optional: go back in history)
    document.querySelector('.back-button').addEventListener('click', () => {
        window.history.back();
    });
});
