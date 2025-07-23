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
  
    const skillTitle = document.getElementById("skill-title");
    const category = document.getElementById("category");
  
    const categoryOptions = {
      "web-development": ["HTML", "CSS", "JavaScript"],
      "programming": ["Python", "Java", "C#"],
      "microsoft-office": ["Excel", "Word", "PowerPoint"],
      "graphic-design": ["Figma", "Canva", "Photoshop"],
    };
  
    skillTitle.addEventListener("change", () => {
      const selectedSkill = skillTitle.value;
  
      // Clear existing options
      category.innerHTML = `<option value="" disabled selected hidden></option>`;
  
      // Add new category options
      if (categoryOptions[selectedSkill]) {
        categoryOptions[selectedSkill].forEach((cat) => {
          const option = document.createElement("option");
          option.value = cat.toLowerCase().replace(/\s+/g, "-");
          option.textContent = cat;
          category.appendChild(option);
        });
      }
    });
  
    // Form submission handling
    const form = document.getElementById("skill-form");
  
    form.addEventListener("submit", async function (e) {
      e.preventDefault(); // Stop the default form submit
  
      const formData = new FormData(form);
  
      try {
        const response = await fetch("php/gainSkill.php", {
          method: "POST",
          body: formData,
        });
  
        const result = await response.json();
  
        if (result.success) {
          alert("Post skill successfully!");
          form.reset(); // Clear the form
          setTimeout(() => {
            window.location.reload(); // Reload the page
          }, 100);
        } else {
          alert("Error posting skill: " + result.error);
        }
      } catch (err) {
        console.error("Submission failed:", err);
        alert("An unexpected error occurred. Please try again.");
      }
    });
  });
  