<?php
	use \hcode\Page;
	use \hcode\Model\Category;

	$app->get('/', function() {
		
		$page = new Page();
		$page->setTpl("index");
	});

	$app->get('/categories/:idcategory', function($idcategory) {
		$cat = new Category();
		$cat->get((int)$idcategory);
		$page = new Page();
		$page->setTpl("category", array(
			"category"=>$cat->getValues(),
			"products"=>[]
		));
	});
?>