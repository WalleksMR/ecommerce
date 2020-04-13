<?php 
    namespace hcode\Model;
    use \hcode\DB\Sql;
    use \hcode\Model;

class Product extends Model {

    public static function ListAll(){
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
    }

    public function save()
    {
        $sql = new Sql();
        $resuts = $sql->select("CALL sp_products_save (:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
            ":idproduct"=>$this->getidproduct(),
            ":desproduct"=>$this->getdesproduct(),
            ":vlprice"=>$this->getvlprice(),
            ":vlwidth"=>$this->getvlwidth(),
            ":vlheight"=>$this->getvlheight(),
            ":vllength"=>$this->getvllength(),
            ":vlweight"=>$this->getvlweight(),
            ":desurl"=>$this->getdesurl()
        ));
        $this->setData($resuts[0]);
    }

    public function get($idproduct){
        $sql = new Sql();
        $resuts = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", array(
            ":idproduct"=>$idproduct
        ));
        $data = $resuts[0];
        $this->setData($data);
    }

    public function delete()
    {
        $user = new Sql();
        $user->query("DELETE FROM tb_products WHERE idproduct = :idproduct",array(
            ":idproduct"=>$this->getidproduct()
        ));
    }
}
?>