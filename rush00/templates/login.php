<?php
include_once ("ressources/handle_db.php");
if(!isset($_SESSION)){ 
    session_start(); 
} 

function connexion($email, $hash_passwd)
{  
  $var1 = query("SELECT passwd FROM `user` WHERE email = '$email'");
  $var2 = query("SELECT email, id FROM `user` WHERE passwd = '$hash_passwd'");
  if (mysqli_num_rows($var1) > 0)
    $ret1 = mysqli_fetch_assoc($var1);
  if (mysqli_num_rows($var2) > 0)
    $ret2 = mysqli_fetch_assoc($var2);
  if (isset($ret2['email']) && $ret2['email'] === $email && isset($ret1['passwd']) && $ret1['passwd'] === $hash_passwd)
    return ($ret2['id']);
  else
    return (FALSE);
}

if (isset($_POST['submit']) && $_POST['submit'] === "Login")
{
    $email = escape_string($_POST["email"]);
    $passwd = escape_string($_POST["passwd"]);
    $hash_passwd = hash("whirlpool", $passwd);
    $ret = connexion($email, $hash_passwd);
    if ($ret !== FALSE)
    {
        $_SESSION["logged_in"] = TRUE;
        $_SESSION["user_email"] = $email;
        $_SESSION['login_id'] = $ret;
        echo "Connexion réussie. Bienvenue !\n";
        header("Location: index.php");
    }
    else{
        echo "L'email ou le mot de passe est incorrect.\n";
    }
}
else if (isset($_POST['submit']) && $_POST['submit'] === "Se déconnecter")
{
    session_unset();
    session_destroy();
    echo "Vous êtes déconnecté.\n";
    header("Location: ../index.php");
}
?>
<form method="post" id="connection">
    <fieldset>
        <h1>Se connecter</h1>
        <label for="email">E-mail : </label><input id="email" name="email"/><br>
        <label for="passwd">Mot de passe : </label><input id="passwd" name="passwd" type="password"/><br>
        <input type="submit" class="submit" name="submit" value="Login" ><br/>
    </fieldset>
</form>
