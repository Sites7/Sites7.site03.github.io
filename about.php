<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О компании</title>
    <link rel="stylesheet" href="css/about.css">
</head>
<body>
    <header>
        <?php include 'header.php'; ?>
    </header>
    
    <main>
        <section class="about-section">
            <div class="container">
                <h1>О компании</h1>
                
                <div class="about-content">
                    <div class="about-image">
                        <img src="images/company.jpg" alt="Наша компания">
                    </div>
                    
                    <div class="about-text">
                        <h2>Наша история</h2>
                        <p>Компания [Название компании] была основана в [год основания] году. За прошедшие годы мы выросли из небольшого предприятия в одного из лидеров рынка.</p>
                        
                        <h2>Наша миссия</h2>
                        <p>Мы стремимся предоставлять нашим клиентам товары и услуги высочайшего качества, постоянно совершенствуя наши процессы и следуя последним тенденциям в отрасли.</p>
                        
                        <h2>Наши ценности</h2>
                        <ul>
                            <li>Качество продукции</li>
                            <li>Честность и прозрачность</li>
                            <li>Клиентоориентированность</li>
                            <li>Инновации и развитие</li>
                        </ul>
                    </div>
                </div>
                
                <div class="team-section">
                    <h2>Наша команда</h2>
                    <div class="team-members">
                        <?php
                        // Массив с информацией о сотрудниках
                        $team = [
                            [
                                'name' => 'Иванов Иван',
                                'position' => 'Генеральный директор',
                                'photo' => 'images/team/ivanov.jpg',
                                'description' => 'Основатель компании с опытом работы более 15 лет.'
                            ],
                            [
                                'name' => 'Петрова Елена',
                                'position' => 'Коммерческий директор',
                                'photo' => 'images/team/petrova.jpg',
                                'description' => 'Специалист по развитию бизнеса и маркетингу.'
                            ],
                            [
                                'name' => 'Сидоров Алексей',
                                'position' => 'Технический директор',
                                'photo' => 'images/team/sidorov.jpg',
                                'description' => 'Эксперт в области технологий и инноваций.'
                            ]
                        ];
                        
                        // Вывод информации о сотрудниках
                        foreach ($team as $member) {
                            echo '<div class="team-member">';
                            echo '<img src="' . $member['photo'] . '" alt="' . $member['name'] . '">';
                            echo '<h3>' . $member['name'] . '</h3>';
                            echo '<p class="position">' . $member['position'] . '</p>';
                            echo '<p class="description">' . $member['description'] . '</p>';
                            echo '</div>';
                        }
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
</body>
</html>