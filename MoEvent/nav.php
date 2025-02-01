<style>
    .sidebar {
        width: 250px;
        background-color: #343a40;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 20px;
        transition: all 0.3s ease;
        z-index: 100;
    }

    .sidebar a {
        display: block;
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        font-size: 18px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .sidebar a:hover {
        background-color: #495057;
    }

    .sidebar a.active {
        background-color: #495057;
        font-weight: bold;
    }

    .hamburger {
        font-size: 30px;
        color: #fff;
        position: fixed;
        top: 20px;
        left: 20px;
        cursor: pointer;
        z-index: 101;
        display: none;
    }

    @media (max-width: 768px) {
        .sidebar {
            left: -250px;
        }

        .sidebar.show {
            left: 0;
        }

        .hamburger {
            display: block;
        }
    }
</style>

<div class="hamburger" id="hamburger-menu">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar-menu">
    <?php
    $current_page = basename($_SERVER['PHP_SELF'], ".php");
    ?>
    <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="events.php" class="<?php echo ($current_page == 'events') ? 'active' : ''; ?>"><i class="fas fa-cogs"></i> Events</a>
    <a href="accomplish.php" class="<?php echo ($current_page == 'accomplish') ? 'active' : ''; ?>"><i class="fas fa-image"></i> Accomplishments</a>
    <a href="users.php" class="<?php echo ($current_page == 'users') ? 'active' : ''; ?>"><i class="fas fa-user"></i> Users</a>
</div>

<script>
    const hamburgerMenu = document.getElementById('hamburger-menu');
    const sidebarMenu = document.getElementById('sidebar-menu');

    hamburgerMenu.addEventListener('click', () => {
        sidebarMenu.classList.toggle('show');
    });
</script>
