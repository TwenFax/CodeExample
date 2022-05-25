<!DOCTYPE html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <title>ГлоПульт</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <style>
        .alert-warning {
            display: none;
        }

        newuservalidate {
            margin-left: 70px;
            font-weight: bold;
            float: left;
            clear: left;
            width: 100px;
            text-align: left;
            margin-right: 10px;
            font-family: sans-serif, bold, Arial, Helvetica;
            font-size: 14px;
        }
    </style>

    <?php
    if (!($_SESSION)) {
        session_start();
    }
    $user = $_SESSION["user"];
    if ($user != 1) {
        $_SESSION['Err'] = "Forbidden";
        header("Location: /");
        exit();
    }
    ?>

    <script>
        function f1() {

            // bool function validate() {
            let name = document.forms["RegForm"]["Name"];
            let email = document.forms["RegForm"]["EMail"];
            let phone = document.forms["RegForm"]["Telephone"];
            let login = document.forms["RegForm"]["Login"];
            let password = document.forms["RegForm"]["Password"];
            let warn = document.getElementById("warn");

            warn.style.display = 'block';

            if (login.value == "") {
                warn.innerText = "<strong>Внимание! </strong>Пропущен догин";
                warn.style.display = "block";
                login.focus();
                return false;
            }


            if (name.value == "") {
                warn.innerHTML = "<strong>Внимание! </strong>Пропущено имя пользователя";
                warn.style.display = "block";
                name.focus();
                return false;
            }

            if (email.value == "") {
                warn.innerHTML = "<strong>Внимание! </strong>Пропущен e-mail";
                warn.style.display = "block";
                email.focus();
                return false;
            }

            if (email.value.indexOf("@", 0) < 0) {
                warn.innerHTML = "<strong>Внимание! </strong>Неверный формат e-mail";
                warn.style.display = "block";
                email.focus();
                return false;
            }

            if (email.value.indexOf(".", 0) < 0) {
                warn.innerHTML = "<strong>Внимание! </strong>Неверный формат e-mail";
                warn.style.display = "block";
                email.focus();
                return false;
            }

            if (phone.value == "") {
                warn.innerHTML = "<strong>Внимание! </strong>Пропущен телефон";
                warn.style.display = "block";
                phone.focus();
                return false;
            }

            if (password.value == "") {
                warn.innerHTML = "<strong>Внимание! </strong>Пропущен пароль";
                warn.style.display = "block";
                password.focus();
                return false;
            }

            return true;
        }
    </script>


</head>

<body id="myNewuser" data-spy="scroll" data-target=".navbar" data-offset="60">
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/gp/not_used/login"><img src="/img/logo.png"></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/gp/not_used/login">Выход</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <br><br><br>
            <div class="alert alert-warning alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" id="warn">&times;</a>
                <strong>Внимание!</strong> Indicates a successful or positive action.
            </div>
            <h1>Новый пользователь</h1>

            <form name="RegForm" action="uadd.php" method="post">
                <div class="form-group">
                    <label for="Name">Имя пользователя:</label>
                    <input type="text" class="form-control" name="Name" id="Name">
                </div>
                <div class="form-group">
                    <label for="Login">Логин:</label>
                    <input type="text" class="form-control" name="Login" id="Login">
                </div>
                <div class="form-group">
                    <label for="Password">Пароль:</label>
                    <input type="password" class="form-control" name="Password" id="Password">
                </div>
                <div class="form-group">
                    <label for="EMail">E-mail:</label>
                    <input type="text" class="form-control" name="EMail" id="EMail">
                </div>
                <div class="form-group">
                    <label for="Telephone">Телефон:</label>
                    <input type="text" class="form-control" name="Telephone" id="Telephone">
                </div>
                <div class="form-group">
                    <input type="submit" value="Создать" onclick="f1()" name="Submit">
                    <input type="reset" value="Сбросить" name="Reset">
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>