<?php
namespace App;
require '../vendor/autoload.php';


$view = "";
if(isset($_GET["view"]))
	$view = $_GET["view"];
/*
controls the RESTful services
URL mapping
*/
switch($view){

	case "all":
		// to handle REST Url /user/list/
		$userRestHandler = new UserRestHandler();
		$userRestHandler->getAllUsers();
		break;

	case "single":
		// to handle REST Url /user/list/<id>/
		$userRestHandler = new UserRestHandler();
		$userRestHandler->getUser($_GET["id"]);
		break;

	case "" :
		//404 - not found;
		break;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $request = json_decode(file_get_contents("php://input"));
	$userRestHandler = new UserRestHandler();
	$msg = $userRestHandler->validateUser($request);
	$data = Array();

	if($msg != "")
	{
		$data["msg"] = $msg;
		$data["user"] = $request;
	}else
	{
		$data = $userRestHandler->store($request);
	}

	echo json_encode($data);

}
?>