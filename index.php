<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог товаров</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <style>
/* Original reset and basic styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Roboto', Arial, sans-serif;
}

body {
    line-height: 1.6;
    background-color: #f4f4f4;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Main content layout with new responsive approach */
#container_cart {
    display: flex;
    max-width: 1200px;
    margin: 20px auto;
    flex: 1;
    padding: 0 20px;
    gap: 20px;
}

/* Added title styling from second CSS */
.section-title {
    font-size: 3rem;
    font-weight: 700;
    margin: 0 0 20px 0;
}

.section-divider {
    width: 150px;
    height: 3px;
    background-color: #333;
    margin: 30px 0;
    transform-origin: left center;
}

.section-subtitle {
    font-style: italic;
    font-size: 1.25rem;
    margin: 0 0 30px 0;
}

#sidebar {
    width: 280px;
    min-width: 280px;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    height: fit-content;
}

#main-content {
    flex: 1;
}

/* Filter styles */
.filter-group {
    margin-bottom: 15px;
}

.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.filter-group input[type="text"],
.filter-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.price-inputs {
    display: flex;
    gap: 10px;
    margin-bottom: 5px;
}

.price-inputs input {
    width: calc(50% - 5px);
    background-color: #ffffff;
    color: #000000;
    border: 2px solid #02a6ec;
    padding: 5px;
    box-sizing: border-box;
}

.price-inputs input::placeholder {
    color: #999;
}

.ui-widget-content {
    border: 1px solid #bdc3c7;
    background: #e1e1e1;
    color: #222222;
    margin-top: 4px;
}

.ui-slider .ui-slider-handle {
    cursor: default;
    width: 2em;
    height: 1.2em;
    background: #d8ec02;
    color: #000000;
    text-align: center;
    margin-left: -1em;
}

#slider {
    margin: 10px 8px 20px 8px;
    width: calc(100% - 16px);
}

#sidebar button {
    background-color: #333;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 5px;
    width: 100%;
    transition: background-color 0.3s;
}

#sidebar button:hover {
    background-color: #555;
}

#sidebar button:last-child {
    background-color: #666;
    margin-top: 10px;
}

/* Products grid - adapted to use repeater approach from second CSS */
#product-list {
    display: grid;
    grid-template-columns: repeat(4, 25%); /* Changed from 3 columns to 4 for smaller cards */
    grid-gap: 15px; /* Reduced gap between cards */
    margin-bottom: 20px;
}

.product-card {
    position: relative;
    background-color: white;
    border-radius: 8px;
    padding: 15px; /* Reduced padding from 22px to 15px */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    display: flex;
    flex-direction: column;
}

.product-card img {
    width: 100%;
    height: 120px; /* Reduced height from 150px to 120px */
    object-fit: contain;
    margin: 0 auto 5px; /* Reduced bottom margin from 10px to 5px */
    border-radius: 4px;
}

.product-card h3 {
    margin: 15px 0 5px 0; /* Reduced margin from 32px to 15px top and 10px to 5px bottom */
    text-align: center;
    font-size: 1.5rem; /* Reduced font size from 1.875rem to 1.5rem */
    letter-spacing: normal;
    text-transform: none;
}

.product-card p {
    color: #666;
    font-size: 0.85em; /* Reduced font size from 0.9em to 0.85em */
    font-style: italic;
    line-height: 1.8; /* Reduced line height from 2 to 1.8 */
    margin: 20px 0 0; /* Reduced top margin from 30px to 20px */
    text-align: center;
}

.price {
    font-weight: bold;
    color: #333;
    text-align: center;
    margin: 8px 0; /* Reduced margin from 10px to 8px */
}

.availability {
    text-align: center;
    color: green;
    margin-bottom: 8px; /* Reduced margin from 10px to 8px */
}

.availability:not(:empty)::before {
    content: "• ";
}

.favorite-btn {
    position: absolute;
    top: 10px;
    right: 20px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 24px;
    color: #ccc;
    transition: color 0.3s;
    z-index: 1;
}

.favorite-btn.active {
    color: red;
}

.cart-button {
    background: none;
    border: none;
    cursor: pointer;
    align-self: center;
    padding: 5px;
    transition: transform 0.3s;
}

.cart-button:hover {
    transform: scale(1.1);
}

.cart-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

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

.color-preview {
    display: flex;
    justify-content: center;
    margin: 5px 0;
    flex-wrap: wrap;
}

.color-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin: 0 3px;
    border: 1px solid #ddd;
    cursor: pointer;
}

.size-preview {
    text-align: center;
    font-size: 0.9em;
    color: #666;
    margin: 5px 0;
}

/* Pagination styles */
#pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
    margin-bottom: 30px;
}

#pagination button {
    background-color: #333;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 8px 12px;
    cursor: pointer;
    transition: background-color 0.3s;
}

#pagination button:hover {
    background-color: #555;
}

#page-info {
    display: flex;
    align-items: center;
    padding: 0 10px;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    overflow: auto;
}

.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: 5% auto;
    padding: 25px;
    border-radius: 8px;
    width: 80%;
    max-width: 900px;
    display: flex;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    animation: modalFadeIn 0.3s;
}

@keyframes modalFadeIn {
    from {opacity: 0; transform: translateY(-30px);}
    to {opacity: 1; transform: translateY(0);}
}

.modal-column {
    flex: 1;
    padding: 0 15px;
}

.modal-column:first-child {
    border-right: 1px solid #eee;
}

#modal-image {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
    margin: 15px 0;
    border-radius: 4px;
}

#modal-title {
    font-size: 1.6em;
    margin-bottom: 15px;
    color: #333;
}

#modal-price {
    font-size: 1.4em;
    color: #333;
    margin: 15px 0;
}

.sizes-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}

.size-option {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.size-option:hover, .size-option.selected {
    background-color: #333;
    color: white;
    border-color: #333;
}

#color-options {
    margin-bottom: 20px;
}

.color-option {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin: 0 8px 8px 0;
    border: 1px solid #ddd;
    cursor: pointer;
    transition: transform 0.2s;
    display: inline-block;
}

.color-option:hover, .color-option.selected {
    transform: scale(1.1);
    box-shadow: 0 0 0 2px #333;
}

#modal-features {
    padding-left: 20px;
    margin-bottom: 20px;
}

#modal-features li {
    margin-bottom: 8px;
    color: #555;
}

.favorite-button {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.3s, transform 0.3s;
}

.favorite-button:hover {
    opacity: 1;
    transform: scale(1.1);
}

.favorite-button.active {
    opacity: 1;
}

.button9 {
    display: inline-block;
    padding: 12px 24px;
    background-color: #333;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    text-align: center;
    transition: background-color 0.3s;
    margin-top: 15px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    font-size: 16px;
}

.button9:hover {
    background-color: #555;
}

.button9.in-cart {
    background-color: #4CAF50;
}

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 28px;
    cursor: pointer;
    color: #aaa;
    transition: color 0.3s;
}

.close:hover {
    color: #333;
}

/* Mobile burger menu styles */
.burger-menu-icon {
    display: none;
    cursor: pointer;
}

.bar1, .bar2, .bar3 {
    width: 30px;
    height: 3px;
    background-color: #fff;
    margin: 6px 0;
    transition: 0.4s;
}

.mobile-nav {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 80%;
    max-width: 300px;
    height: 100%;
    background-color: #333;
    z-index: 1000;
    padding: 20px;
    transform: translateX(-100%);
    transition: transform 0.3s ease-in-out;
}

.mobile-nav.active {
    transform: translateX(0);
}

.mobile-nav-close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 30px;
    color: white;
    cursor: pointer;
}

.mobile-nav-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    z-index: 999;
}

/* Responsive styles - adapted from second CSS */
@media (max-width: 1199px) {
    .section-title {
        font-size: 2.5rem;
    }
    {
        
        #container_cart {
            padding: 0 10px;
        }
        
        #product-list {
            grid-template-columns: repeat(3, 33.333%); /* 3 columns on large screens */
        }
 
    
    .product-card h3 {
        font-size: 1.75rem;
    }
    
    .section-subtitle {
        width: auto;
        margin-top: 17px;
        margin-left: 22px;
    }
}

@media (max-width: 991px) {
    #container_cart {
        padding: 0 8px;
    }
    
    #sidebar {
        padding: 15px;
    }
    
    .section-subtitle {
        margin-left: 0;
    }
    #product-list {
        grid-template-columns: repeat(3, 33.333%); /* Still 3 columns on medium screens */
    }

}

@media (max-width: 767px) {
    #container_cart {
        flex-direction: column;
        padding: 10px;
    }
    
    #sidebar {
        width: 100%;
        margin-bottom: 20px;
        min-width: auto;
    }
    
    .section-title {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .section-subtitle {
        padding-top: 0;
    }
    
    #product-list {
        grid-template-columns: repeat(2, 50%); /* 2 columns on tablets */
    }
    
    .product-card {
        padding: 12px; /* Further reduced padding on smaller screens */
    }
    
    .product-card h3 {
        font-size: 1.3rem; /* Smaller font on tablets */
    }
    
    .burger-menu-icon {
        display: block;
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 100;
    }
    
    .header-container .logo {
        margin-left: 40px;
    }
    
    .header-container .phone-number,
    .header-container nav,
    .header-container .user-controls {
        display: none;
    }
    
    .modal-content {
        flex-direction: column;
        width: 95%;
        margin: 10% auto;
        padding: 15px;
    }
    
    .modal-column:first-child {
        border-right: none;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    
    .footer-container {
        flex-direction: column;
    }
    
    .footer-section {
        margin-bottom: 25px;
    }
}

@media (max-width: 575px) {
    .section-title {
        font-size: 1.875rem;
    }
    
    .product-card {
        padding: 30px;
    }
}


@media (max-width: 480px) {
    #product-list {
        grid-template-columns: repeat(2, 50%); /* Keep 2 columns on mobile */
    }
    
    .product-card {
        padding: 10px; /* Minimal padding on mobile */
    }
    
    .product-card h3 {
        font-size: 1.1rem; /* Even smaller font on mobile */
        margin-top: 10px;
    }
    
    .product-card img {
        height: 100px; /* Smaller images on mobile */
    }
}
</style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div id="container_cart">
        <div id="sidebar">
            <h2>Фильтры</h2>
            <div class="filter-group">
                <label for="search">Поиск по названию:</label>
                <input type="text" id="search" placeholder="Введите название">
            </div>
            
            <div class="filter-group">
                <label>Цена:</label>
                <div class="price-inputs">
                    <input type="number" id="minPrice" placeholder="От">
                    <input type="number" id="maxPrice" placeholder="До">
                </div>
                <div id="slider"></div>
            </div>
            
            <div class="filter-group">
                <label for="brand">Бренд:</label>
                <select id="brand">
                    <option value="">-- Выберите бренд --</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="category">Категория:</label>
                <select id="category" disabled>
                    <option value="">-- Сначала выберите бренд --</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="series">Серия:</label>
                <select id="series" disabled>
                    <option value="">-- Сначала выберите категорию --</option>
                </select>
            </div>
            <button onclick="applyFilters()">Применить фильтры</button>
            <button onclick="resetFilters()">Сбросить фильтры</button>
            <button onclick="clearSelections()">Очистить выбор фильтров</button>
        </div>
        
        <div id="main-content">
            <div id="product-list"></div>
            <div id="pagination">
                <button onclick="changePage('first')"><<</button>
                <button onclick="changePage('prev')"><</button>
                <span id="page-info"></span>
                <button onclick="changePage('next')">></button>
                <button onclick="changePage('last')">>></button>
            </div>
        </div>
    </div>
    
    <!-- Модальное окно -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-column">
                <h2 id="modal-title"></h2>
                <img id="modal-image" src="" alt="Product Image">
                <h3 id="modal-price"></h3>
                <h3>Размеры:</h3>
                <div class="sizes-list" id="size-options"></div>
                <h3>Цвета:</h3>
                <div id="color-options" style="display: flex; flex-wrap: wrap;"></div>
            </div>
            <div class="modal-column">
                <h3>Характеристики:</h3>
                <ul id="modal-features"></ul>
                <button class="favorite-button" id="add-to-favorites" data-id="0" onclick="toggleFavorite(this)">❤️</button>
                <button class="cart-button1" id="add-to-cart" onclick="event.stopPropagation(); toggleCartFromModal();">
                    <svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none">
                        <path stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h1m17 0h-1m0 0-1 10H5L4 10m16 0h-4M4 10h4m4 4v2m3-2v2m-6-2v2m-1-6h8m-8 0V8c0-1.333.8-4 4-4s4 2.667 4 4v2"/>
                    </svg>
                </button>
            </div>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js"></script>
    <script>
      // Глобальные переменные для данных и состояния
let rawData = {};
let filteredProducts = [];
const itemsPerPage = 8;
let currentPage = 1;
let doorData = {};
let allProducts = []; // Для хранения всех продуктов из всех серий
let minPriceFilter = 0;
let maxPriceFilter = 80000;
document.getElementById('maxPrice').addEventListener('change', function(e) {
    const value = Number(e.target.value) || 0;
    // Проверка, чтобы максимальная цена не была меньше минимальной
    if (value < $("#slider").slider("values", 0)) {
        e.target.value = $("#slider").slider("values", 1);
        return;
    }
    $("#slider").slider("values", 1, value);
    maxPriceFilter = value;
    currentPage = 1;
    filterProducts();
});

$(function() {
    $("#slider").slider({
        range: true,
        min: 0,
        max: 80000,
        // step: 100, // Установка шага ползунка на 100
        values: [0, 80000],
        slide: function(event, ui) {
            $("#minPrice").val(ui.values[0]);
            $("#maxPrice").val(ui.values[1]);
            minPriceFilter = ui.values[0];
            maxPriceFilter = ui.values[1];
        },
        stop: function(event, ui) {
            currentPage = 1;
            filterProducts();
        },
        // Добавляем поддержку сенсорных устройств
        create: function() {
            $('.ui-slider-handle').attr('aria-valuemin', 0)
                                 .attr('aria-valuemax', 80000)
                                 .attr('role', 'slider');
        }
    });
    
    // Инициализация значений
    $("#minPrice").val($("#slider").slider("values", 0));
    $("#maxPrice").val($("#slider").slider("values", 1));
    
    // Улучшаем обработку ввода для мобильных устройств
    document.getElementById('minPrice').addEventListener('input', function(e) {
        const value = Number(e.target.value) || 0;
        const maxValue = $("#slider").slider("values", 1);
        const validValue = Math.min(value, maxValue);
        
        if (value !== validValue) {
            e.target.value = validValue;
        }
        
        $("#slider").slider("values", 0, validValue);
        minPriceFilter = validValue;
    });

    document.getElementById('maxPrice').addEventListener('input', function(e) {
        const value = Number(e.target.value) || 0;
        const minValue = $("#slider").slider("values", 0);
        const validValue = Math.max(value, minValue);
        
        if (value !== validValue) {
            e.target.value = validValue;
        }
        
        $("#slider").slider("values", 1, validValue);
        maxPriceFilter = validValue;
    });

    // Добавляем задержку для фильтрации после ввода вручную
    let priceTimeout;
    [document.getElementById('minPrice'), document.getElementById('maxPrice')].forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(() => {
                currentPage = 1;
                filterProducts();
            }, 500);
        });
    });
});

$(function() {
    $("#slider").slider({
        range: true,
        min: 0,
        max: 80000,
        values: [0, 80000],
        slide: function(event, ui) {
            $("#minPrice").val(ui.values[0]);
            $("#maxPrice").val(ui.values[1]);
            // Update filter variables
            minPriceFilter = ui.values[0];
            maxPriceFilter = ui.values[1];
            // Don't filter while sliding to avoid performance issues
        },
        stop: function(event, ui) {
            // Apply filters only when sliding stops to improve performance
            currentPage = 1;
            filterProducts();
        }
    });
    
    // Initialize input values
    $("#minPrice").val($("#slider").slider("values", 0));
    $("#maxPrice").val($("#slider").slider("values", 1));
});

// Handle minPrice input changes
document.getElementById('minPrice').addEventListener('input', function(e) {
    const value = Number(e.target.value) || 0;
    const maxValue = $("#slider").slider("values", 1);
    
    // Ensure min doesn't exceed max
    const validValue = Math.min(value, maxValue);
    
    // Update the input if value was adjusted
    if (value !== validValue) {
        e.target.value = validValue;
    }
    
    // Update slider
    $("#slider").slider("values", 0, validValue);
    minPriceFilter = validValue;
});

// Handle minPrice change event (when user presses Enter or leaves the field)
document.getElementById('minPrice').addEventListener('change', function(e) {
    currentPage = 1;
    filterProducts();
});

// Handle maxPrice input changes
document.getElementById('maxPrice').addEventListener('input', function(e) {
    const value = Number(e.target.value) || 0;
    const minValue = $("#slider").slider("values", 0);
    
    // Ensure max isn't below min
    const validValue = Math.max(value, minValue);
    
    // Update the input if value was adjusted
    if (value !== validValue) {
        e.target.value = validValue;
    }
    
    // Update slider
    $("#slider").slider("values", 1, validValue);
    maxPriceFilter = validValue;
});

// Handle maxPrice change event (when user presses Enter or leaves the field)
document.getElementById('maxPrice').addEventListener('change', function(e) {
    currentPage = 1;
    filterProducts();
});

// Сохраняем выбранные фильтры при их изменении
function saveFilterSelections() {
    const filterSelections = {
        brand: document.getElementById('brand').value,
        category: document.getElementById('category').value,
        series: document.getElementById('series').value,
        currentPage: currentPage, // Сохраняем текущую страницу
        minPrice: minPriceFilter,
        maxPrice: maxPriceFilter
    };
    
    localStorage.setItem('filterSelections', JSON.stringify(filterSelections));
}

// Восстанавливаем выбранные фильтры и запускаем фильтрацию
async function restoreFilterSelections() {
    const savedSelections = localStorage.getItem('filterSelections');
    if (!savedSelections) return;
    
    try {
        const filterSelections = JSON.parse(savedSelections);
        
        // Ждем загрузки данных о дверях
        if (Object.keys(doorData).length === 0) {
            await new Promise(resolve => {
                const checkInterval = setInterval(() => {
                    if (Object.keys(doorData).length > 0) {
                        clearInterval(checkInterval);
                        resolve();
                    }
                }, 100);
            });
        }
        
        // Восстанавливаем цены
        if (filterSelections.minPrice !== undefined && filterSelections.maxPrice !== undefined) {
            minPriceFilter = parseInt(filterSelections.minPrice);
            maxPriceFilter = parseInt(filterSelections.maxPrice);
            $("#slider").slider("values", [minPriceFilter, maxPriceFilter]);
            $("#minPrice").val(minPriceFilter);
            $("#maxPrice").val(maxPriceFilter);
        }
        
        // Восстанавливаем выбор бренда
        const brandSelect = document.getElementById('brand');
        if (filterSelections.brand && brandSelect) {
            brandSelect.value = filterSelections.brand;
            
            // Вручную запускаем обновление опций категорий
            updateCategoryOptions();
            
            // Ждем заполнения опций категорий
            await new Promise(resolve => setTimeout(resolve, 50));
            
            // Восстанавливаем выбор категории
            const categorySelect = document.getElementById('category');
            if (filterSelections.category && categorySelect) {
                categorySelect.value = filterSelections.category;
                
                // Вручную запускаем обновление опций серий
                updateSeriesOptions();
                
                // Ждем заполнения опций серий
                await new Promise(resolve => setTimeout(resolve, 50));
                
                // Восстанавливаем выбор серии
                const seriesSelect = document.getElementById('series');
                if (filterSelections.series && seriesSelect) {
                    seriesSelect.value = filterSelections.series;
                }
            }
        }        
        // Применяем фильтры для отображения отфильтрованных продуктов
        // Но не сбрасываем номер страницы
        filterProductsWithoutPageReset();
        
        // Восстанавливаем номер страницы после фильтрации
        if (filterSelections.currentPage) {
            currentPage = parseInt(filterSelections.currentPage);
            renderProducts();
            renderPagination();
        }        
    } catch (error) {
        console.error('Ошибка восстановления выбранных фильтров:', error);
    }
}

// Добавляем новую функцию, которая фильтрует продукты без сброса номера страницы
function filterProductsWithoutPageReset() {
    const searchQuery = document.getElementById('search').value.toLowerCase();
    const selectedBrand = document.getElementById('brand').value;
    const selectedCategory = document.getElementById('category').value;
    const selectedSeries = document.getElementById('series').value;

    if (!rawData.products) {
        console.error('Данные о товарах не загружены');
        return;
    }

    filteredProducts = rawData.products.filter(product => {
        return (
            (product.title.toLowerCase().includes(searchQuery)) &&
            (product.price >= minPriceFilter && product.price <= maxPriceFilter) &&
            (!selectedBrand || product.brand === selectedBrand) &&
            (!selectedCategory || product.category === selectedCategory) &&
            (!selectedSeries || product.series === selectedSeries)
        );
       });
    // Не сбрасываем текущую страницу
    renderProducts();
    renderPagination();
}

// Обновляем существующие обработчики событий для элементов select

// Обновляем функцию готовности документа, чтобы включить наш код
document.addEventListener('DOMContentLoaded', function() {
    loadDoorData();
    fetchData()
        .then(() => {
            restoreFilterSelections(); // Восстанавливаем выбор после загрузки данных
        })
        .catch(error => {
            console.error('Ошибка загрузки данных:', error);
        });
    
    setupBurgerMenu();
    updateCartCount();
    
    // Добавляем обработчики событий для сохранения выбора при изменении
    document.getElementById('brand').addEventListener('change', function() {
        updateCategoryOptions();
        saveFilterSelections();
    });
    
    document.getElementById('category').addEventListener('change', function() {
        updateSeriesOptions();
        saveFilterSelections();
    });
    
    document.getElementById('series').addEventListener('change', function() {
        handleSeriesChange();
        saveFilterSelections();
    });
    
    // Добавляем обработчики для других элементов фильтра
    document.getElementById('search').addEventListener('input', saveFilterSelections);
});

async function loadDoorData() {
    try {
        const response1 = await fetch('1.json');
        doorData = await response1.json();
        populateFilterOptions();
    } catch (error) {
        console.error('Ошибка загрузки структуры категорий:', error);
        doorData = { brands: [] };
    }
}

async function fetchData() {
    try {
        // Загружаем комбинированный файл zador.json
        const response = await fetch('zador.json');
        const data = await response.json();
        if (data==false){
            alert("Не грузилось");
        } 
        // Инициализируем объект для хранения всех продуктов
        rawData = { products: [] };
        allProducts = [];
        
        // Обрабатываем каждую серию
        for (const seriesName in data) {
            const seriesData = data[seriesName];
            
            // Преобразуем данные для каждого продукта в серии
            const seriesProducts = seriesData.map((item, index) => {
                const attributes = {};
                const features = [];
                
                if (item._ATTRIBUTES_) {
                    const attrLines = item._ATTRIBUTES_.split('\n');
                    attrLines.forEach(line => {
                        const [category, key, value] = line.split('|');
                        if (key && value) {
                            features.push(`${key}: ${value}`);
                            attributes[key] = value;
                        }
                    });
                }
                
                let sizes = [];
                if (attributes['Размер']) {
                    sizes = attributes['Размер'].split(', ').map(size => size.trim());
                }
                
                let colors = [];
                if (attributes['Цвет']) {
                    const colorMap = {
                        'Грей': '#808080',
                        'Оливковый': '#808000',
                        'Темно-серый': '#696969',
                        'Белый': '#FFFFFF',
                        'Черный': '#000000'
                    };
                    const colorName = attributes['Цвет'];
                    colors.push(colorMap[colorName] || colorName);
                }
                
                // Используем уникальный идентификатор для каждого продукта
                const uniqueId = `${seriesName}-${index + 1}`;
                
                return {
                    id: uniqueId,
                    title: item._NAME_ || 'Без названия',
                    brand: 'ZADOOR',
                    category: 'Межкомнатные двери', // Можно изменить при необходимости
                    series: seriesName,
                    price: item._PRICE_ || 0,
                    images: [{ thumbnail: item._IMAGE_ || 'placeholder.jpg' }],
                    features: features,
                    sizes: sizes,
                    colors: colors,
                    description: item._DESCRIPTION_ || '',
                    inStock: true
                };
            });
            
            // Добавляем продукты серии к общему списку
            allProducts = [...allProducts, ...seriesProducts];
        }
        
        // Присваиваем все продукты в rawData
        rawData.products = allProducts;
        
        // Фильтруем и отображаем продукты
        filterProducts();
    } catch (error) {
        console.error(`Ошибка загрузки данных:`, error);
        document.getElementById('product-list').innerHTML = `
            <div class="no-results">
                Не удалось загрузить данные. Пожалуйста, попробуйте позже.
            </div>
        `;
    }
}

function populateFilterOptions() {
    const brandSelect = document.getElementById('brand');
    brandSelect.innerHTML = '<option value="">-- Выберите бренд --</option>';
    
    doorData.brands.forEach(brand => {
        const option = document.createElement('option');
        option.value = brand.name;
        option.textContent = brand.name;
        brandSelect.appendChild(option);
    });
    
    document.getElementById('brand').addEventListener('change', updateCategoryOptions);
    document.getElementById('category').addEventListener('change', updateSeriesOptions);
    document.getElementById('series').addEventListener('change', handleSeriesChange);
}

// Модифицируем функцию handleSeriesChange для фильтрации продуктов
function handleSeriesChange() {
    const selectedSeries = document.getElementById('series').value;
    if (selectedSeries) {
        filterProducts(); // Фильтруем продукты при выборе серии
    }
}

// Опционально: добавляем кнопку очистки выбора, если необходимо
function clearSelections() {
    document.getElementById('brand').value = '';
    document.getElementById('category').value = '';
    document.getElementById('category').disabled = true;
    document.getElementById('series').value = '';
    document.getElementById('series').disabled = true;
    document.getElementById('search').value = '';
    
    // Сбрасываем цены
    minPriceFilter = 0;
    maxPriceFilter = 80000;
    $("#slider").slider("values", [minPriceFilter, maxPriceFilter]);
    $("#minPrice").val(minPriceFilter);
    $("#maxPrice").val(maxPriceFilter);
    
    localStorage.removeItem('filterSelections');
    currentPage = 1;
    filterProducts(); // Сбрасываем для отображения всех продуктов
}

function updateCategoryOptions() {
    const brandSelect = document.getElementById('brand');
    const categorySelect = document.getElementById('category');
    const selectedBrand = brandSelect.value;
    
    categorySelect.innerHTML = '<option value="">-- Выберите категорию --</option>';
    
    if (!selectedBrand) {
        categorySelect.disabled = true;
        document.getElementById('series').disabled = true;
        document.getElementById('series').innerHTML = '<option value="">-- Сначала выберите категорию --</option>';
        return;
    }
    
    const brand = doorData.brands.find(b => b.name === selectedBrand);
    if (brand) {
        categorySelect.disabled = false;
        brand.categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.type;
            option.textContent = category.type;
            categorySelect.appendChild(option);
        });
    }
    
    document.getElementById('series').disabled = true;
    document.getElementById('series').innerHTML = '<option value="">-- Сначала выберите категорию --</option>';
}

function updateSeriesOptions() {
    const brandSelect = document.getElementById('brand');
    const categorySelect = document.getElementById('category');
    const seriesSelect = document.getElementById('series');
    const selectedBrand = brandSelect.value;
    const selectedCategory = categorySelect.value;
    
    seriesSelect.innerHTML = '<option value="">-- Выберите серию --</option>';
    
    if (!selectedCategory) {
        seriesSelect.disabled = true;
        return;
    }
    
    const brand = doorData.brands.find(b => b.name === selectedBrand);
    if (brand) {
        const category = brand.categories.find(c => c.type === selectedCategory);
        if (category) {
            seriesSelect.disabled = false;
            category.series.forEach(series => {
                const option = document.createElement('option');
                option.value = series;
                option.textContent = series;
                seriesSelect.appendChild(option);
            });
        }
    }
}

// Modify the original filterProducts function to save state
function filterProducts() {
    const searchQuery = document.getElementById('search').value.toLowerCase();
    const selectedBrand = document.getElementById('brand').value;
    const selectedCategory = document.getElementById('category').value;
    const selectedSeries = document.getElementById('series').value;

    if (!rawData.products) {
        console.error('Данные о товарах не загружены');
        return;
    }

    filteredProducts = rawData.products.filter(product => {
        return (
            (product.title.toLowerCase().includes(searchQuery)) &&
            (product.price >= minPriceFilter && product.price <= maxPriceFilter) &&
            (!selectedBrand || product.brand === selectedBrand) &&
            (!selectedCategory || product.category === selectedCategory) &&
            (!selectedSeries || product.series === selectedSeries)
        );
    });

    // Reset to first page when filters change
    currentPage = 1;
    renderProducts();
    renderPagination();
    
    // Save the current state
    saveFilterSelections();
}

function renderProducts() {
    const productList = document.getElementById('product-list');
    if (!productList) {
        console.error('Элемент product-list не найден');
        return;
    }
    
    productList.innerHTML = '';

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const productsToDisplay = filteredProducts.slice(startIndex, endIndex);

    const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    const cart = JSON.parse(localStorage.getItem('cart')) || {};

    if (productsToDisplay.length === 0) {
        productList.innerHTML = '<div class="no-results">Нет товаров, соответствующих заданным критериям</div>';
        return;
    }

    productsToDisplay.forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';
        
        const isFavoriteProduct = favorites.some(fav => fav.id === product.id);
        const isInCart = cart[product.id] > 0;
        
        const colorElements = product.colors && product.colors.length > 0 
            ? `<div class="color-preview">${product.colors.map(color => 
                `<div class="color-dot" style="background-color: ${color}"></div>`).join('')}</div>`
            : '';
        
        const sizeElements = product.sizes && product.sizes.length > 0
            ? `<div class="size-preview">${product.sizes.slice(0, 3).join(', ')}${product.sizes.length > 3 ? '...' : ''}</div>`
            : '';
        
        card.innerHTML = `
            <div class="product-card-inner">
                <button class="favorite-btn ${isFavoriteProduct ? 'active' : ''}"
                        onclick="event.stopPropagation(); toggleFavorite(this)" data-id="${product.id}">
                    ♥
                </button>
                
                <button class="cart-button ${isInCart ? 'in-cart' : ''}" 
                        onclick="event.stopPropagation(); toggleCart('${product.id}');"
                        ${!product.inStock ? 'disabled' : ''}>
                    ${isInCart ? 
                        '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path fill="#000000" fill-rule="evenodd" d="M9.612 6.084C9.16 6.711 9 7.494 9 8v1h6V8c0-.507-.16-1.289-.611-1.916C13.974 5.508 13.274 5 12 5c-1.274 0-1.974.508-2.388 1.084zM17 9V8c0-.827-.24-2.044-.988-3.084C15.226 3.825 13.926 3 12 3c-1.926 0-3.226.825-4.012 1.916C7.24 5.956 7 7.173 7 8v1H3a1 1 0 0 0 0 2h.095l.91 9.1A1 1 0 0 0 5 21h14a1 1 0 0 0 .995-.9l.91-9.1H21a1 1 0 1 0 0-2h-4zm-8 5a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2z" clip-rule="evenodd"/></svg>' : 
                        '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h1m17 0h-1m0 0-1 10H5L4 10m16 0h-4M4 10h4m4 4v2m3-2v2m-6-2v2m-1-6h8m-8 0V8c0-1.333.8-4 4-4s4 2.667 4 4v2"/></svg>'}
                </button>
                <img src="${product.images[0]?.thumbnail || 'placeholder.jpg'}" alt="${product.title}">
                <h3>${product.title}</h3>
                <p class="product-series">Серия: ${product.series}</p>
                <h3>Цена: ${product.price} руб.</h3>          
            </div>
            

        `;
        
        const cardInner = card.querySelector('.product-card-inner');
        cardInner.addEventListener('click', function() {
            openModal(product.id);
        });
        
        productList.appendChild(card);
    });
    
    updateCartCount();
    // updatePagination();
    renderPagination(); // Правильная функция, которая определена
}
function toggleCart(productId) {
    try {
        console.log("Переключение корзины для: ", productId);
        
        const cart = JSON.parse(localStorage.getItem('cart')) || {};
        
        // Если товар уже в корзине - удаляем, иначе добавляем
        if (cart[productId]) {
            delete cart[productId];
        } else {
            cart[productId] = 1;
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Обновляем все кнопки корзины для этого товара
        const buttons = document.querySelectorAll(`.cart-button[onclick*="${productId}"]`);
        buttons.forEach(button => {
            button.classList.toggle('in-cart', cart[productId] > 0);
            button.innerHTML = cart[productId] > 0 ? 
                '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path fill="#000000" fill-rule="evenodd" d="M9.612 6.084C9.16 6.711 9 7.494 9 8v1h6V8c0-.507-.16-1.289-.611-1.916C13.974 5.508 13.274 5 12 5c-1.274 0-1.974.508-2.388 1.084zM17 9V8c0-.827-.24-2.044-.988-3.084C15.226 3.825 13.926 3 12 3c-1.926 0-3.226.825-4.012 1.916C7.24 5.956 7 7.173 7 8v1H3a1 1 0 0 0 0 2h.095l.91 9.1A1 1 0 0 0 5 21h14a1 1 0 0 0 .995-.9l.91-9.1H21a1 1 0 1 0 0-2h-4zm-8 5a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2z" clip-rule="evenodd"/></svg>' : 
                '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h1m17 0h-1m0 0-1 10H5L4 10m16 0h-4M4 10h4m4 4v2m3-2v2m-6-2v2m-1-6h8m-8 0V8c0-1.333.8-4 4-4s4 2.667 4 4v2"/></svg>';
        });
        
        updateCartCount();
        console.log("Корзина успешно обновлена");
    } catch (error) {
        console.error("Ошибка переключения корзины:", error);
    }
}

function addToCart(productId) {
    try {
        console.log("Добавление в корзину: ", productId);
        
        // Получаем текущее состояние корзины
        const cart = JSON.parse(localStorage.getItem('cart')) || {};
        
        // Обновляем корзину только с ID и количеством
        cart[productId] = (cart[productId] || 0) + 1;
        
        // Сохраняем обновленную корзину
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Сохраняем allProducts в localStorage для cart.html
        const productsForStorage = {};
        rawData.products.forEach(product => {
            productsForStorage[product.id] = product;
        });
        localStorage.setItem('allProducts', JSON.stringify(productsForStorage));
        
        // Обновляем элементы интерфейса
        updateCartCount();
        
        // Показываем уведомление
        alert('Товар добавлен в корзину!');
        console.log("Корзина успешно обновлена");
    } catch (error) {
        console.error("Ошибка добавления в корзину:", error);
    }
}

function updateCartCount() {
    const cartCountElements = document.querySelectorAll('.cart-count');
    if (!cartCountElements.length) return;
    
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    let totalItems = 0;
    
    for (const productId in cart) {
        totalItems += cart[productId];
    }
    
    cartCountElements.forEach(element => {
        element.textContent = totalItems;
        element.style.display = totalItems > 0 ? 'inline-block' : 'none';
    });
}

function toggleFavorite(button) {
    const productId = button.dataset.id;
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    const productIndex = favorites.findIndex(prod => prod.id === productId);
    
    if (productIndex === -1) {
        const product = rawData.products.find(p => p.id === productId);
        if (product) {
            // Сохраняем полные данные о продукте
            favorites.push({
                id: product.id,
                title: product.title,
                price: product.price,
                images: product.images,
                inStock: product.inStock,
                category: product.category,
                series: product.series
            });
        }
    } else {
        favorites.splice(productIndex, 1);
    }
    
    localStorage.setItem('favorites', JSON.stringify(favorites));
    button.classList.toggle('active');
}

// Также обновляем функцию модального окна, чтобы использовать toggleCart вместо addToCart
function openModal(productId) {
    if (event) {
        event.stopPropagation();
    }
    
    const product = rawData.products.find(p => p.id === productId);
    if (!product) return;

    const modal = document.getElementById('modal');
    if (!modal) {
        console.error('Модальное окно не найдено');
        return;
    }

    document.getElementById('modal-title').textContent = product.title;
    document.getElementById('modal-image').src = product.images[0]?.thumbnail || 'placeholder.jpg';
    document.getElementById('modal-price').textContent = `Цена: ${product.price} руб.`;
    
    const featuresElement = document.getElementById('modal-features');
    featuresElement.innerHTML = '';
    
    // Добавляем серию как первую характеристику
    const seriesToShow = document.createElement('li');
    seriesToShow.textContent = `Серия: ${product.series}`;
    featuresElement.appendChild(seriesToShow);
    
    product.features.forEach(feature => {
        const li = document.createElement('li');
        li.textContent = feature;
        featuresElement.appendChild(li);
    });
    
    const addToFavoritesBtn = document.getElementById('add-to-favorites');
    addToFavoritesBtn.dataset.id = product.id;
    
    const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
    const isFavorite = favorites.some(fav => fav.id === product.id);
    addToFavoritesBtn.classList.toggle('active', isFavorite);
    
    const sizeOptions = document.getElementById('size-options');
    sizeOptions.innerHTML = '';
    if (product.sizes && product.sizes.length > 0) {
        product.sizes.forEach(size => {
            const sizeButton = document.createElement('button');
            sizeButton.className = 'size-button';
            sizeButton.textContent = size;
            sizeOptions.appendChild(sizeButton);
        });
    } else {
        sizeOptions.innerHTML = '<p>Нет доступных размеров</p>';
    }
    
    const colorOptions = document.getElementById('color-options');
    colorOptions.innerHTML = '';
    if (product.colors && product.colors.length > 0) {
        product.colors.forEach(color => {
            const colorBtn = document.createElement('div');
            colorBtn.className = 'color-option';
            colorBtn.style.backgroundColor = color;
            colorBtn.title = color;
            colorOptions.appendChild(colorBtn);
        });
    } else {
        colorOptions.innerHTML = '<p>Нет доступных цветов</p>';
    }
    
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    const addToCartButton = document.getElementById('add-to-cart');
    
    if (addToCartButton) {
        // Устанавливаем класс в зависимости от состояния корзины
        addToCartButton.classList.toggle('in-cart', cart[product.id] > 0);
        
        // Обновляем текст кнопки
      // Обновляем текст и иконку кнопки
addToCartButton.innerHTML = cart[product.id] ? 
    '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path fill="#000000" fill-rule="evenodd" d="M9.612 6.084C9.16 6.711 9 7.494 9 8v1h6V8c0-.507-.16-1.289-.611-1.916C13.974 5.508 13.274 5 12 5c-1.274 0-1.974.508-2.388 1.084zM17 9V8c0-.827-.24-2.044-.988-3.084C15.226 3.825 13.926 3 12 3c-1.926 0-3.226.825-4.012 1.916C7.24 5.956 7 7.173 7 8v1H3a1 1 0 0 0 0 2h.095l.91 9.1A1 1 0 0 0 5 21h14a1 1 0 0 0 .995-.9l.91-9.1H21a1 1 0 1 0 0-2h-4zm-8 5a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2z" clip-rule="evenodd"/></svg>' : 
    '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h1m17 0h-1m0 0-1 10H5L4 10m16 0h-4M4 10h4m4 4v2m3-2v2m-6-2v2m-1-6h8m-8 0V8c0-1.333.8-4 4-4s4 2.667 4 4v2"/></svg>';

// В обработчике клика тоже используем innerHTML вместо textContent
addToCartButton.onclick = function() {
    toggleCart(product.id);
    
    // Обновляем состояние кнопки после клика
    const updatedCart = JSON.parse(localStorage.getItem('cart')) || {};
    addToCartButton.classList.toggle('in-cart', updatedCart[product.id] > 0);
    addToCartButton.innerHTML = updatedCart[product.id] ? 
        '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path fill="#000000" fill-rule="evenodd" d="M9.612 6.084C9.16 6.711 9 7.494 9 8v1h6V8c0-.507-.16-1.289-.611-1.916C13.974 5.508 13.274 5 12 5c-1.274 0-1.974.508-2.388 1.084zM17 9V8c0-.827-.24-2.044-.988-3.084C15.226 3.825 13.926 3 12 3c-1.926 0-3.226.825-4.012 1.916C7.24 5.956 7 7.173 7 8v1H3a1 1 0 0 0 0 2h.095l.91 9.1A1 1 0 0 0 5 21h14a1 1 0 0 0 .995-.9l.91-9.1H21a1 1 0 1 0 0-2h-4zm-8 5a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2zm4 0a1 1 0 1 0-2 0v2a1 1 0 1 0 2 0v-2z" clip-rule="evenodd"/></svg>' : 
        '<svg width="32px" height="32px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"><path stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h1m17 0h-1m0 0-1 10H5L4 10m16 0h-4M4 10h4m4 4v2m3-2v2m-6-2v2m-1-6h8m-8 0V8c0-1.333.8-4 4-4s4 2.667 4 4v2"/></svg>';
};
     
            // Закрываем модальное окно после добавления (опционально)
            // closeModal();
        };
    //}
    
    // Добавляем обработчик для сердечка в модальном окне
    const favoriteButton = document.getElementById('add-to-favorites');
    if (favoriteButton) {
        favoriteButton.onclick = function(e) {
            e.stopPropagation();
            toggleFavorite(this);
        };
    }
    
    modal.style.display = 'block';
}

function closeModal() {
    const modal = document.getElementById('modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function addToCartFromModal() {
    const addToFavoritesBtn = document.getElementById('add-to-favorites');
    if (!addToFavoritesBtn) {
        console.error('Кнопка добавления в избранное не найдена');
        return;
    }
    
    const productId = addToFavoritesBtn.dataset.id;
    toggleCart(productId);
    
    // Update the "Add to cart" button text in the modal
    const addToCartButton = document.getElementById('add-to-cart');
    if (addToCartButton) {
        addToCartButton.textContent = 'В корзине';
    }
}

function renderPagination() {
    const pagination = document.getElementById('pagination');
    if (!pagination) {
        console.error('Элемент pagination не найден');
        return;
    }
    
    const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);

    pagination.innerHTML = `
        <button onclick="changePage('first')" ${currentPage === 1 ? 'disabled' : ''}><<</button>
        <button onclick="changePage('prev')" ${currentPage === 1 ? 'disabled' : ''}><</button>
        <span id="page-info">Страница ${currentPage} из ${totalPages || 1}</span>
        <button onclick="changePage('next')" ${currentPage >= totalPages || totalPages === 0 ? 'disabled' : ''}>></button>
        <button onclick="changePage('last')" ${currentPage >= totalPages || totalPages === 0 ? 'disabled' : ''}>>></button>
    `;
}
// Модифицируем changePage для сохранения состояния после изменения
function changePage(direction) {
    const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
    if (totalPages === 0) return;

    switch (direction) {
        case 'first':
            currentPage = 1;
            break;
        case 'prev':
            if (currentPage > 1) currentPage--;
            break;
        case 'next':
            if (currentPage < totalPages) currentPage++;
            break;
        case 'last':
            currentPage = totalPages;
            break;
    }

    renderProducts();
    renderPagination();
    
    // Сохраняем состояние после изменения страницы
    saveFilterSelections();
    
    const productList = document.getElementById('product-list');
    if (productList) {
        productList.scrollIntoView({ behavior: 'smooth' });
    }
}

function applyFilters() {
    filterProducts();
}

// Сохраняем состояние фильтров в localStorage
function saveFilterState() {
    const filterState = {
        search: document.getElementById('search').value,
        minPrice: minPriceFilter,
        maxPrice: maxPriceFilter,
        brand: document.getElementById('brand').value,
        category: document.getElementById('category').value,
        series: document.getElementById('series').value,
        currentPage: currentPage
    };
    
    localStorage.setItem('filterState', JSON.stringify(filterState));
}

// Восстанавливаем состояние фильтров из localStorage
function restoreFilterState() {
    const savedState = localStorage.getItem('filterState');
    if (!savedState) return;
    
    try {
        const filterState = JSON.parse(savedState);
        
        // Восстанавливаем текстовый поиск и диапазон цен
        document.getElementById('search').value = filterState.search || '';
        minPriceFilter = filterState.minPrice || 0;
        maxPriceFilter = filterState.maxPrice || 80000;
        
        $("#slider").slider("values", [minPriceFilter, maxPriceFilter]);
        $("#minPrice").val(minPriceFilter);
        $("#maxPrice").val(maxPriceFilter);
        
        // Восстанавливаем выбор бренда
        const brandSelect = document.getElementById('brand');
        if (filterState.brand && brandSelect) {
            brandSelect.value = filterState.brand;
            
            // Запускаем обновление опций категорий
            updateCategoryOptions();
            
            // Восстанавливаем выбор категории
            const categorySelect = document.getElementById('category');
            if (filterState.category && categorySelect) {
                categorySelect.value = filterState.category;
                
                // Запускаем обновление опций серий
                updateSeriesOptions();
                
                // Восстанавливаем выбор серии
                const seriesSelect = document.getElementById('series');
                if (filterState.series && seriesSelect) {
                    seriesSelect.value = filterState.series;
                }
            }
        }
        
        // Применяем восстановленные фильтры
        filterProducts();
        
        // Восстанавливаем номер страницы
        if (filterState.currentPage) {
            currentPage = parseInt(filterState.currentPage);
            renderProducts();
            renderPagination();
        }
    } catch (error) {
        console.error('Ошибка восстановления состояния фильтров:', error);
        // Если произошла ошибка, сбрасываем фильтры
        resetFilters();
    }
}

// Модифицируем resetFilters() для очистки сохраненного состояния
function resetFilters() {
    const searchElem = document.getElementById('search');
    const brandElem = document.getElementById('brand');
    const categoryElem = document.getElementById('category');
    const seriesElem = document.getElementById('series');
    
    if (searchElem) searchElem.value = '';
    
    // Сбрасываем цены
    minPriceFilter = 0;
    maxPriceFilter = 80000;
    $("#slider").slider("values", [minPriceFilter, maxPriceFilter]);
    $("#minPrice").val(minPriceFilter);
    $("#maxPrice").val(maxPriceFilter);
    
    if (brandElem) brandElem.value = '';
    if (categoryElem) {
        categoryElem.value = '';
        categoryElem.disabled = true;
    }
    if (seriesElem) {
        seriesElem.value = '';
        seriesElem.disabled = true;
    }
    
    localStorage.removeItem('filterState');
    filterProducts();
}

function setupBurgerMenu() {
    const burgerIcon = document.querySelector('.burger-menu-icon');
    const mobileNav = document.querySelector('.mobile-nav');
    const closeBtn = document.querySelector('.mobile-nav-close');
    const overlay = document.querySelector('.mobile-nav-overlay');
    
    if (!burgerIcon || !mobileNav || !closeBtn || !overlay) {
        console.error('Не найдены элементы бургер-меню');
        return;
    }
    
    burgerIcon.addEventListener('click', () => {
        mobileNav.classList.add('active');
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });
    
    closeBtn.addEventListener('click', () => {
        mobileNav.classList.remove('active');
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    });
    
    overlay.addEventListener('click', () => {
        mobileNav.classList.remove('active');
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    });
}
    </script>
</body>
</html>