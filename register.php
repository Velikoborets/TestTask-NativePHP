<div class="container">
    <?php
        session_start();
        require_once 'connectionDB.php';

        // "Базовые стили"
        echo '<link rel="stylesheet" href="css/style.css">';

        if (!empty($_POST['login']) and !empty($_POST['password'])) {
            
            $login = $_POST['login'];
            $password = $_POST['password']; 

            $query = "INSERT INTO users SET login='$login', password='$password'";
            $res = mysqli_query($link, $query);

            if ($res) {
                $_SESSION['auth'] = true;
                $_SESSION['login'] = $login;
                $_SESSION['message'] = 'Вы зарегистрировались!!';
                header('Location: index.php'); 
                die();
            } else {
                echo '<div class="alert alert--error">Проверьте Ваши данные!</div>';
            }
        }
    ?>
    <form class="form" action="" method="POST">
        <label class="form-label" for="login">придумайте логин</label>
        <input class="form-input" name="login" required>
        <label class="form-label" for="password">пароль</label>
        <input class="form-input" name="password" type="password" required>
        <label class="form-label" for="confirm-password">подтвердите пароль</label>
        <input class="form-input" name="confirm-password" type="password" required>
        <label class="form-label" for="email">email</label>
        <input class="form-input" name="password" type="email" required>
        <label class="form-label" for="phone">телефон</label>
        <input class="form-input" name="password" type="phone" required>
        <button class="btn btn--link" type="submit" onclick="return confirm('Вы уверены?')">Зарегистрироваться</button>
    </form>
</div>