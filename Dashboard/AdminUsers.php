<?php 
    use hcode\Model\User;
    use hcode\PageAdmin;
    
    // Verificacao do Login
$app->get('/admin/login', function(){

	$page = new PageAdmin([
		"header"=> false,
		"footer"=> false
	]);
	$page->setTpl("login");
});

$app->post('/admin/login', function() {
	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;
});

$app->get('/admin/logout', function(){
	User::logout();
	header("Location: /admin/login");
	exit;
});

// USUARIOS
$app->get('/admin/users', function(){
	User::verifyLoginAdmin();
	$page = new PageAdmin();
	$users = User::ListAll();
	$page->setTpl("users",array(
		"users"=>$users
	));
});

$app->get('/admin/users/create', function(){
	User::verifyLoginAdmin();
	$page = new PageAdmin();
	$page->setTpl("users-create");
});

$app->get('/admin/users/:iduser/delete', function($iduser){
	User::verifyLoginAdmin();
	$user= new User();
	$user->get((int)$iduser);
	$user->delete();
	header("Location: /admin/users");
	exit;

});

$app->get('/admin/users/:iduser', function($iduser){
	User::verifyLoginAdmin();
	$user = new User();
	$user->get((int)$iduser);

	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
});

$app->post('/admin/users/create', function(){
	User::verifyLoginAdmin();
	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
	$user->setData($_POST);
	$user->save();
	header("Location: /admin/users");
	exit;
});

$app->post('/admin/users/:iduser', function($iduser){
	User::verifyLoginAdmin();
	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
	$user->get((int)$iduser);
	$user->setData($_POST);
	$user->update();
	header("Location: /admin/users");
	exit;
});
// FIM USUARIOS
?>