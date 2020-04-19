<?php 
    use \hcode\Model\Category;
    use \hcode\Page;

	$app->get('/categories/:idcategory', function($idcategory) {
		$cat = new Category();
		$cat->get((int)$idcategory);
		$getPaginetion = (isset($_GET["page"]) ? (int)$_GET["page"] : 1);
		$pagination = $cat->getProductPage($getPaginetion);

		$pages = [];
		for ($i=1; $i<=$pagination['pages']; $i++) { 
			array_push($pages, [
					"link"=>'/categories/'.$cat->getidcategory().'?page='.$i,
					"page"=>$i
				]);
			}
		$page = new Page();
		$page->setTpl("category", array(
			"category"=>$cat->getValues(),
			"products"=>$pagination["data"],
			"pages"=>$pages
		));
	});
?>