<body id="myLogin" data-spy="scroll" data-target=".navbar" data-offset="60">
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/index.php"><img src="/img/logo.png"></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li><a><span style="color: yellow">+7(900)641-89-62</span></a></li>
                <li><a><span style="color: yellow">info@glopult.ru</span></a></li>
                <li><a href="/index.php">Выход</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h4><?= $uname ?></h4>
            <table class="table table-condensed">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Устройство</th>
                    <th>Телефон</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM mydev WHERE IDuser = :iduser");
                $stmt->execute(array(':iduser' => $user));
                $count = 1;
                while ($rdev = $stmt->fetch()) { ?>
                    <tr class="<?php if ($rdev["enable"]) echo "danger"; else echo "success"; ?>">
                        <td scope=”row”>
                            <?php echo $count; ?>
                        </td>
                        <td>
                            <a href="dev.php?idev= <?php echo $rdev["ID"] ?>"> <?php echo $rdev["name"]; ?></a>
                        </td>
                        <td>
                            <?php echo $rdev["phone"]; ?>
                        </td>
                    </tr>
                    <?php
                    $count++;
                } ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
<!--            колонка 2-->
        </div>

    </div>
</div>
</body>
</html>