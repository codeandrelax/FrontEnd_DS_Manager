<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Light Sidebar with Toggle</title>
    <link rel="icon" href="/public/resources/images/favicon.svg" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background-color: #121212;
            color: #e0e0e0;
        }

        .layout-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        #sidebar {
            width: 250px;
            background: #1e1e1e;
            /* Tamna pozadina za sidebar */
            transition: all 0.3s ease;
            border-right: 1px solid #333333;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        #sidebar.collapsed {
            width: 70px;
        }

        /* Centered Toggle Button */
        #toggleSidebar {
            position: fixed;
            top: 50%;
            left: 250px;
            transform: translateY(-50%) translateX(-50%);
            z-index: 1001;
            transition: all 0.3s ease;
            width: 24px;
            height: 24px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1e1e1e;
            border: 1px solid #333333;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            color: #e0e0e0;
        }

        #toggleSidebar:hover {
            background-color: #2c2c2c;
        }

        #toggleSidebar.collapsed {
            left: 70px;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid #333333;
            min-height: 60px;
            background-color: #1e1e1e;
        }

        .sidebar-header img {
            width: 25px;
            height: 25px;
            border-radius: 50%;
        }

        /* Navigation Links Container */
        .nav-link-container {
            padding: 0.5rem;
            flex: 1;
            overflow-y: auto;
        }

        /* Nav Links */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #e0e0e0;
            text-decoration: none;
            border-radius: 0.5rem;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .nav-link:hover {
            background-color: #2c2c2c;
            color: #bb86fc;
            transform: translateX(5px);
        }

        .nav-link.active {
            background-color: #333333;
            color: #bb86fc;
            font-weight: 500;
        }

        /* Footer Styles */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #333333;
            background-color: #1e1e1e;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .user-info img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .user-details {
            flex-grow: 1;
            overflow: hidden;
        }

        .user-name {
            font-weight: 600;
            color: #e0e0e0;
            margin: 0;
        }

        .user-role {
            color: #a0a0a0;
            font-size: 0.875rem;
            margin: 0;
        }

        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #e0e0e0;
        }

        #sidebar.collapsed .user-details {
            display: none;
        }

        #sidebar.collapsed .logout-btn span {
            display: none;
        }

        #sidebar.collapsed .logout-btn {
            padding: 0.375rem;
        }

        /* Hide text when collapsed */
        #sidebar.collapsed .link-text {
            display: none;
        }

        /* Main Content */
        #content {
            margin-left: 250px;
            padding: 2rem;
            width: 100%;
            transition: all 0.3s ease;
            background-color: #181818;
            /* Tamna pozadina za sadržaj */
            color: #e0e0e0;
        }

        #content.expanded {
            margin-left: 70px;
        }

        .sub-nav {
            padding-left: 2rem;
            /* Uvlaka podkategorije */
        }

        #sidebar.collapsed .sub-nav {
            padding-left: 1rem;
            /* Reduced padding when collapsed */
        }
    </style>
</head>

<body>
    <?php
    $currentPath = rtrim($_SERVER['REQUEST_URI'], '/');
    ?>

    <div class="layout-container">
        <!-- Sidebar -->
        <nav id="sidebar">
            <!-- Header / Logo -->
            <div class="sidebar-header">
                <img src="/public/resources/images/logo.png" alt="Logo">
                <span class="fs-5 fw-bold link-text">Development</span>
            </div>

            <!-- Main Nav Links -->
            <div class="nav-link-container">
                <?php if (isset($filteredNavItems) && is_array($filteredNavItems)): ?>
                    <?php foreach ($filteredNavItems as $path => $item): ?>
                        <?php
                        // Preskoči skrivene stavke
                        if (!empty($item['hidden'])) {
                            continue;
                        }
                        $isActive = ($currentPath === rtrim($path, '/'));
                        ?>
                        <!-- Glavna navigacijska stavka -->
                        <a href="<?= htmlspecialchars($path, ENT_QUOTES, 'UTF-8') ?>" class="nav-link <?= $isActive ? 'active' : '' ?>">
                            <img src="<?= htmlspecialchars($iconMap[$item['icon']] ?? '', ENT_QUOTES, 'UTF-8') ?>" width="20" height="20" alt="ikonica">
                            <span class="link-text"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                        </a>

                        <!-- Provjera i ispis podkategorija ako postoje -->
                        <?php if (isset($item['sub']) && is_array($item['sub'])): ?>
                            <?php foreach ($item['sub'] as $subPath => $subItem): ?>
                                <?php
                                $isSubActive = ($currentPath === rtrim($subPath, '/'));
                                ?>
                                <a href="<?= htmlspecialchars($subPath, ENT_QUOTES, 'UTF-8') ?>" class="nav-link sub-nav <?= $isSubActive ? 'active' : '' ?>">
                                    <img src="<?= htmlspecialchars($iconMap[$subItem['icon']] ?? '', ENT_QUOTES, 'UTF-8') ?>" width="20" height="20" alt="ikonica">
                                    <span class="link-text"><?= htmlspecialchars($subItem['label'], ENT_QUOTES, 'UTF-8') ?></span>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>


            <!-- Profile (Footer) -->
            <!-- Ažurirani footer s role_mask -->
            <div class="sidebar-footer">
                <div class="user-info">
                    <img src="/public/resources/icons/avatar.jpg" alt="User Avatar">
                    <div class="user-details">
                        <p class="user-name"><?= htmlspecialchars($username ?? 'Guest') ?></p>
                        <p class="user-role"><?= htmlspecialchars($auth_roles ?? '') ?></p>
                        <!-- Ako zelimo jos nesto mroamo u DashboardController proslijediti i ovdje uhvatiti -->
                    </div>
                </div>
                <a href="/logout" class="btn btn-outline-danger btn-sm logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="link-text">Logout</span>
                </a>
            </div>
        </nav>

        <!-- Toggle Button -->
        <button id="toggleSidebar" class="btn">
            <i class="bi bi-chevron-left"></i>
        </button>

        <!-- Content -->
        <main id="content">
            <?php
            if (isset($contentFile) && file_exists($contentFile)) {
                include $contentFile;
            } else {
                echo "<p>Content not available.</p>";
            }
            ?>
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                toggleBtn.classList.toggle('collapsed');
                content.classList.toggle('expanded');

                // Update toggle button icon
                const icon = toggleBtn.querySelector('i');
                if (sidebar.classList.contains('collapsed')) {
                    icon.classList.replace('bi-chevron-left', 'bi-chevron-right');
                } else {
                    icon.classList.replace('bi-chevron-right', 'bi-chevron-left');
                }
            });
        });
    </script>
</body>

</html>