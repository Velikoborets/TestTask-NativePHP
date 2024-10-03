<div class="container">
    <?php
        session_start();
        require_once 'connectionDB.php';

        // "Базовые стили"
        echo '<link rel="stylesheet" href="css/style.css">';

        // При заполненных полях начинаем проверки
        if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['confirm_password'] 
            && !empty($_POST['email'])) && !empty($_POST['phone'])) {
            
            // Для удобства записываем отпр. значения из формы в переменные
            $login = $_POST['login'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            
            // Введём доп. переменные, для дальнейших проверок полей формы на валидацию
            $validLogin = false;
            $validPhone = false;
            $validEmail = false;
            $validPassword = false;

            // Проверяем логин на уникальность 
            $queryCheckLogin = "SELECT * FROM users WHERE login='$login'";
            $dbQueryCheckLogin = mysqli_query($link, $queryCheckLogin) or die (mysqli_error($link));
            $checkLogin = mysqli_fetch_assoc($dbQueryCheckLogin);

            if (empty($checkLogin)) {
                $validLogin = true;
            } else {
                echo '<p class="alert alert--error">Логин уже занят</p>';
            }

            // Проверка email на корректность + уникальность
            $emailPattern = '/[a-zA-Z0-9\-_]+@[a-z]+\.[a-z]{2,}/';

            if (!preg_match($emailPattern, $email)) {
                echo '<p class="alert alert--error">У вас не корректный email!</p>';
            } else {
                $queryCheckEmail = "SELECT * FROM users WHERE email='$email'";
                $dbQueryCheckEmail = mysqli_query($link, $queryCheckEmail) or die (mysqli_error($link));
                $checkEmail = mysqli_fetch_assoc($dbQueryCheckEmail);

                if (empty($checkEmail)) {
                    $validEmail = true;
                } else {
                    echo '<p class="alert alert--error">Email уже занят</p>';
                }
            }

            // Проверка телефона на уникальность
            $queryCheckPhone = "SELECT * FROM users WHERE phone='$phone'";
            $dbQueryCheckPhone = mysqli_query($link, $queryCheckPhone) or die (mysqli_error($link));
            $checkPhone = mysqli_fetch_assoc($dbQueryCheckPhone);
            
            if (empty($checkPhone)) {
                $validPhone = true;
            } else {
                echo '<p class="alert alert--error">Телефон уже занят</p>';
            }

            // Проверка паролей на совпадение
            if ($password == $confirm_password) {
                $validPassword = true;
            } else {
                echo '<p class="alert alert--error">Пароль не совпадают!</p>';
            }
            
            // После всех проверок заносим данные в Б\Д
            if ($validLogin == true && $validPassword == true && $validEmail == true && $validPhone == true) {

                // Переопределим $password перед занесением в БД (занесем с хэшом)
                $password = password_hash($password, PASSWORD_DEFAULT);
            
                $queryInsert = "INSERT INTO users SET login='$login', password='$password', email='$email', phone='$phone' ";
                $db_query_insert = mysqli_query($link, $queryInsert) or die (mysqli_error($link));

                $_SESSION['auth'] = true;
                $_SESSION['login'] = $login;
                $_SESSION['message'] = 'Вы зарегистрировались!!';
                header('Location: index.php'); 
                die();
            }

        }
    ?>
    <form class="form" action="" method="POST">
        <label class="form-label" for="login">придумайте логин</label>
        <input class="form-input" name="login" value="<?php if (!empty ($login)) echo $login ?>" required>
        <label class="form-label" for="password">пароль</label>
        <input class="form-input" name="password" type="password" required>
        <label class="form-label" for="confirm_password">подтвердите пароль</label>
        <input class="form-input" name="confirm_password" type="password" required>
        <label class="form-label" for="email">email</label>
        <input class="form-input" name="email" type="email" value="<?php if (!empty ($email)) echo $email ?>" required>
        <label class="form-label" for="phone">телефон</label>
        <input class="form-input" name="phone" type="phone" value="<?php if (!empty ($phone)) echo $phone ?>" required>
        <button class="btn btn--link" type="submit" onclick="return confirm('Вы уверены?')">Зарегистрироваться</button>
    </form>
</div>