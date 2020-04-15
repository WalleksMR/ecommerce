<?php 
    use \hcode\Model\Category;
    use \hcode\Page;

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