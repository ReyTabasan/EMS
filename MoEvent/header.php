<style>
    .header-section {
        background-color: #343a40;
        color: white;
        text-align: center;
        padding: 10px 20px;
        font-size: 1.5rem;
        background-size: cover;
        background-attachment: fixed;
    }

    .header-section h1 {
        margin-bottom: 0;
        font-size: 1.5rem;
    }

    .admin-dropdown .btn {
        background-color: transparent;
        border: none;
        color: white;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
    }

    .admin-dropdown .dropdown-menu {
        background-color: #343a40;
        border: 1px solid #495057;
        color: red;
    }

    .admin-dropdown .dropdown-item a {
        color: red;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .header-section h1 {
            font-size: 1.2rem;
        }

        .admin-dropdown .btn {
            font-size: 0.9rem;
        }

        .admin-dropdown {
            position: absolute;
            right: 10px;
            top: 15px;
        }
    }
    .admin-dropdown {
            position: absolute;
            top: 10px;
            right: 20px; 
            z-index: 110;
        }

        #admin-toggle {
            background-color: transparent;
            border: none;
            outline: none;
            box-shadow: none;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 35px;
            right: 0;
            background-color: #fff;
            color: black;
            min-width: 160px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }

        .admin-dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f1f1f1;
        }
</style>

<div class="header-section py-2">
    <h1 class="mb-0">Event Management System</h1>
</div>

<div class="admin-dropdown">
    <div class="dropdown">
        <button class="btn btn-secondary" id="admin-toggle" style="background-color: transparent; border: none;">
            Admin <i class="fas fa-user"></i>
        </button>
        <div class="dropdown-menu" id="admin-dropdown-menu">
            <div class="dropdown-item"><a href="logout.php">Logout</a></div>
        </div>
    </div>
</div>

<script>
    document.getElementById('admin-toggle').addEventListener('click', function() {
        const menu = document.getElementById('admin-dropdown-menu');
        menu.classList.toggle('show');
    });
</script>
