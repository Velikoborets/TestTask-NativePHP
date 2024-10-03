<div class="container">
    <?php
        session_start();
        require_once 'connectionDB.php';

        // "Базовые стили"
        echo '<link rel="stylesheet" href="css/style.css">';

        echo '<p>Хотите изменить данные? Пожалуйста!</p>';

        if ($_SESSION['auth']) {
            $id = $_SESSION['id'];

            // Вытаскиваем данные юзера по id
            $query = "SELECT * FROM users WHERE id='$id'";
            $dbQuery = mysqli_query($link, $query) or die(mysqli_error($link));

            $dataUser = [];
            while ($user = mysqli_fetch_assoc($dbQuery)) {
                $dataUser[] = $user;
            }

            if (!empty($_POST['login']) && !empty($_POST['email']) && !empty($_POST['phone'])) {

                // Для удобства записываем отпр. значения из формы в переменные
                $login = $_POST['login'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];

                // Введём доп. переменные, для дальнейших проверок полей формы на валидацию
                $validLogin = true;
                $validPhone = true;
                $validEmail = true;
                $validPassword = true;

                // Данные текущего юзера, тоже, для удобства запишем в переменную
                $oldLogin = $dataUser[0]['login'];
                $oldEmail = $dataUser[0]['email'];
                $oldPhone = $dataUser[0]['phone'];


                // Проверка login на уникальность (если он изменён). Аналогично реализованы проверки для: email, phone
                // За исключем первой проверки, на совпадение с текущими данными
                if ($login != $oldLogin) {
                    $queryCheckLogin = "SELECT * FROM users WHERE login='$login'";
                    $dbQueryCheckLogin = mysqli_query($link, $queryCheckLogin) or die(mysqli_error($link));
                    $checkLogin = mysqli_fetch_assoc($dbQueryCheckLogin);
                    if (!empty($checkLogin)) {
                        $validLogin = false;
                        echo '<p class="alert alert--error">Логин уже занят</p>';
                    }
                }

                // Проверка поля email
                if ($email != $oldEmail) {

                    // Проверяем на корректность формата
                    $emailPattern = '/[a-zA-Z0-9\-_]+@[a-z]+\.[a-z]{2,}/';

                    if (!preg_match($emailPattern, $email)) {
                        $validEmail = false;
                        echo '<p class="alert alert--error">У вас не корректный email!</p>';
                    } else {
                        // Если email - корректный, то проверяем на уникальность
                        $queryCheckEmail = "SELECT * FROM users WHERE email='$email'";
                        $dbQueryCheckEmail = mysqli_query($link, $queryCheckEmail) or die (mysqli_error($link));
                        $checkEmail = mysqli_fetch_assoc($dbQueryCheckEmail);

                        if (!empty($checkEmail)) {
                            $validEmail = false;
                            echo '<p class="alert alert--error">Email уже занят</p>';
                        }
                    }
                }

                // Проверка поля phone
                if ($phone != $oldPhone) {
                    $queryCheckPhone = "SELECT * FROM users WHERE phone='$phone'";
                    $dbQueryCheckPhone = mysqli_query($link, $queryCheckPhone) or die(mysqli_error($link));
                    $checkPhone = mysqli_fetch_assoc($dbQueryCheckPhone);
                    if (!empty($checkPhone)) {
                        $validPhone = false;
                        echo '<p class="alert alert--error">Телефон уже занят</p>';
                    }
                }

                // Проверка пароля (при условии, что введён)
                if (!empty($password) && !empty($confirm_password)) {
                    if ($password !== $confirm_password) {
                        $validPassword = false;
                        echo '<p class="alert alert--error">Пароли не совпадают!</p>';
                    }
                }

                // После выполнения всех проверок, заносим данные в Б\Д
                if ($validLogin && $validEmail && $validPhone && $validPassword) {

                    // Если пароль изменён, только тогда хэшируем
                    $password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : $dataUser[0]['password'];

                    $queryInsert = "UPDATE users SET login='$login', password='$password', email='$email', phone='$phone' WHERE id = $id";
                    $db_query_insert = mysqli_query($link, $queryInsert) or die(mysqli_error($link));

                    $_SESSION['id'] = $id;
                    $_SESSION['auth'] = true;
                    $_SESSION['message'] = 'Ваши данные изменены!';
                    header('Location: index.php');
                    die();
                }
            }
        } else {
            header('Location: index.php');
            die();
        }
    ?>
    <?php foreach ($dataUser as $currentUser): ?>
    <form class="form" action="" method="POST">
        <label class="form-label" for="login">текущий логин</label>
        <input class="form-input" name="login" value="<?=$currentUser['login']?>">
        <label class="form-label" for="password">введие новый пароль</label>
        <input class="form-input" name="password" type="password">
        <label class="form-label" for="confirm_password">подтвердите пароль</label>
        <input class="form-input" name="confirm_password" type="password">
        <label class="form-label" for="email">текущий email</label>
        <input class="form-input" name="email" type="email" value="<?=$currentUser['email']?>">
        <label class="form-label" for="phone">текущий телефон</label>
        <input class="form-input" name="phone" type="phone" value="<?=$currentUser['phone']?>">
        <button class="btn btn--link" type="submit" onclick="return confirm('Вы уверены?')">Изменить данные</button>
    </form>
    <?php endforeach ?>
</div>