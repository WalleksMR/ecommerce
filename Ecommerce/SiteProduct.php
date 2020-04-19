<?php 
    use \hcode\Model\Product;
    use \hcode\Page;

    $app->get('/products/:desurl', function($desurl){
        $Prod = new Product();
        $page = new Page();

        $Prod->getFromUrl($desurl);
        // var_dump($Prod);exit;
        $page->setTpl("product-detail", [
            "product"=>$Prod->getValues(),
            "categories"=>$Prod->getCategories()
        ]);
    });

?>