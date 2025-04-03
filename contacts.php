<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты</title>
    <link rel="stylesheet" href="css/contacts.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    
    <main>
        <section class="contacts-section">
            <div class="container">
                <h1>Контакты</h1>
                
                <div class="contacts-content">
                    <div class="contacts-info">
                        <h2>Наши контактные данные</h2>
                        
                        <?php
                        // Массив с контактными данными
                        $contacts = [
                            'address' => 'г. Москва, ул. Примерная, д. 123',
                            'phone' => '+7 (495) 123-45-67',
                            'email' => 'info@example.com',
                            'hours' => 'Пн-Пт: 9:00-18:00, Сб-Вс: 10:00-17:00'
                        ];
                        
                        // Массив офисов компании
                        $offices = [
                            [
                                'city' => 'Москва',
                                'address' => 'ул. Примерная, д. 123',
                                'phone' => '+7 (495) 123-45-67',
                                'email' => 'moscow@example.com',
                                'schedule' => 'Пн-Пт: 9:00-18:00, Сб-Вс: 10:00-17:00'
                            ],
                            [
                                'city' => 'Санкт-Петербург',
                                'address' => 'ул. Образцовая, д. 456',
                                'phone' => '+7 (812) 765-43-21',
                                'email' => 'spb@example.com',
                                'schedule' => 'Пн-Пт: 9:00-18:00, Сб-Вс: 10:00-17:00'
                            ],
                            [
                                'city' => 'Екатеринбург',
                                'address' => 'ул. Тестовая, д. 789',
                                'phone' => '+7 (343) 123-45-67',
                                'email' => 'ekb@example.com',
                                'schedule' => 'Пн-Пт: 9:00-18:00, Сб: 10:00-15:00, Вс: выходной'
                            ]
                        ];
                        ?>
                        
                        <div class="main-contacts">
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="contact-text">
                                    <h3>Адрес</h3>
                                    <p><?php echo $contacts['address']; ?></p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="contact-text">
                                    <h3>Телефон</h3>
                                    <p><a href="tel:<?php echo str_replace([' ', '(', ')', '-'], '', $contacts['phone']); ?>"><?php echo $contacts['phone']; ?></a></p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                                <div class="contact-text">
                                    <h3>Email</h3>
                                    <p><a href="mailto:<?php echo $contacts['email']; ?>"><?php echo $contacts['email']; ?></a></p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon"><i class="fas fa-clock"></i></div>
                                <div class="contact-text">
                                    <h3>Режим работы</h3>
                                    <p><?php echo $contacts['hours']; ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="offices">
                            <h2>Наши офисы</h2>
                            <div class="offices-list">
                                <?php
                                // Вывод информации об офисах
                                foreach ($offices as $office) {
                                    echo '<div class="office-item">';
                                    echo '<h3>' . $office['city'] . '</h3>';
                                    echo '<p><strong>Адрес:</strong> ' . $office['address'] . '</p>';
                                    echo '<p><strong>Телефон:</strong> <a href="tel:' . str_replace([' ', '(', ')', '-'], '', $office['phone']) . '">' . $office['phone'] . '</a></p>';
                                    echo '<p><strong>Email:</strong> <a href="mailto:' . $office['email'] . '">' . $office['email'] . '</a></p>';
                                    echo '<p><strong>Режим работы:</strong> ' . $office['schedule'] . '</p>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contacts-map">
                        <h2>Мы на карте</h2>
                        <div class="map-container">
                            <?php
                            // Здесь можно добавить вставку карты (Google Maps, Яндекс.Карты и т.д.)
                            // Пример для Яндекс.Карт:
                            /*
                            echo '<script src="https://api-maps.yandex.ru/2.1/?apikey=YOUR_API_KEY&lang=ru_RU" type="text/javascript"></script>';
                            echo '<div id="map" style="width: 100%; height: 400px;"></div>';
                            echo '<script>
                                ymaps.ready(init);
                                function init() {
                                    var myMap = new ymaps.Map("map", {
                                        center: [55.76, 37.64],
                                        zoom: 10
                                    });
                                    
                                    var myPlacemark = new ymaps.Placemark([55.76, 37.64], {
                                        hintContent: "Наш офис",
                                        balloonContent: "г. Москва, ул. Примерная, д. 123"
                                    });
                                    
                                    myMap.geoObjects.add(myPlacemark);
                                }
                            </script>';
                            */
                            
                            // Временная замена карты изображением
                            echo '<img src="images/map.jpg" alt="Карта с расположением офиса">';
                            ?>
                        </div>
                    </div>
                    
                    <div class="contacts-form">
                        <h2>Напишите нам</h2>
                        
                        <?php
                        // Обработка формы
                        $formSubmitted = false;
                        $formErrors = [];
                        
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
                            // Проверка полей формы
                            if (empty($_POST['name'])) {
                                $formErrors['name'] = 'Пожалуйста, укажите ваше имя';
                            }
                            
                            if (empty($_POST['email'])) {
                                $formErrors['email'] = 'Пожалуйста, укажите ваш email';
                            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                                $formErrors['email'] = 'Пожалуйста, укажите корректный email';
                            }
                            
                            if (empty($_POST['message'])) {
                                $formErrors['message'] = 'Пожалуйста, напишите сообщение';
                            }
                            
                            // Если ошибок нет, обрабатываем форму
                            if (empty($formErrors)) {
                                // Здесь можно добавить код для отправки сообщения, например, через mail() или сохранения в БД
                                $formSubmitted = true;
                                
                                // Пример отправки письма
                                /*
                                $to = $contacts['email'];
                                $subject = 'Сообщение с сайта от ' . $_POST['name'];
                                $message = "Имя: " . $_POST['name'] . "\n";
                                $message .= "Email: " . $_POST['email'] . "\n";
                                if (!empty($_POST['phone'])) {
                                    $message .= "Телефон: " . $_POST['phone'] . "\n";
                                }
                                $message .= "Сообщение: " . $_POST['message'];
                                $headers = 'From: ' . $_POST['email'] . "\r\n" .
                                    'Reply-To: ' . $_POST['email'] . "\r\n" .
                                    'X-Mailer: PHP/' . phpversion();
                                
                                mail($to, $subject, $message, $headers);
                                */
                            }
                        }
                        
                        // Если форма успешно отправлена, показываем сообщение об успехе
                        if ($formSubmitted) {
                            echo '<div class="success-message">';
                            echo '<p>Спасибо за ваше сообщение! Мы свяжемся с вами в ближайшее время.</p>';
                            echo '</div>';
                        } else {
                            // Иначе показываем форму
                        ?>
                        
                        <form method="post" action="" class="contact-form">
                            <div class="form-group <?php echo isset($formErrors['name']) ? 'has-error' : ''; ?>">
                                <label for="name">Ваше имя *</label>
                                <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                <?php if (isset($formErrors['name'])) echo '<span class="error-message">' . $formErrors['name'] . '</span>'; ?>
                            </div>
                            
                            <div class="form-group <?php echo isset($formErrors['email']) ? 'has-error' : ''; ?>">
                                <label for="email">Ваш email *</label>
                                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                <?php if (isset($formErrors['email'])) echo '<span class="error-message">' . $formErrors['email'] . '</span>'; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Ваш телефон</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                            
                            <div class="form-group <?php echo isset($formErrors['message']) ? 'has-error' : ''; ?>">
                                <label for="message">Сообщение *</label>
                                <textarea id="message" name="message" rows="5"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                                <?php if (isset($formErrors['message'])) echo '<span class="error-message">' . $formErrors['message'] . '</span>'; ?>
                            </div>
                            
                            <div class="form-group form-agreement">
                                <input type="checkbox" id="agreement" name="agreement" required <?php echo isset($_POST['agreement']) ? 'checked' : ''; ?>>
                                <label for="agreement">Я согласен на обработку персональных данных</label>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" name="contact_submit" class="submit-button">Отправить сообщение</button>
                            </div>
                        </form>
                        
                        <?php } ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
    
    <!-- <script src="scripts.js"></script> -->
</body>
</html>