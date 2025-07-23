document.addEventListener("DOMContentLoaded", () => {
  const logo = document.querySelector(".logo");
  const borderColors = ["#8aff33", "#33ffe3", "#3333ff", "#ff33d1"];
  let currentIndex = 0;

  function blinkLogoBorder() {
    if (!logo) return;
    logo.style.border = `3px solid ${borderColors[currentIndex]}`;
    currentIndex = (currentIndex + 1) % borderColors.length;
  }

  setInterval(blinkLogoBorder, 700);
});
