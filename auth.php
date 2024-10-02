<div class="container">
    <?php
        session_start();
        require_once 'connectionDB.php';

        // "Базовые стили"
        echo '<link rel="stylesheet" href="css/style.css">';

        if (!empty($_POST['password']) and !empty($_POST['login'])) {

            // Записываем данные после отправки формы в переменные
            $login = $_POST['login'];
            $password = $_POST['password'];

            // Проверяем отправленные данные на совпадение в Б\Д, рез-тат записываем в $user
            $query = "SELECT * FROM users WHERE login='$login' AND password='$password'";
            $res = mysqli_query($link, $query);
            $user = mysqli_fetch_assoc($res);
            
            if (!empty($user)) {
                $_SESSION['auth'] = true;
                $_SESSION['login'] = $login;
                $_SESSION['message']='Вы авторизованы!';
                header('Location: index.php'); 
                die(); 
            } else {
                echo '<p class="alert alert--error">Проверьте Ваши данные!</p>';
            }
        }
    ?>
    <form class="form" action="" method="POST">
        <labe class="form-label" for="login">логин</labe>
        <input class="form-input" name="login" required>
        <label  class="form-label" for="password">пароль</label>
        <input class="form-input" name="password" type="password" required>
        <button class="btn btn--link" type="submit" onclick="return confirm('Вы уверены?')">Авторизоваться</button>
    </form>
</div>