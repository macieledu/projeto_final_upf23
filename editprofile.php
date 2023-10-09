<?php
 require_once("templates/header.php");
 require_once("dao/userDAO.php");

 $userDAO = new UserDao($conn, $BASE_URL);

 $userData = $userDAO->verifyToken();

?>
    <div id="main-container" class="container-fluid">
        <h1>Edição de perfil</h1>
    </div>
<?php
 require_once("templates/footer.php")
?>  