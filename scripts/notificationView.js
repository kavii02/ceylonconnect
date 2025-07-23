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

    // Accept/Decline button actions
    document.querySelector('.accept-btn').addEventListener('click', () => {
        alert('You have accepted the exchange request.');
    });
    document.querySelector('.decline-btn').addEventListener('click', () => {
        alert('You have declined the exchange request.');
    });

    // Back button functionality
    document.querySelector('.back-button').addEventListener('click', () => {
        window.history.back();
    });
});
