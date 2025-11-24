document.addEventListener("DOMContentLoaded", () => {
  const toggles = document.querySelectorAll(".login-password-toggle");

  toggles.forEach(toggle => {
    const input = toggle.closest(".login-password-field").querySelector("input");
    const icon = toggle.querySelector("i");

    toggle.addEventListener("click", e => {
      e.preventDefault();
      const show = input.type === "password";
      input.type = show ? "text" : "password";
      icon.classList.toggle("bi-eye-slash", show);
      icon.classList.toggle("bi-eye", !show);
    });
  });
});
