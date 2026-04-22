<nav class="navbar navbar-expand-lg bg-dark bg-opacity-75 position-absolute w-100 z-3" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php if ($route == "") {
                                            echo ("active");
                                        } ?>" href="recipes"><i class="fa-solid fa-house me-1"></i>Kezdőoldal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($route == "create") {
                                            echo ("active");
                                        } ?>" href="create"><i class="fa-solid fa-plus"></i> Új Recept</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php if ($route == "calorieCounter") {
                                            echo ("active");
                                        } ?>" href="calorieCounter"><i class="fa-solid fa-fire-flame-curved me-1"></i>Kalóriaszámláló</a>
                </li>


            </ul>

            <ul class="navbar-nav me-4">
                <?php
                    if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"] == true){
                        echo('<li class="nav-item"><a class="nav-link" href="register"><i class="fa-solid fa-user me-1"></i>Regisztráció</a></li>');
                    }
                ?>
                

                <?php
                if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true) {
                    echo ('<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user me-1"></i>Szia ' . $_SESSION["user_name"] . '
                    </a>
                    <ul class="dropdown-menu">
                        
                        <li><a class="dropdown-item" href="logout"><i class="fa-solid fa-right-from-bracket me-1"></i>Kijelentkezés</a></li>
                    </ul>
                </li>');
                } else {
                    echo ('<li class="nav-item"><a class="nav-link" href="login"><i class="fa-solid fa-right-to-bracket me-1"></i>Bejelentkezés</a></li>');
                }
                ?>

            </ul>

        </div>
    </div>
</nav>