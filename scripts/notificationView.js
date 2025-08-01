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

    // Accept button action
    const acceptBtn = document.querySelector('.accept-btn');
    if (acceptBtn) {
        acceptBtn.addEventListener('click', () => {
            const requestId = acceptBtn.getAttribute('data-request-id') || 1; // Replace 1 with dynamic ID later

            fetch('/php/acceptRequest.phpv', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `request_id=${requestId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `chat.php?chat_id=${data.chat_id}`;
                } else {
                    alert('Failed to accept the request. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong while processing the request.');
            });
        });
    }

    // Decline button action
    const declineBtn = document.querySelector('.decline-btn');
    if (declineBtn) {
        declineBtn.addEventListener('click', () => {
            alert('You have declined the exchange request.');
            // You can optionally send an AJAX call to update the request status to "declined"
        });
    }

    // Back button functionality
    const backBtn = document.querySelector('.back-button');
    if (backBtn) {
        backBtn.addEventListener('click', () => {
            window.history.back();
        });
    }
});
