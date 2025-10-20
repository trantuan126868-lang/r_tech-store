document.addEventListener("DOMContentLoaded", () => {
  const dropdowns = document.querySelectorAll(".dropdown-toggle");

  dropdowns.forEach(toggle => {
    toggle.addEventListener("click", e => {
      e.preventDefault();
      const parent = toggle.closest(".dropdown");
      parent.classList.toggle("active");

      document.querySelectorAll(".dropdown").forEach(d => {
        if (d !== parent) d.classList.remove("active");
      });
    });
  });

  document.addEventListener("click", e => {
    if (!e.target.closest(".dropdown")) {
      document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("active"));
    }
  });
});
