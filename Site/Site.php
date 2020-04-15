<?php
	use \hcode\Page;
	use \hcode\Model\Product;

	$app->get('/', function() {
		$products = Product::ListAll();
		$page = new Page();
		$page->setTpl("index", [
			'products'=>Product::checkList($products)
		]);
	});

?>