<div class="container">
    <?php
        session_start();

        // "Базовые стили"
        echo '<link rel="stylesheet" href="css/style.css">';

        if ($_SESSION['auth']) {
            echo 'Вы видите это т.к Авторизованы!';
        }
    ?>
</div>