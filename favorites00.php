<?php 
// Include database connection
include 'conection_db.php';
include 'header.php'; 
?>

<div class="content-wrapper">
    <h1 class="page-title">Избранные товары</h1>
    
    <div id="favorites-container">
        <div id="favorites-list">
            <!-- Favorites will be loaded here dynamically -->
        </div>
        
        <div class="favorites-empty" style="display: none;">
            <h2>У вас пока нет избранных товаров</h2>
            <p>Добавляйте товары в избранное, чтобы вернуться к ним позже</p>
            <a href="catalog.php" class="button9">Перейти в каталог</a>
        </div>
    </div>
</div>

<script>
    // Global variables
    let productData = null;
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    
    // Fetch data from JSON file
    async function fetchData() {
        try {
            const response = await fetch('vfd.json');
            productData = await response.json();
            renderFavorites();
        } catch (error) {
            console.error('Error loading data:', error);
        }
    }
    
    // Render favorites
    function renderFavorites() {
        const favoritesContainer = document.getElementById('favorites-list');
        const emptyContainer = document.querySelector('.favorites-empty');
        
        // Clear container
        favoritesContainer.innerHTML = '';
        
        if (favorites.length === 0) {
            favoritesContainer.style.display = 'none';
            emptyContainer.style.display = 'block';
            return;
        }
        
        // Show favorites container, hide empty message
        favoritesContainer.style.display = 'grid';
        emptyContainer.style.display = 'none';
        
        // Filter products to show only favorites
        const favoriteProducts = productData.products.filter(product => 
            favorites.includes(product.id)
        );
        
        // Create grid layout for favorites
        favoritesContainer.className = 'favorites-grid';
        
        // Render each favorite product
        favoriteProducts.forEach(product => {
            const productCard = createFavoriteCard(product);
            favoritesContainer.appendChild(productCard);
        });
        
        // Save favorites to database for logged in users
        if (favorites.length > 0) {
            saveFavoritesToDatabase();
        }
    }
    
    // Create a favorite product card
    function createFavoriteCard(product) {
        const card = document.createElement('div');
        card.className = 'product-card';
        
        // Get image URL - use placeholder if no image
        let imageUrl = '/api/placeholder/400/320';
        if (product.images && product.images.length > 0) {
            imageUrl = product.images[0].medium || product.images[0].thumbnail || '/api/placeholder/400/320';
        }
        
        card.innerHTML = `
            <div class="product-image-container">
                <img src="${imageUrl}" alt="${product.title}">
            </div>
            <h3>${product.full_title || product.title}</h3>
            <div class="product-price">${parseFloat(product.price).toLocaleString()} руб.</div>
            <div class="favorite-card-buttons">
                <button class="button9 add-to-cart-btn" onclick="addToCartFromFavorites(${product.id})">В корзину</button>
                <button class="button9 remove-favorite-btn" onclick="removeFromFavorites(${product.id})">Удалить</button>
            </div>
        `;
        
        return card;
    }
    
    // Remove product from favorites
    function removeFromFavorites(productId) {
        const index = favorites.indexOf(productId);
        
        if (index !== -1) {
            favorites.splice(index, 1);
            localStorage.setItem('favorites', JSON.stringify(favorites));
            
            // Update display
            renderFavorites();
            
            // Remove from database if user is logged in
            removeFavoriteFromDatabase(productId);
        }
    }
    
    // Add product to cart from favorites
    function addToCartFromFavorites(productId) {
        const product = productData.products.find(p => p.id === productId);
        
        if (!product) return;
        
        // Get cart from local storage or initialize empty array
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Check if product already in cart
        const existingItem = cart.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            // Add product to cart
            cart.push({
                id: product.id,
                title: product.full_title || product.title,
                price: parseFloat(product.price),
                quantity: 1,
                image: product.images && product.images.length > 0 ? 
                    (product.images[0].thumbnail || '/api/placeholder/400/320') : 
                    '/api/placeholder/400/320'
            });
        }
        
        // Save updated cart
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Show confirmation
        alert('Товар добавлен в корзину!');
    }
    
    // Save favorites to database (for logged in users)
    function saveFavoritesToDatabase() {
        // This function would use AJAX to save favorites to the database
        // Only implement if user authentication is set up
        
        // Example AJAX call (uncomment when ready to use)
        /*
        const userId = 1; // This would be the actual user ID from your authentication system
        
        fetch('save_favorites.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: userId,
                favorites: favorites
            }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Favorites saved:', data);
        })
        .catch((error) => {
            console.error('Error saving favorites:', error);
        });
        */
    }
    
    // Remove favorite from database
    function removeFavoriteFromDatabase(productId) {
        // Example AJAX call (uncomment when ready to use)
        /*
        const userId = 1; // This would be the actual user ID from your authentication system
        
        fetch('remove_favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: userId,
                product_id: productId
            }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Favorite removed:', data);
        })
        .catch((error) => {
            console.error('Error removing favorite:', error);
        });
        */
    }
    
    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        fetchData();
    });
</script>

<style>
    .page-title {
        margin-bottom: 30px;
        font-size: 2rem;
        text-align: center;
    }
    
    .favorites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .favorites-empty {
        text-align: center;
        padding: 40px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .favorites-empty h2 {
        margin-bottom: 15px;
        color: #333;
    }
    
    .favorites-empty p {
        margin-bottom: 20px;
        color: #666;
    }
    
    .favorite-card-buttons {
        display: flex;
        margin: 0 1rem 1rem;
        gap: 10px;
    }
    
    .favorite-card-buttons button {
        flex: 1;
        padding: 0.75rem 0;
    }
    
    .remove-favorite-btn {
        background-color: #d32f2f !important;
    }
    
    .remove-favorite-btn:hover {
        background-color: #b71c1c !important;
    }
</style>

<?php include 'footer.php'; ?>