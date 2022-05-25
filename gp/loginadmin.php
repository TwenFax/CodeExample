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
                <li><a href="/index.php">Выход</a></li>
                <li><a href="nuser.php">Новый пользователь</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <h2></h2>
            <table class="table table-condensed">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Пользователь</th>
                    <th>Логин</th>
                    <th>Телефон</th>
                    <th>E-mail</th>
                    <th>Баланс</th>
                    <th>Тариф</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM myusers");
                $count = 1;
                while ($row = $stmt->fetch()) { ?>
                    <tr class="<?php if ($row->enable) echo "danger"; else echo "success"; ?>">
                        <td scope=”row”>
                            <?php echo $count; ?>
                        </td>
                        <td>
                            <a href="eduser.php?iduser= <?php echo $row["ID"] ?>"> <?php echo $row["name"]; ?></a>
                        </td>
                        <td>
                            <?php echo $row["login"]; ?>
                        </td>
                        <td>
                            <?php echo $row["phone"]; ?>
                        </td>
                        <td>
                            <?php echo $row["email"]; ?>
                        </td>
                        <td>
                            <?php echo $row["balance"]; ?>
                        </td>
                        <td>
                            <?php echo $row["tarif"]; ?>
                        </td>
                    </tr>
                    <?php
                    $count++;
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>