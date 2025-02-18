document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const expandBtn = document.querySelector(".expand-btn");
    const themeToggle = document.querySelector('#themeToggle'); // Button to toggle theme
    const sidebarLinksWrapper = document.querySelector('.sidebar-links-wrapper');
    const links = document.querySelectorAll('a'); // Svi linkovi na stranici

    // Retrieve menu states from localStorage
    const savedState = JSON.parse(localStorage.getItem('menuState')) || {};

    // Spinner element
    const spinner = document.createElement('div');
    spinner.id = 'loadingSpinner';
    spinner.className = 'loading-spinner';
    spinner.innerHTML = '<div class="spinner"></div>';
    document.body.appendChild(spinner);

    // Function to show spinner
    function showSpinner() {
        spinner.style.display = 'flex';
    }

    // Function to hide spinner with a delay
    function hideSpinner() {
        setTimeout(() => {
            spinner.style.display = 'none';
        }, 500); // 0.5-second delay
    }

    // Show spinner on page load
    showSpinner();
    window.addEventListener('load', hideSpinner);

    // Show spinner on link navigation
    links.forEach(link => {
        link.addEventListener('click', function (event) {
            const href = this.getAttribute('href');
            if (href && !href.startsWith('#') && !href.startsWith('javascript')) {
                showSpinner();
            }
        });
    });

    // Function to toggle expand button icon
    function updateExpandIcon() {
        if (!expandBtn) return; // Provera postojanja dugmeta
        const expandIcon = expandBtn.querySelector(".material-icons-outlined");
        if (expandIcon) {
            if (body.classList.contains("collapsed")) {
                expandIcon.textContent = "chevron_right"; // Collapsed icon
            } else {
                expandIcon.textContent = "expand_more"; // Expanded icon
            }
        }
    }

    // Expand/collapse sidebar
    if (expandBtn) {
        expandBtn.addEventListener("click", () => {
            body.classList.toggle("collapsed");
            updateExpandIcon();
        });
    }

    // Initialize expand icon
    updateExpandIcon();

    // Handle main items and submenu states
    const mainItems = document.querySelectorAll('.sidebar-links .main-item');
    const subLinks = document.querySelectorAll('.sidebar-links ul ul li a');

    mainItems.forEach((mainItem) => {
        const parentLi = mainItem.parentElement;
        const submenu = parentLi.querySelector('ul');
        const menuName = parentLi.dataset.name;

        // Restore main item state
        if (menuName && savedState[menuName]?.open) {
            parentLi.classList.add('main-item--open');
        }

        // Toggle submenu and save state
        if (submenu) {
            mainItem.addEventListener('click', function (event) {
                if (body.classList.contains('collapsed')) {
                    body.classList.remove('collapsed');
                    updateExpandIcon();
                } else {
                    event.preventDefault();
                    const isOpen = parentLi.classList.toggle('main-item--open');
                    savedState[menuName] = { open: isOpen };
                    localStorage.setItem('menuState', JSON.stringify(savedState));
                }
            });
        }

        // Highlight active link
        const linkPath = mainItem.getAttribute('href');
        if (linkPath === window.location.pathname) {
            mainItem.classList.add('active');
        }
    });

    // Restore submenu states and highlight active links
    subLinks.forEach((subLink) => {
        const subLinkPath = subLink.getAttribute('href');
        const subMenuName = subLink.closest('li').dataset.name;

        if (subLinkPath === window.location.pathname) {
            subLink.classList.add('active');
            const parentLi = subLink.closest('.has-submenu');
            if (parentLi) {
                parentLi.classList.add('main-item--open');
                const mainMenuName = parentLi.dataset.name;
                if (mainMenuName) {
                    savedState[mainMenuName] = { open: true };
                    localStorage.setItem('menuState', JSON.stringify(savedState));
                }
            }
        }

        subLink.addEventListener('click', function () {
            const mainMenuName = subLink.closest('.has-submenu')?.dataset.name;
            if (mainMenuName) {
                savedState[mainMenuName] = { open: true };
                localStorage.setItem('menuState', JSON.stringify(savedState));
            }
        });
    });

    // Theme Toggle Functionality
    if (themeToggle) {
        const currentTheme = localStorage.getItem('theme') || 'current';

        // Apply the saved theme on load
        document.documentElement.classList.remove('light-theme', 'dark-theme');
        if (currentTheme !== 'current') {
            document.documentElement.classList.add(`${currentTheme}-theme`);
        }

        // Update theme on toggle
        themeToggle.addEventListener('click', () => {
            const newTheme = document.documentElement.classList.contains('light-theme')
                ? 'dark'
                : 'light';
            document.documentElement.classList.remove('light-theme', 'dark-theme');
            if (newTheme !== 'current') {
                document.documentElement.classList.add(`${newTheme}-theme`);
            }
            localStorage.setItem('theme', newTheme);
        });
    }

    // Scroll Position Handling
    if (sidebarLinksWrapper) {
        // Function to save scroll position
        function saveScrollPosition() {
            const scrollData = {
                windowScroll: window.scrollY,
                sidebarScroll: sidebarLinksWrapper.scrollTop
            };
            // Key based on current path
            const key = `scrollPos-${window.location.pathname}`;
            localStorage.setItem(key, JSON.stringify(scrollData));
        }

        // Function to restore scroll position
        function restoreScrollPosition() {
            const key = `scrollPos-${window.location.pathname}`;
            const scrollData = JSON.parse(localStorage.getItem(key));
            if (scrollData) {
                window.scrollTo(0, scrollData.windowScroll);
                sidebarLinksWrapper.scrollTop = scrollData.sidebarScroll;
                // Remove the saved data after restoring
                localStorage.removeItem(key);
            }
        }

        // Save scroll position before navigating away
        window.addEventListener('beforeunload', saveScrollPosition);

        // Save scroll position when clicking on links
        links.forEach(link => {
            link.addEventListener('click', saveScrollPosition);
        });

        // Restore scroll position after page load
        restoreScrollPosition();
    }
});
