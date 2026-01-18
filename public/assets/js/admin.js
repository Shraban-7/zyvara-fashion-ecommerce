/**
 * Admin Panel JavaScript
 * EnterpriseERP
 */

document.addEventListener("DOMContentLoaded", function () {
    // Elements
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main");
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const userDropdown = document.getElementById("userDropdown");

    // Sidebar Toggle
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function () {
            if (window.innerWidth <= 991.98) {
                // Mobile: Show/hide sidebar
                sidebar.classList.toggle("show");
                sidebarOverlay.classList.toggle("show");
            } else {
                // Desktop: Collapse sidebar
                sidebar.classList.toggle("collapsed");
                main.classList.toggle("expanded");
            }
        });
    }

    // Close sidebar on overlay click (mobile)
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", function () {
            sidebar.classList.remove("show");
            sidebarOverlay.classList.remove("show");
        });
    }

    // Submenu Toggle
    const submenuToggles = document.querySelectorAll('[data-toggle="submenu"]');
    submenuToggles.forEach(function (toggle) {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();
            const parentItem = this.closest(".nav-item");

            // Close other open submenus
            const openItems = document.querySelectorAll(".nav-item.open");
            openItems.forEach(function (item) {
                if (item !== parentItem) {
                    item.classList.remove("open");
                }
            });

            // Toggle current submenu
            parentItem.classList.toggle("open");
        });
    });

    // User Dropdown Toggle
    if (userDropdown) {
        const dropdownToggle = userDropdown.querySelector(
            ".user-dropdown-toggle"
        );

        if (dropdownToggle) {
            dropdownToggle.addEventListener("click", function (e) {
                e.stopPropagation();
                userDropdown.classList.toggle("show");
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener("click", function (e) {
            if (!userDropdown.contains(e.target)) {
                userDropdown.classList.remove("show");
            }
        });
    }

    // Handle window resize
    let resizeTimer;
    window.addEventListener("resize", function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (window.innerWidth > 991.98) {
                sidebar.classList.remove("show");
                sidebarOverlay.classList.remove("show");
            }
        }, 250);
    });

    // Active link highlighting based on current URL
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll(".sidebar-nav .nav-link");

    navLinks.forEach(function (link) {
        const href = link.getAttribute("href");
        if (href && href !== "#" && currentPath.includes(href)) {
            link.classList.add("active");

            // Open parent submenu if exists
            const parentSubmenu = link.closest(".nav-submenu");
            if (parentSubmenu) {
                const parentItem = parentSubmenu.closest(".nav-item");
                if (parentItem) {
                    parentItem.classList.add("open");
                }
            }
        }
    });

    // Initialize tooltips (if Bootstrap tooltips are needed)
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers (if Bootstrap popovers are needed)
    const popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    console.log("Admin panel initialized");
});
