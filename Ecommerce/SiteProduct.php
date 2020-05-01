<?php 
    use \hcode\Model\Product;
    use \hcode\Model\Cart;
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

    $app->get('/cart',function(){
        $cart = Cart::getFromSession();
        $page = new Page();

        $page->setTpl('cart',[
            "cart"=>$cart->getValues(),
            "products"=>$cart->getProducts()
        ]);
    });

    $app->get('/cart/:idproduct/add', function($idproduct){
        $product = new Product();
        $product->get((int)$idproduct);

        $cart = Cart::getFromSession();
        $cart->addProducts($product);
        header("Location: /cart");
        exit;
    });

    $app->get('/cart/:idproduct/minus', function($idproduct){
        $product = new Product();
        $product->get((int)$idproduct);

        $cart = Cart::getFromSession();
        $cart->removeProducts($product);
        header("Location: /cart");
        exit;
    });
    $app->get('/cart/:idproduct/remove', function($idproduct){
        $product = new Product();
        $product->get((int)$idproduct);

        $cart = Cart::getFromSession();
        $cart->removeProducts($product, true);
        header("Location: /cart");
        exit;
    });

?>