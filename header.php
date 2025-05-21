<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="asset/css/style.css">

    <style>
        /* Sidebar styles */
        #sidebar {
            width: 250px;
            background-color: rgb(233, 233, 219);
            color: #000000;
            position: fixed;
            height: 100%;
            padding: 15px;
            transition: all 0.3s;
            z-index: 1000;
        }

        #sidebar h2 {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
        }

        #sidebar ul li {
            margin: 10px 0;
        }

        #sidebar ul li a {
            color: #000000;
            text-decoration: none;
            display: block;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        #sidebar ul li a:hover {
            background-color: #495057;
            color: #fff;
        }

        /* Main content */
        body {
            display: flex;
            flex-direction: row;
        }

        #content {
            margin-left: 260px;
            padding: 20px;
            flex-grow: 1;
            width: calc(100% - 260px);
            transition: all 0.3s;
        }

        /* Toggle button for mobile */
        #sidebarToggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
        }

        /* Responsive styles */
        @media screen and (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }

            #content.active {
                margin-left: 250px;
            }

            #sidebarToggle {
                display: block;
            }

            body {
                overflow-x: hidden;
                /* Prevent horizontal scroll */
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <h2>Admin</h2>
        <ul>
            <li><a href="<?= BASE_URL ?>/dashboard"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/bacsi"><i class="fa-solid fa-user-doctor"></i> Bác sĩ</a></li>
            <li><a href="<?= BASE_URL ?>/lichkham"><i class="fa-solid fa-calendar-days"></i> Lịch khám</a></li>
            <li><a href="<?= BASE_URL ?>/benhnhan"><i class="fa-solid fa-bed"></i> Bệnh nhân</a></li>
            <a href="<?= BASE_URL ?>?controller=login&action=logout" class="btn btn-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
            </a>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="asset/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    content.classList.toggle('active');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isMobile = window.innerWidth < 769;
                if (isMobile && sidebar.classList.contains('active') &&
                    !sidebar.contains(event.target) &&
                    event.target !== sidebarToggle) {
                    sidebar.classList.remove('active');
                    content.classList.remove('active');
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                    content.classList.remove('active');
                }
            });
        });
    </script>
</body>
<button id="sidebarToggle" class="btn">
    <i class="fas fa-bars"></i>
</button>

</html>