<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header with Burger Menu</title>
    <link rel="stylesheet" href="css/header.css">
    <style>
       
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="burger-menu-icon">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
            <div class="logo">МебельМаркет</div>
            <nav>
                <ul>
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="index.php">Каталог</a></li>
                    <li><a href="uslugi.php">Услуги</a></li>
                    <li><a href="about.php">О компании</a></li>
                    <li><a href="delivery.php">Доставка</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                </ul>
            </nav>
            <div class="user-controls">
                <a href="favorites.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    Избранное
                </a>
                <a href="cart.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Корзина
                    <span class="cart-count" id="header-cart-count" style="display: inline-block;">30</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <div class="mobile-nav">
        <div class="mobile-nav-close">&times;</div>
        <div class="logo">МебельМаркет</div>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="index.php">Каталог</a></li>
                <li><a href="uslugi.php">Услуги</a></li>
                <li><a href="about.php">О компании</a></li>
                <li><a href="delivery.php">Доставка</a></li>
                <li><a href="contacts.php">Контакты</a></li>
            </ul>
        </nav>
        <div class="user-controls-mobile">
            <a href="favorites.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                Избранное
            </a>
            <a href="cart.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Корзина
            </a>
        </div>
    </div>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay"></div>

    <script>
        // Burger menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const burgerIcon = document.querySelector('.burger-menu-icon');
            const mobileNav = document.querySelector('.mobile-nav');
            const closeBtn = document.querySelector('.mobile-nav-close');
            const overlay = document.querySelector('.mobile-nav-overlay');
            
            // Toggle menu when burger icon is clicked
            burgerIcon.addEventListener('click', () => {
                mobileNav.classList.add('active');
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
            
            // Close menu when X is clicked
            closeBtn.addEventListener('click', () => {
                mobileNav.classList.remove('active');
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            });
            
            // Close menu when clicking outside
            overlay.addEventListener('click', () => {
                mobileNav.classList.remove('active');
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            });
        });
    </script>
</body>
</html>