let products = [];
let categories = [];
let currentCategory = null;
let currentPage = 1;
const productsPerPage = 6;

// Загрузка данных из JSON файла
async function loadData() {
    try {
        const response = await fetch('cart.json');
        const data = await response.json();
        products = data.products;
        categories = data.categories;
        renderCategories();
        renderProducts();
    } catch (error) {
        console.error('Ошибка загрузки данных:', error);
    }
}

// Отрисовка категорий
function renderCategories() {
    const categoriesList = document.getElementById('categories');
    categoriesList.innerHTML = `
        <li class="${!currentCategory ? 'active' : ''}" 
            onclick="selectCategory(null)">
            Все категории
        </li>
        ${categories.map(category => `
            <li class="${currentCategory === category ? 'active' : ''}"
                onclick="selectCategory('${category}')">
                ${category}
            </li>
        `).join('')}
    `;
}

// Выбор категории
function selectCategory(category) {
    currentCategory = category;
    currentPage = 1;
    renderCategories();
    renderProducts();
}

// Фильтрация товаров по текущей категории
function getFilteredProducts() {
    if (!currentCategory) return products;
    return products.filter(product => product.category === currentCategory);
}

// Получение товаров для текущей страницы
function getCurrentPageProducts() {
    const filtered = getFilteredProducts();
    const start = (currentPage - 1) * productsPerPage;
    return filtered.slice(start, start + productsPerPage);
}

// Отрисовка товаров
function renderProducts() {
    const productsContainer = document.getElementById('products');
    const currentProducts = getCurrentPageProducts();
    
    productsContainer.innerHTML = currentProducts.map(product => `
        <div class="product-card">
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>${product.description}</p>
            <p class="category">${product.category}</p>
        </div>
    `).join('');

    updatePagination();
}

// Обновление пагинации
function updatePagination() {
    const filtered = getFilteredProducts();
    const totalPages = Math.ceil(filtered.length / productsPerPage);
    
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('firstPage').disabled = currentPage === 1;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
    document.getElementById('lastPage').disabled = currentPage === totalPages;
}

// Обработчики кнопок пагинации
document.getElementById('firstPage').addEventListener('click', () => {
    currentPage = 1;
    renderProducts();
});

document.getElementById('prevPage').addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderProducts();
    }
});

document.getElementById('nextPage').addEventListener('click', () => {
    const filtered = getFilteredProducts();
    const totalPages = Math.ceil(filtered.length / productsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderProducts();
    }
});

document.getElementById('lastPage').addEventListener('click', () => {
    const filtered = getFilteredProducts();
    currentPage = Math.ceil(filtered.length / productsPerPage);
    renderProducts();
});

// Загрузка данных при запуске
document.addEventListener('DOMContentLoaded', loadData);