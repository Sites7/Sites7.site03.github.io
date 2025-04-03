<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Избранное</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', Arial, sans-serif;
        }

        body {
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        /* Header Styles */
        header {
            background-color: #333;
            color: #fff;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav li {
            margin-left: 1.5rem;
        }
        
        nav a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: #ffcc00;
        }
        
        nav a.active {
            color: #ffcc00;
            font-weight: bold;
        }

        .burger-menu-icon {
            display: none;
            cursor: pointer;
            font-size: 24px;
        }

        .mobile-nav {
            position: fixed;
            top: 0;
            left: -300px;
            width: 280px;
            height: 100%;
            background-color: #333;
            z-index: 1000;
            padding: 20px;
            box-shadow: 4px 0 10px rgba(0,0,0,0.2);
            transition: left 0.3s ease-in-out;
        }

        .mobile-nav.active {
            left: 0;
        }

        .mobile-nav-close {
            color: #fff;
            font-size: 24px;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
        }

        .mobile-nav ul {
            list-style: none;
            margin-top: 40px;
        }

        .mobile-nav li {
            margin: 15px 0;
        }

        .mobile-nav a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }

        /* Main container */
        .favorites-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .favorites-empty {
            text-align: center;
            padding: 40px;
        }

        .favorites-empty p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .continue-shopping {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .continue-shopping:hover {
            background-color: #0056b3;
        }

        /* Favorites table styles */
        .favorites-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .favorites-table th {
            text-align: left;
            padding: 12px 15px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .favorites-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .favorites-table tr:last-child td {
            border-bottom: none;
        }

        .favorite-item-image img {
            width: 150px;
            height: 400px;
            object-fit: contain;
            border-radius: 4px;
        }

        .favorite-item-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .favorite-item-price {
            color: #666;
            margin-bottom: 5px;
        }

        .favorite-item-stock {
            margin-bottom: 5px;
        }

        .favorite-item-stock.in-stock {
            color: #28a745;
        }

        .favorite-item-stock.out-of-stock {
            color: #dc3545;
        }

        .favorite-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            min-width: 40px;
            height: 40px;
            background: none;
        }

        .remove-btn {
            color: #dc3545;
        }

        .remove-btn:hover {
            color: #a71d2a;
        }

        .cart-btn svg {
            width: 32px;
            height: 32px;
        }

        .cart-btn:hover {
            opacity: 0.9;
        }

        .cart-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .action-btn svg {
            width: 16px;
            height: 16px;
        }

        /* Cart count styles */
        .cart-count {
            display: inline-block;
            background-color: #ff5722;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            text-align: center;
            line-height: 20px;
            margin-left: 5px;
        }

        @media (max-width: 768px) {
            .burger-menu-icon {
                display: block;
            }
            
            nav {
                display: none;
            }

            .favorites-table {
                display: block;
                overflow-x: auto;
            }

            .favorite-actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- <header>
        <div class="header-container">
            <div class="logo">
                <a href="index.html">Каталог</a>
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Главная</a></li>
                    <li><a href="favorites.html" class="active">Избранное</a></li>
                    <li><a href="cart.html">Корзина <span class="cart-count" id="header-cart-count"></span></a></li>
                </ul>
            </nav>
            <div class="burger-menu-icon">☰</div>
        </div>
    </header> -->
    <?php include 'header.php'; ?>
    <div class="mobile-nav">
        <div class="mobile-nav-close">✕</div>
        <ul>
            <li><a href="index.html">Главная</a></li>
            <li><a href="favorites.html">Избранное</a></li>
            <li><a href="cart.html">Корзина <span class="cart-count" id="mobile-cart-count"></span></a></li>
        </ul>
    </div>

    <div class="mobile-nav-overlay"></div>

    <div class="favorites-container">
        <h1>Избранное</h1>
        
        <div id="favorites-empty" class="favorites-empty" style="display: none;">
            <p>В избранном пока нет товаров</p>
            <a href="index.html" class="continue-shopping">Перейти в каталог</a>
        </div>
        
        <table class="favorites-table">
            <tbody id="favoritesTableBody">
                <!-- Избранные товары будут добавлены сюда -->
            </tbody>
        </table>
    </div>
    <?php include 'footer.php'; ?>
    <script>
    function loadFavorites() {
        const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        const cart = JSON.parse(localStorage.getItem('cart')) || {};
        const allProducts = JSON.parse(localStorage.getItem('allProducts')) || {};
        const tableBody = document.getElementById('favoritesTableBody');
        const emptyMessage = document.getElementById('favorites-empty');
        
        if (favorites.length === 0) {
            tableBody.innerHTML = '';
            emptyMessage.style.display = 'block';
            return;
        }

        // Clear favorites container
        tableBody.innerHTML = '';
        emptyMessage.style.display = 'none';
        
        // Generate favorites items
        favorites.forEach(productId => {
            // If productId is an object with an id property, use that instead
            if (typeof productId === 'object' && productId.id) {
                productId = productId.id;
            }
            
            // Find the product in allProducts
            const product = allProducts[productId];
            
            if (!product) {
                console.warn(`Product with ID ${productId} not found in allProducts`);
                return;
            }
            
            // Safely access the image URL
            let imageUrl = 'placeholder.jpg';
            if (product.images && product.images.length > 0) {
                if (typeof product.images[0] === 'string') {
                    imageUrl = product.images[0];
                } else if (product.images[0] && product.images[0].thumbnail) {
                    imageUrl = product.images[0].thumbnail;
                } else if (product.images[0] && product.images[0].url) {
                    imageUrl = product.images[0].url;
                }
            } else if (product.image) {
                imageUrl = product.image;
            }
            
            const inCart = cart[productId] ? true : false;
            const inStock = (product.inStock || product.stock > 0);
            
            const row = document.createElement('tr');
            row.className = 'favorite-item';
            
            row.innerHTML = `
                <td class="favorite-item-image">
                    <img src="${imageUrl}" alt="${product.title || product.name}">
                </td>
                <td class="favorite-item-details">
                    <div class="favorite-item-title">${product.title || product.name}</div>
                    <div class="favorite-item-price">Цена: ${product.price} ₽</div>
                    <div class="favorite-item-stock ${inStock ? 'in-stock' : 'out-of-stock'}">
                        ${inStock ? 'В наличии' : 'Нет в наличии'}
                    </div>
                    ${product.category ? `<div class="favorite-item-category">Категория: ${product.category}</div>` : ''}
                </td>
                <td class="favorite-item-controls">
                    <div class="favorite-actions">
                     <button class="action-btn remove-btn" onclick="removeFromFavorites('${productId}')" title="Удалить из избранного">
    <svg width="32" height="32" viewBox="0 0 144 144" fill="none" stroke="currentColor" stroke-width="6">
        <!-- Верхняя часть корзины (крышка) -->
        <path d="M30 24h84M42 24V18c0-3.3 2.7-6 6-6h48c3.3 0 6 2.7 6 6v6" stroke-linecap="round" />
        
        <!-- Основная часть корзины -->
        <rect x="36" y="24" width="72" height="96" rx="6" />
        
        <!-- Линии на корзине для объема -->
        <line x1="60" y1="48" x2="60" y2="96" stroke-linecap="round" />
        <line x1="84" y1="48" x2="84" y2="96" stroke-linecap="round" />
    </svg>
</button>
</button>
<button class="cart-button ${inCart ? 'in-cart' : ''}" 
        onclick="event.stopPropagation(); toggleCart('${productId}');"
        ${!inStock ? 'disabled' : ''}>
    ${inCart ? 
        '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path fill="#000000" fill-rule="evenodd" d="M9.612 6.084C9.16 6.711 9 7.494 9 8v1h6V8c0-.507-.16-1.289-.611-1.916C13.974 5.508 13.274 5 12 5c-1.274 0-1.974.508-2.388 1.084zM17 9V8c0-.827-.24-2.044-.988-3.084C15.226 3.825 13.926 3 12 3c-1.926 0-3.226.825-4.012 1.916C7.24 5.956 7 7.173 7 8v1H3a1 1 0 0 0 0 2h.095l.91 9.1A1 1 0 0 0 5 21h14a1 1 0 0 0 .995-.9l.91-9.1H21a1 1 0 1 0 0-2h-4zm-8 5a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2z" clip-rule="evenodd"/></svg>' : 
        '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h1m17 0h-1m0 0-1 10H5L4 10m16 0h-4M4 10h4m4 4v2m3-2v2m-6-2v2m-1-6h8m-8 0V8c0-1.333.8-4 4-4s4 2.667 4 4v2"/></svg>'}
</button>
                    </div>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Update cart count
        updateCartCount();
    }   
        
    function removeFromFavorites(productId) {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        
        // Handle both object and primitive formats
        favorites = favorites.filter(item => {
            if (typeof item === 'object' && item.id) {
                return item.id != productId;
            }
            return item != productId;
        });
        
        // Save updated favorites
        localStorage.setItem('favorites', JSON.stringify(favorites));
        
        // Update page display
        loadFavorites();
        
        // Show notification
        showNotification('Товар удален из избранного');
    }

    function toggleCart(productId) {
        // Get current cart state
        let cart = JSON.parse(localStorage.getItem('cart')) || {};
        
        // Toggle cart state
        if (cart[productId]) {
            delete cart[productId];
            showNotification('Товар удален из корзины');
        } else {
            cart[productId] = 1;
            showNotification('Товар добавлен в корзину');
        }
        
        // Save the updated cart
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Refresh the favorites display
        loadFavorites();
    }
    
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart')) || {};
        let totalItems = 0;
        
        for (const productId in cart) {
            totalItems += cart[productId];
        }
        
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
            element.textContent = totalItems;
            element.style.display = totalItems > 0 ? 'inline-block' : 'none';
        });
    }
    
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.style.position = 'fixed';
        notification.style.bottom = '20px';
        notification.style.right = '20px';
        notification.style.padding = '12px 20px';
        notification.style.backgroundColor = '#28a745';
        notification.style.color = 'white';
        notification.style.borderRadius = '4px';
        notification.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
        notification.style.zIndex = '1000';
        notification.style.transition = 'opacity 0.3s';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    
    function setupBurgerMenu() {
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
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        loadFavorites();
        setupBurgerMenu();
    });
    </script>
</body>
</html>