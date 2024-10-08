// resources/js/landing-page.js

const toggleDarkMode = () => {
    document.body.classList.toggle("dark-mode");
};

document.addEventListener("DOMContentLoaded", function () {
    const darkModeToggle = document.createElement("button");
    darkModeToggle.textContent = "Toggle Dark Mode";
    darkModeToggle.classList.add("btn", "btn-outline-light");
    darkModeToggle.style.position = "fixed";
    darkModeToggle.style.bottom = "10px";
    darkModeToggle.style.right = "10px";
    document.body.appendChild(darkModeToggle);
    darkModeToggle.addEventListener("click", toggleDarkMode);
});
