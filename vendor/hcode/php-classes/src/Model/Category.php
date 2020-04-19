<?php 
    namespace hcode\Model;
    use \hcode\DB\Sql;
    use \hcode\Model;

class Category extends Model {

    public static function ListAll(){
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
    }

    public function save()
    {
        $sql = new Sql();
        $resuts = $sql->select("CALL sp_categories_save (:pidcategory, :pdescategory)", array(
            ":pidcategory"=>$this->getidcategory(),
            ":pdescategory"=>$this->getdescategory(),
            ":pdescategory"=>$this->getdescategory()
        ));
        $this->setData($resuts[0]);
        Category::updateFile();
    }

    public function get($idcategory){
        $sql = new Sql();
        $resuts = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", array(
            ":idcategory"=>$idcategory
        ));
        $data = $resuts[0];
        $this->setData($data);
    }

    public function update()
    {
        $sql = new Sql();
        $resuts = $sql->select("CALL sp_category_save(:pidcategory, :pdescategory)", array(
            ":pidcategory"=>$this->getidcategory(),
            ":pdescategory"=>$this->getdescategory(),
        ));
        $this->setData($resuts[0]);
        Category::updateFile();
    }

    public function delete()
    {
        $user = new Sql();
        $user->query("CALL sp_category_delete(:idcategory)",array(
            ":idcategory"=>$this->getidcategory()
        ));
        Category::updateFile();
    }

    public static function updateFile(){
        $cat = Category::ListAll();
        $html = [];
        foreach ($cat as $row) {
           array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
        }
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode('', $html));
    }
    public function getProducts($related = true)
    {
        $sql = new Sql();
        if($related === true){
            
            return $sql->select(
                "SELECT * FROM tb_products WHERE idproduct IN(
                    SELECT a.idproduct
                    FROM tb_products a
                    INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
                    WHERE b.idcategory = :id 
            );",[
                ":id"=>$this->getidcategory()
                ]);
        }else{
           
            return $sql->select(
                "SELECT * FROM tb_products WHERE idproduct NOT IN(
                    SELECT a.idproduct
                    FROM tb_products a
                    INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
                    WHERE b.idcategory = :id
            );",["id"=>$this->getidcategory()]);
        }
    }

    public function productAdd(Product $product)
    {
        $sql = new Sql();
        $sql->query("INSERT INTO tb_productscategories (idcategory, idproduct) VALUES(:idcategory, :idproduct)", [
            ":idcategory"=>$this->getidcategory(),
            ":idproduct"=>$product->getidproduct()
        ]);
    }

    public function productRemove(Product $product)
    {
        $sql = new Sql();
        $sql->query("DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduct", [
            ":idcategory"=>$this->getidcategory(),
            ":idproduct"=>$product->getidproduct()
        ]);
    }
}
?>