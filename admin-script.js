let products = [];
let currentId = 0;

// Загрузка существующих товаров
async function loadProducts() {
    try {
        const response = await fetch('cart.json');
        const data = await response.json();
        products = data.products;
        currentId = Math.max(...products.map(p => p.id));
        renderProducts();
    } catch (error) {
        showMessage('Ошибка загрузки данных: ' + error.message, false);
    }
}

// Отображение списка товаров
function renderProducts() {
    const productsList = document.getElementById('productsList');
    productsList.innerHTML = products
        .map(product => `
            <div class="product-item">
                <div>
                    <strong>${product.name}</strong> - 
                    ${product.category}
                </div>
                <button 
                    onclick="deleteProduct(${product.id})" 
                    class="delete-btn">
                    Удалить
                </button>
            </div>
        `)
        .join('');
}

// Добавление нового товара
async function addProduct(event) {
    event.preventDefault();

    const newProduct = {
        id: currentId + 1,
        name: document.getElementById('productName').value,
        category: document.getElementById('productCategory').value,
        description: document.getElementById('productDescription').value,
        image: document.getElementById('productImage').value
    };

    products.push(newProduct);
    currentId++;

    try {
        await saveProducts();
        renderProducts();
        event.target.reset();
        showMessage('Товар успешно добавлен', true);
    } catch (error) {
        showMessage('Ошибка при сохранении: ' + error.message, false);
    }
}

// Удаление товара
async function deleteProduct(id) {
    if (confirm('Вы уверены, что хотите удалить этот товар?')) {
        products = products.filter(product => product.id !== id);
        try {
            await saveProducts();
            renderProducts();
            showMessage('Товар успешно удален', true);
        } catch (error) {
            showMessage('Ошибка при удалении: ' + error.message, false);
        }
    }
}

// Сохранение изменений в файл
async function saveProducts() {
    try {
        const response = await fetch('save-products.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                categories: [
                    "Смартфоны",
                    "Ноутбуки",
                    "Планшеты",
                    "Наушники",
                    "Аксессуары"
                ],
                products: products
            })
        });

        if (!response.ok) {
            throw new Error('Ошибка сохранения');
        }
    } catch (error) {
        throw new Error('Ошибка сети: ' + error.message);
    }
}

// Отображение сообщений
function showMessage(text, isSuccess) {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = text;
    messageDiv.className = `message ${isSuccess ? 'success' : 'error'}`;
    setTimeout(() => {
        messageDiv.textContent = '';
        messageDiv.className = 'message';
    }, 3000);
}

// Инициализация
document.getElementById('productForm').addEventListener('submit', addProduct);
document.addEventListener('DOMContentLoaded', loadProducts);