<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();
$connected=1;
// Following GLOBALS configuration is inspired by phpSteroid (https://github.com/DarKnight1346/phpSteroid/blob/master/index.php)
$GLOBALS['config'] = array(
	"appName" => "ICS",
	"version" => "beta 0.1",
	"domain" => "localhost",
	"authenticator" => array(
		"sessionCookieLength" => 40, //length of the connexion cookie
		"connexionTimeout" => 3600, //if time-lastPingTime > this, ask for a reconnexion
		"onlineTimeout"=>600 // 10 minutes
		),
	"database" => array(
		"host"=>"localhost",
		"username"=>"root",
		"password"=>"root",
		"name"=>"ics",
		"type_ref" => array("comment"=>"0")
		),
	"paths" => array(
		"views" => "../views/",
		"actions" => "../actions/",
		"libs" => "../lib/",
		"ajax" => "../lib/ajax/"
		),
	"views" => array(
		"accueil" => "accueil.php",
		"calendar" => "calendar.php",
		"signup" => "signup.php",
		"login" => "login.php",
		"addCourse" => "addCourse.php",
		"explore" => "explore.php",
		"profile" => "profile.php",
		"view" => "documentView.php"
		),
	"ajax" => array(
		"getchap" => "chapitres.get.php",
		"getmat" => "matieres.get.php",
		"comments" => "comment_view.get.php",
		"postCom" => "comment.post.php",
		"search" => "search.get.php",
		"like" => "like.post.php",
		"online_users" => "online_users.get.php"
		),
	"actions" => array(
		"signup" => "proceedSignUp.php",
		"login" => "proceedLogin.php",
		"addDoc" => "proceedAddDoc.php"
		),
	"upload" => array(
		'valid_extensions' => array(
			'jpg',
			'jpeg',
			'gif',
			'png',
			'pdf')
		),
	"default" => array(
		"view" => "accueil"
		)
	);

require_once($GLOBALS['config']['paths']['libs']."db.funcs.php");

$GLOBALS['db'] = getPdoDbObject();

require_once($GLOBALS['config']['paths']['libs']."class/Authenticator.php");


$auth = NULL;

if(isset($_SESSION['auth'])) {
	$auth = unserialize($_SESSION['auth']);
	if(isset($_GET['r']) && $_GET['r']=='logout') {
		$auth->disconnect();
		header('Location: ?u=accueil');
		exit();
	}
	if(isset($_GET['r']) && $_GET['r']=='ping') {
		echo $auth->ping();
		exit();
	}
	$auth->ping();
}

if($auth == NULL)
	$auth = new Authenticator();

if(!isset($_GET['u']) || $_GET['u']=="")
	$_GET['u'] = $GLOBALS['config']['default']['view'];

// HANDLE ajax
if(isset($_GET['ajax']) && array_key_exists($_GET["ajax"], $GLOBALS['config']["ajax"]))
	include($GLOBALS['config']["paths"]["ajax"].$GLOBALS['config']["ajax"][$_GET["ajax"]]);

//HANDLE actions
if(isset($_POST['action']) && array_key_exists($_POST["action"], $GLOBALS['config']["actions"]))
	include($GLOBALS['config']["paths"]["actions"].$GLOBALS['config']["actions"][$_POST["action"]]);

//HANDLE views
if(array_key_exists($_GET["u"], $GLOBALS['config']["views"]))
	include($GLOBALS['config']["paths"]["views"].$GLOBALS['config']["views"][$_GET["u"]]);
else
	die("Erreur, cet URL n'existe pas.");

if(!isset($auth))
	$auth = new Authenticator();

$_SESSION['auth'] = serialize($auth);

?>

<script>
	function ping() {
		var req = new XMLHttpRequest();
		req.onreadystatechange = function(event) {
    // XMLHttpRequest.DONE === 4
    if (this.readyState == 4) {
    	if (this.status != 200) {
    		$("#server-status").html("<i class='fa fa-plug' aria-hidden='true'></i> Serveur inaccessible");
    	} else if (this.responseText=="pong"){
    		$("#server-status").html("<i class='fa fa-check-circle' aria-hidden='true'></i> Serveur connecté");
    	} else {
    		$("#server-status").html("<i class='fa fa-plug' aria-hidden='true'></i> Serveur déconnecté");
    	}
    }
};
req.open("GET", "?r=ping", true);
req.send();
window.setTimeout(ping, 2000);
}

function lateInitMain() {
	ping();
	$("#server-status").html("Connexion au serveur...");
	$("#server-ping-fire").click(function() {
		$("#server-status").html("Connexion au serveur...");
		ping();
	})
}


lateInitMain();
</script>


<?
$GLOBALS['db']=null;
exit(); 
?>