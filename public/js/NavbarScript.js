
    document.addEventListener("DOMContentLoaded", () => {
        const navLinks = document.querySelectorAll(".nav-links-container .nav-link");
        const navContainer = document.querySelector(".nav-links-container");

        // Create slider div
        const slider = document.createElement("div");
        slider.classList.add("nav-slider");
        navContainer.appendChild(slider);

        function moveSliderTo(link) {
            const linkRect = link.getBoundingClientRect();
            const containerRect = navContainer.getBoundingClientRect();
            slider.style.width = `${linkRect.width}px`;
            slider.style.transform = `translateX(${linkRect.left - containerRect.left}px)`;
        }

        // Move slider to active link on load
        const activeLink = document.querySelector(".nav-link.active");
        if (activeLink) {
            moveSliderTo(activeLink);
        }

        // Update slider on nav item click
        navLinks.forEach(link => {
            link.addEventListener("click", () => moveSliderTo(link));
        });

        // Handle window resize
        window.addEventListener("resize", () => {
            const newActive = document.querySelector(".nav-link.active");
            if (newActive) moveSliderTo(newActive);
        });
    });


