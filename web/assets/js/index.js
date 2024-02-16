mobile_nav_btn();

function mobile_nav_btn() {
    document.querySelector(".hamburger").addEventListener("click", e => {
        document.querySelector(".hamburger").classList.toggle("active");
        document.querySelector(".mobile_nav").classList.toggle("active");
    });
}