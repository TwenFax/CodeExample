<?php
session_start();
if (!isset($_SESSION['Err']) || $_SESSION['Err'] == "") {
    $_SESSION["Err"] = "";
    ?>
    <script>
        let war = document.getElementById("idxwarn");
        war.style.display = "none";
    </script>
    <?php
} else { ?>
    <script>
        let war = document.getElementById("idxwarn");
        war.innerHTML = "<?php echo $_SESSION['Err']; ?>";
        war.style.display = "block";
    </script>
    <?php
}
$err = $_SESSION['Err'];
session_unset();
$_SESSION['Password'] = "";
if (isset($_POST['save'])) {
    $_SESSION['Login'] = $_POST['login'];
    $_SESSION['Password'] = $_POST['passw'];
    header('Refresh: 0; url="/gp/login.php"');
    exit();
}
include("gp/head.php");
$_SESSION["user"] = 0;
?>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
<?php
include ("gp/yandex.php")
?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#myPage"><img src="/img/logo.png"></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li><a><span style="color: yellow">****************</span></a></li>
                <li><a><span style="color: yellow">***************</span></a></li>
                <li><a href="#about">О нас</a></li>
                <li><a href="#services">Услуги</a></li>
                <!--                <li><a href="#portfolio">Картинки</a></li>-->
                <!--                <li><a href="#pricing">Тарифы</a></li>-->
                <li><a href="#contact">Контакты</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="jumbotron text-center">
    <h1>Глобальный Пульт</h1>
    <p>Мы предлагаем забыть о пультах открытия ворот и шлагбаумов</p>
    <p>Пишите: <span style="color: yellow;">***************</span> Звоните: <span style="color: yellow;">****************</span>
    </p>
</div>
<div id="about" class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            <h4 style="color: #EE0000"><?php echo $err; ?></h4>
            <form method="POST">
                <table class="log12">
                    <tr>
                        <td colspan="2"><h1>Вход в систему</h1></td>
                    </tr>
                    <tr>
                        <div class="form-group">
                            <td class="log12"><label class="login">Пользователь:</label></td>
                            <td class="log12"><input type="text" name="login"></td>
                        </div>
                    </tr>
                    <tr>
                        <div class="form-group">
                            <td class="log12"><label class="passw">Пароль</label></td>
                            <td class="log12"><input type="password" name="passw"></td>
                        </div>
                    </tr>
                    <tr>
                        <div class="form-group">
                            <td colspan="2"><h4><input style="background-color: #f4ee24" type=submit name="save"
                                                       value="                                Вход                                  "
                                                       width="50"></h4></td>
                            <!--                            <td colspan="2"><input type="image" src="img/login.png" name="save"></td>-->
                        </div>
                    </tr>
                </table>
            </form>
        </div>
        <div class="col-sm-1"></div>
        <div class="col-sm-9">
            <!--                        <h2>Что это и зачем??</h2><br>-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-7">
                        <h4>
                            У Вас есть ворота или шлагбаум.
                            Через них ежедневно проезжают автомашины.
                            Вам надо контролировать, кому можно пользоваться въёздом.
                            Особенно трудно забирать уже выданные пультики
                        </h4>
                    </div>
                    <div class="col-sm-5">
                        <img src="img/vorota_mini.png">
                    </div>
                    <div class="col-sm-7">
                        <h4>
                            Каждый водитель пользуется пультом от этих ворот.<br>
                            Пульты мешают, ломаются, занимают место, требуют смены батареек, а главное теряются.
                        </h4>
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-3">
                        <img src="img/pults.png">
                    </div>
                    <div class="col-sm-7">
                        <h4>
                            Система "Глобальный пуль" предлагает Вам полный контроль над доступом к воротам.
                            Вы можете прямо с компьютера или телефона добавлять, исключать, а также
                            группировать пользователей, например,
                            по компаниям - арендаторам бизнес-центра или по домовладениям в посёлке.

                        </h4>
                    </div>
                    <div class="col-sm-5">
                        <img src="img/site.png">
                    </div>
                    <div class="col-sm-7">
                        <h4>
                            Водителям не требуется пульт открытия ворот.
                            Зарегистрированным в системе достаточно позвонить с мобильного телефона
                            на закреплённый за воротами номер и ворота откроются.
                            При этом плата за звонок не взимается, поскольку система определяет номер звонящего
                            и скидывает звонок.
                        </h4>
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-3">
                        <img src="img/phone.png">
                    </div>
                </div>


                <p>Попробуйте! Вам понравится</p>
            </div>
        </div>
    </div>
</div>

<div id="services" class="container-fluid text-center">
    <h2>Презентация системы</h2>
    <iframe src="https://docs.google.com/presentation/d/e/2PACX-1vSYKOaV3LWGfi1jIZli9NnLLkCEAfApmb9o8rQdmI0uKwrjotqVBzqsAqN-JkMyvXKa_gk11UyalfcT/embed?start=true&loop=true&delayms=15000" frameborder="0" width="640" height="389" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
</div>


<div id="services" class="container-fluid text-center">
    <h2>УСЛУГИ</h2>
    <h4>Мы предлагаем</h4>
    <br>
    <div class="row slideanim">
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-wrench logo-small"></span>
            <h4>Установка оборудования</h4>
            <p>Оборудование системы "ГлоПульт" устанавливается в течение 1 дня и не отменяет систему допуска по
                имеющимся пультам.</p>
        </div>
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-file logo-small"></span>
            <h4>Программное обеспечение</h4>
            <p>Система "ГлоПульт" управляется через веб интерфейс. Администратору достаточно иметь компьютер доступом в
                интернет.</p>
        </div>
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-list logo-small"></span>
            <h4>Данные</h4>
            <p>
                Данные о Ваших пользователях, группах пользователей хранятся в базе данных системы
            </p>
        </div>
    </div>
    <br><br>
    <div class="row slideanim">
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-user logo-small"></span>
            <h4>Администратор</h4>
            <p>
                Администратор системы вводит группы пользователей и самих пользователей в систему.
                В дальнейшем пользователей можно добавлять, редактировать и исключать.
                Исключать или временно блокировать можно как отдельных пользователей, так и группами.
            </p>
        </div>
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-phone logo-small"></span>
            <h4>Пользователи</h4>
            <p>
                Пользователи, зарегистрированные в системе открывают ворота (шлагбаум),
                осуществляя звонок на определённый номер. Система распознаёт номер звонящего, и ,
                если его номер зарегистрирован, открывает ворота.
            </p>
        </div>
        <div class="col-sm-4">
            <span class="glyphicon glyphicon-globe logo-small"></span>
            <h4 style="color:#303030;">ГлоПульт</h4>
            <p>
                Мы осуществляем гарантийное и постгарантийное обслуживание системы.
                Запуск системы сопровождается бесплатным обучением.
                Мы также сами готовы наполнить новую систему пользователями.
            </p>
        </div>
    </div>
</div>

<div id="pricing" class="container-fluid">
    <div class="text-center">
        <h2>Контакты</h2>
    </div>
</div>

<!-- Container (Contact Section) -->
<div id="contact" class="container-fluid bg-grey">
    <!--    <h2 class="text-center">Контакты</h2>-->
    <div class="row">
        <div class="col-sm-4">
            <p>Обращайтесь к нам по всем вопросам.</p>
            <p><span class="glyphicon glyphicon-map-marker"></span> Санкт-Петербург</p>
            <p><span class="glyphicon glyphicon-phone"></span> ******************</p>
            <p><span class="glyphicon glyphicon-envelope"></span>****************</p>
        </div>
    </div>
</div>


<footer class="container-fluid text-center">
    <a href="#myPage" title="To Top">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
    <p>&COPY GloPult 2018;</p>
</footer>

<script>
    $(document).ready(function () {
        // Add smooth scrolling to all links in navbar + footer link
        $(".navbar a, footer a[href='#myPage']").on('click', function (event) {
            // Make sure this.hash has a value before overriding default behavior
            if (this.hash !== "") {
                // Prevent default anchor click behavior
                event.preventDefault();

                // Store hash
                var hash = this.hash;

                // Using jQuery's animate() method to add smooth page scroll
                // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
                $('html, body').animate({
                    scrollTop: $(hash).offset().top
                }, 900, function () {

                    // Add hash (#) to URL when done scrolling (default click behavior)
                    window.location.hash = hash;
                });
            } // End if
        });

        $(window).scroll(function () {
            $(".slideanim").each(function () {
                var pos = $(this).offset().top;

                var winTop = $(window).scrollTop();
                if (pos < winTop + 600) {
                    $(this).addClass("slide");
                }
            });
        });
    })
</script>

</body>
</html>