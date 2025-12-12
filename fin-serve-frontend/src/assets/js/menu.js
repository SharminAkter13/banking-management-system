export default function setupMobileMenu() {
    const navToggle = document.getElementById("nav-toggle");
    const navContent = document.getElementById("nav-content");

    if (navToggle) {
        navToggle.addEventListener("click", () => {
            navContent.classList.toggle("hidden");
        });
    }
}
