<?php 
    use hcode\PageAdmin;
    use hcode\Model\Category;
    use hcode\Model\User;
    // Categorias 
$app->get('/admin/categories', function(){
	User::verifyLoginAdmin();
	$page = new PageAdmin();
	$cat = Category::ListAll();
	$page->setTpl("categories",array(
		"categories"=>$cat
	));
});

$app->get('/admin/categories/create', function(){
	User::verifyLoginAdmin();
	$page = new PageAdmin();
	$page->setTpl("categories-create");
});

$app->post('/admin/categories/create', function(){
	$cat = new Category();
	$cat->setData($_POST);
	$cat->save();
	header("Location: /admin/categories");
	exit;
});

$app->get('/admin/categories/:idcategory/delete', function($idcategory){
	User::verifyLoginAdmin();
	$cat = new Category();
	$cat->get((int)$idcategory);
	$cat->delete();
	header("Location: /admin/categories");
	exit;
});

$app->get('/admin/categories/:idcategory', function($idcategory){
	User::verifyLoginAdmin();
	$page = new PageAdmin();
	$Cat = new Category();
	$Cat->get((int)$idcategory);

	$page->setTpl("categories-update", array(
		"category"=>$Cat->getValues()
	));
});

$app->post('/admin/categories/:idcategory', function($idcategory){
	User::verifyLoginAdmin();
	$Cat = new Category();
	$Cat->get((int)$idcategory);
	$Cat->setData($_POST);
	$Cat->update();
	header("Location: /admin/categories");
	exit;
});
// FIM Categorias 
?>