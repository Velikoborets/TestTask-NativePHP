<div class="container">
    <?php
        session_start();
        require_once 'connectionDB.php';

        // Генерация CAPTCHA
        if (empty($_SESSION['captcha'])) {
            $_SESSION['captcha'] = rand(1000, 9999);
        }

        // "Базовые стили"
        echo '<link rel="stylesheet" href="css/style.css">';

        echo '<p>Авторизация</p>';

        if (!empty($_POST['password']) and (!empty($_POST['phone'])) and (!empty($_POST['captcha']))) {

            $input = $_POST['phone']; 
            $password = $_POST['password'];
            $captcha = $_POST['captcha'];

            // Проверка CAPTCHA
            if ($captcha != $_SESSION['captcha']) {
                echo '<p class="alert alert--error">Неправильная CAPTCHA!</p>';
            } else {
                // Проверка, является ли введенное значение в input телефоном или email
                $phonePattern = '/^[0-9]+$/';
                if (preg_match($phonePattern, $input)) {
                    $phone = $input;
                    $email = false;
                } else {
                    $email = $input;
                    $phone = false;
                }

                $query = "SELECT * FROM users WHERE email='$email' OR phone='$phone'";
                $res = mysqli_query($link, $query) or die(mysqli_error($link));
                $user = mysqli_fetch_assoc($res);
               
                if (!empty($user)) {
                    $hash = $user['password']; // Вытаскиваем пароль(хэш) юзера из Б\Д
                    $id = $user['id']; // Вытаскиваем id user, чтобы работать с его данными

                    // Проверяем введенный пароль на соответствие паролю(хэшу) в Б/Д
                    if (password_verify($_POST['password'], $hash)) {
                        $_SESSION['id'] = $id;
                        $_SESSION['auth'] = true;
                        $_SESSION['message']='Вы авторизованы!';
                        header('Location: index.php'); 
                        die();
                    } else {
                        echo '<p class="alert alert--error">Неправильный пароль!</p>';
                    }
                } else {
                    echo '<p class="alert alert--error">Неправильный телефон или email!</p>';
                }
            }
        }
    ?>
    <form class="form" action="" method="POST">
        <label class="form-label" for="phone">телефон или email</label>
        <input class="form-input" name="phone" required>
        <label  class="form-label" for="password">пароль</label>
        <input class="form-input" name="password" type="password" required>
        <div class="captcha">CAPTCHA: <?php echo $_SESSION['captcha']; ?></div>
        <input class="form-input" name="captcha" required placeholder="Введите captcha">
        <button class="btn btn--link" type="submit" onclick="return confirm('Вы уверены?')">Авторизоваться</button>
    </form>
</div>