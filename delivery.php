<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доставка</title>
    <link rel="stylesheet" href="css/delivery.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    
    <main>
        <section class="delivery-section">
            <div class="container">
                <h1>Доставка</h1>
                
                <div class="delivery-info">
                    <h2>Способы доставки</h2>
                    
                    <?php
                    // Массив с информацией о способах доставки
                    $deliveryMethods = [
                        [
                            'title' => 'Курьерская доставка',
                            'icon' => 'fas fa-truck',
                            'description' => 'Доставка до двери курьером в удобное для вас время.',
                            'price' => 'от 300 руб.',
                            'time' => '1-2 дня'
                        ],
                        [
                            'title' => 'Самовывоз',
                            'icon' => 'fas fa-store',
                            'description' => 'Вы можете забрать заказ самостоятельно в одном из наших пунктов выдачи.',
                            'price' => 'Бесплатно',
                            'time' => 'В день заказа'
                        ],
                        [
                            'title' => 'Почта России',
                            'icon' => 'fas fa-mail-bulk',
                            'description' => 'Доставка в любую точку России.',
                            'price' => 'от 250 руб.',
                            'time' => '3-10 дней'
                        ],
                        [
                            'title' => 'Транспортная компания',
                            'icon' => 'fas fa-shipping-fast',
                            'description' => 'Доставка крупногабаритных заказов транспортными компаниями.',
                            'price' => 'Рассчитывается индивидуально',
                            'time' => '3-7 дней'
                        ]
                    ];
                    
                    // Вывод информации о способах доставки
                    echo '<div class="delivery-methods">';
                    foreach ($deliveryMethods as $method) {
                        echo '<div class="delivery-method">';
                        echo '<div class="method-icon"><i class="' . $method['icon'] . '"></i></div>';
                        echo '<div class="method-info">';
                        echo '<h3>' . $method['title'] . '</h3>';
                        echo '<p>' . $method['description'] . '</p>';
                        echo '<div class="method-details">';
                        echo '<span class="price">Стоимость: ' . $method['price'] . '</span>';
                        echo '<span class="time">Срок: ' . $method['time'] . '</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                    ?>
                    
                    <div class="delivery-map">
                        <h2>Зоны доставки</h2>
                        <p>Мы осуществляем доставку по всей России. Сроки и стоимость зависят от региона.</p>
                        
                        <?php
                        // Получение текущего региона пользователя (пример)
                        $userRegion = isset($_COOKIE['user_region']) ? $_COOKIE['user_region'] : 'Москва';
                        
                        // Вывод информации о доставке в регион пользователя
                        echo '<div class="user-region-info">';
                        echo '<p>Информация о доставке в ваш регион (' . $userRegion . '):</p>';
                        
                        // Здесь можно добавить логику для определения условий доставки в конкретный регион
                        switch ($userRegion) {
                            case 'Москва':
                                echo '<ul>';
                                echo '<li>Курьерская доставка: от 300 руб., 1-2 дня</li>';
                                echo '<li>Самовывоз: бесплатно, в день заказа</li>';
                                echo '</ul>';
                                break;
                            case 'Санкт-Петербург':
                                echo '<ul>';
                                echo '<li>Курьерская доставка: от 350 руб., 1-2 дня</li>';
                                echo '<li>Самовывоз: бесплатно, в день заказа</li>';
                                echo '</ul>';
                                break;
                            default:
                                echo '<ul>';
                                echo '<li>Почта России: от 250 руб., 3-10 дней</li>';
                                echo '<li>Транспортная компания: индивидуальный расчет, 3-7 дней</li>';
                                echo '</ul>';
                        }
                        
                        echo '</div>';
                        ?>
                        
                        <div class="delivery-map-image">
                            <img src="images/delivery-map.jpg" alt="Карта зон доставки">
                        </div>
                    </div>
                    
                    <div class="delivery-faq">
                        <h2>Часто задаваемые вопросы о доставке</h2>
                        
                        <?php
                        // Массив с вопросами и ответами
                        $faq = [
                            [
                                'question' => 'Как отследить заказ?',
                                'answer' => 'После оформления заказа вы получите трек-номер, по которому можно отслеживать статус доставки в личном кабинете или на сайте транспортной компании.'
                            ],
                            [
                                'question' => 'Что делать, если товар не подошел?',
                                'answer' => 'Вы можете вернуть товар в течение 14 дней с момента получения. Подробнее о возврате читайте в разделе "Возврат и обмен".'
                            ],
                            [
                                'question' => 'Как изменить адрес доставки?',
                                'answer' => 'Вы можете изменить адрес доставки, связавшись с нашим менеджером по телефону или через личный кабинет, если заказ еще не был передан в службу доставки.'
                            ]
                        ];
                        
                        // Вывод FAQ
                        echo '<div class="faq-list">';
                        foreach ($faq as $index => $item) {
                            echo '<div class="faq-item">';
                            echo '<div class="faq-question" onclick="toggleFaq(' . $index . ')">';
                            echo '<h3>' . $item['question'] . '</h3>';
                            echo '<span class="toggle-icon">+</span>';
                            echo '</div>';
                            echo '<div class="faq-answer" id="faq-' . $index . '">';
                            echo '<p>' . $item['answer'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
    
    <!-- <script src="scripts.js"></script> -->
    <script>
        function toggleFaq(index) {
            const answer = document.getElementById('faq-' + index);
            const allAnswers = document.querySelectorAll('.faq-answer');
            
            // Закрываем все ответы
            allAnswers.forEach(item => {
                if (item.id !== 'faq-' + index) {
                    item.classList.remove('active');
                }
            });
            
            // Открываем или закрываем выбранный ответ
            answer.classList.toggle('active');
        }
    </script>
</body>
</html>