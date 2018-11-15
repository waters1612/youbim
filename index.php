<?php
require 'vendor/autoload.php';
use App\User;

$user = new User('Anais','Morales','mayrmorales@gmail.com','0961294219');

$list = $user->getUser(2);
echo '<pre>';print_r($list);echo '</pre>';exit;
$flag = $user->validateUser();

if($flag)
{
	//$database->create('Felix','Cabrera','cabreraesqueda@gmail.com','0998621802');
	$user->create($user);
}