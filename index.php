<div class="container">
    <?php
        session_start();
        require_once 'connectionDB.php';

        // "Базовые стили"
        echo '<link rel="stylesheet" href="css/style.css">'; 
    ?>

    <?php if (!empty($_SESSION['message'])): ?>
        <p class="alert alert--success"><?=$_SESSION['message']?></p>    
        <? unset($_SESSION['message']) ?>
    <? endif?>

    <?php if (!empty($_SESSION['auth'])): ?>
        <p>Вы вошли в аккаунт!</p>
        <a class="btn btn--link" href="/changeData.php">Изменить данные</a>
        <a class="btn btn--link btn--exit" href="/logout.php">Выйти из аккаунта</a>
    <? else:?>
        <p>Авторизуйтесь или зарегайтесь, пожалуйста! Вам ведь не сложно)</p>
        <a class="btn btn--link" href="/auth.php">Авторизация</a>
        <a class="btn btn--link" href="/register.php">Регистарция</a>
    <? endif?>
</div>

<? /* Для обновления Авторизации\Регистрации *///    session_destroy(); ?>