document.addEventListener('DOMContentLoaded', () => {
    // Logo border blinking effect
    const logo = document.querySelector(".logo");
    const borderColors = ["#8aff33", "#33ffe3", "#3333ff", "#ff33d1"];
    let currentIndex = 0;
  
    function blinkLogoBorder() {
      if (!logo) return;
      logo.style.border = `3px solid ${borderColors[currentIndex]}`;
      currentIndex = (currentIndex + 1) % borderColors.length;
    }
  
    setInterval(blinkLogoBorder, 700);
  
    // Time slot booking functionality
    const dayButtons = document.querySelectorAll('.day-btn');
    const slotsGrid = document.querySelector('.slots-grid');
    const selectedDayInput = document.getElementById('selected_day');
    const selectedTimeInput = document.getElementById('selected_time');
  
    dayButtons.forEach(button => {
      button.addEventListener('click', () => {
        // Clear previous active states and set new one
        dayButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
  
        const day = button.dataset.day;
        selectedDayInput.value = day;
  
        // Fetch slots for selected day
        fetch('php/loadSlots.php', {  // adjust path if needed
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `day=${encodeURIComponent(day)}`,
        })
          .then(response => response.text())
          .then(data => {
            slotsGrid.innerHTML = data;
  
            // Add click events to newly inserted slot buttons
            const slotButtons = document.querySelectorAll('.slot-btn');
            slotButtons.forEach(slot => {
              slot.addEventListener('click', () => {
                slotButtons.forEach(btn => btn.classList.remove('selected'));
                slot.classList.add('selected');
                selectedTimeInput.value = slot.dataset.time;
              });
            });
          })
          .catch(error => {
            slotsGrid.innerHTML = "<p>Error loading time slots</p>";
            console.error('Fetch error:', error);
          });
      });
    });
  });
  