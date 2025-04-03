<?php include 'header.php'; ?>
<link rel="stylesheet" href="style.css">
<style>
/* Services Page Container */
.services-container {
 /*   padding: 20px;*/
    min-height: 60vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}


.title-section {
    text-align: center;
    margin-bottom: 20px;
}

.main-title {
    color: #2196F3;
    font-size: 32px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 15px;
}

.title-underline {
    height: 3px;
    width: 100px;
    background: #2196F3;
    margin: 0 auto;
}

.description-section p {
    text-align: center;
    color: #333;
    font-size: 16px;
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
}

/* Services Grid */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.service-card {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 5px 5px 10px #d1d1d1, -5px -5px 10px #ffffff;
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 7px 7px 14px #d1d1d1, -7px -7px 14px #ffffff;
}

.service-card h3 {
    color: #2196F3;
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.service-card ul {
    list-style: none;
    padding: 0;
}

.service-card ul li {
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    color: #444;
    transition: color 0.3s ease;
}

.service-card ul li:last-child {
    border-bottom: none;
}

.service-card ul li:hover {
    color: #2196F3;
}

/* Responsive Design */
@media (max-width: 768px) {
    .services-header {
        padding: 20px;
    }

    .main-title {
        font-size: 24px;
    }

    .services-grid {
        grid-template-columns: 1fr;
        padding: 10px;
    }

    .service-card {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .services-header {
        padding: 15px;
    }

    .description-section p {
        font-size: 14px;
    }

    .service-card h3 {
        font-size: 20px;
    }
}
</style>
<main class="services-container">
    <section class="services-header">
        <div class="header-content">
            <div class="title-section">
                <h2 class="main-title">Предоставляемые услуги</h2>
                <div class="title-underline"></div>
            </div>
            <div class="description-section">
                <p>Мы команда профессионалов, занимающихся установкой межкомнатных дверей более 20 лет.<br> Наша цель - обеспечить клиентов качественными услугами и удовлетворить их потребности.</p>
            </div>
        </div>
    </section>

    <section class="services-grid">
        <div class="service-card">
            <h3>Установка дверей</h3>
            <ul>
                <li>входных</li>
                <li>металлических</li>
                <li>межкомнатных</li>
                <li>раздвижных</li>
                <li>складных дверей (гармошка, книжка)</li>
            </ul>
        </div>

        <div class="service-card">
            <h3>Ремонт</h3>
            <ul>
                <li>Установка доводчиков</li>
                <li>Отделка откосов</li>
                <li>Обивка дверей дермантином</li>
                <li>Установка МДФ панелей</li>
                <li>Утепление дверей</li>
                <li>Демонтаж дверей</li>
            </ul>
        </div>

        <div class="service-card">
            <h3>Работы по замкам</h3>
            <ul>
                <li>Замена</li>
                <li>Установка</li>
                <li>Врезка</li>
                <li>Вскрытие</li>
                <li>Ремонт</li>
            </ul>
        </div>
    </section>
</main>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Burger menu functionality
            const burgerMenu = document.querySelector('.burger-menu');
            const sideNav = document.querySelector('.side-nav');
            
            burgerMenu.addEventListener('click', function() {
                sideNav.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!sideNav.contains(event.target) && !burgerMenu.contains(event.target)) {
                    sideNav.classList.remove('active');
                }
            });

            // Button control functionality
            const controlButtons = document.querySelectorAll('.control-panel .control-button');
            const buttonGroups = document.querySelectorAll('.button-group');

            function clearActiveStates() {
                controlButtons.forEach(btn => btn.classList.remove('active'));
                buttonGroups.forEach(group => group.classList.remove('active'));
            }

            controlButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const groupName = this.dataset.group;
                    
                    if (this.classList.contains('active')) {
                        return;
                    }

                    clearActiveStates();
                    
                    this.classList.add('active');
                    loadButtonGroup(groupName);
                });
            });

            // Function to load and display buttons for a group
            function loadButtonGroup(groupName) {
                fetch('1.json')
                    .then(response => response.json())
                    .then(data => {
                        const buttonContainer = document.getElementById('buttonContainer');
                        buttonContainer.innerHTML = ''; // Clear existing content
                        
                        // Create button group div
                        const buttonGroup = document.createElement('div');
                        buttonGroup.className = 'button-group active';
                        buttonGroup.setAttribute('data-group', groupName);
                        
                        // Add buttons from JSON data
                        if (data[groupName]) {
                            data[groupName].forEach(button => {
                                buttonGroup.innerHTML += `
                                    <button class="control-button">
                                        <a href="${button.url}" target="_blank">${button.text}</a>
                                    </button>
                                `;
                            });
                        }
                        
                        buttonContainer.appendChild(buttonGroup);
                    })
                    .catch(error => console.error('Error loading buttons:', error));
            }

            // Load initial button group (optional)
            loadButtonGroup('a1');
        });
</script>
<?php include 'footer.php'; ?>