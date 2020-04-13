<?php 
    use hcode\PageAdmin;
    use hcode\Model\Product;
    use hcode\Model\User;

    // Produtos 
$app->get('/admin/products', function(){
	User::verifyLoginAdmin();
	$page = new PageAdmin();
	$Pro = Product::ListAll();
	$page->setTpl("products",array(
		"products"=>$Pro
	));
});

$app->get('/admin/products/create', function(){
	User::verifyLoginAdmin();
	$page = new PageAdmin();
	$page->setTpl("products-create");
});

$app->post('/admin/products/create', function(){
	$Pro = new Product();
	$Pro->setData($_POST);
	$Pro->save();
	header("Location: /admin/products");
	exit;
	
});

$app->get('/admin/products/:idproduct/delete', function($idproducts){
	User::verifyLoginAdmin();
	$Pro = new Product();
	$Pro->get((int)$idproducts);
	$Pro->delete();
	header("Location: /admin/products");
	exit;
});

$app->get('/admin/products/:idproducts', function($idproducts){
	User::verifyLoginAdmin();
	$page = new PageAdmin();
	$Pro = new Product();
	$Pro->get((int)$idproducts);

	$page->setTpl("products-update", array(
		"product"=>$Pro->getValues()
	));
});

$app->post('/admin/products/:idproduct', function($idproduct){
	User::verifyLoginAdmin();
	$Pro = new Product();
	$Pro->get((int)$idproduct);
	$Pro->setData($_POST);

	header("Location: /admin/products");
	exit;
});
// FIM Produtos 
?>