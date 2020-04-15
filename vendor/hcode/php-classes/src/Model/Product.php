<?php 
    namespace hcode\Model;
    use \hcode\DB\Sql;
    use \hcode\Model;

class Product extends Model {

    public static function ListAll(){
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");
    }

    public static function checkList($list)
    {
        foreach ($list as &$row) {
            $p = new Product();
            $p->setData($row);
            $row = $p->getValues();
        }
        return $list;
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

    public function checkPhoto()
    {
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
        "res" . DIRECTORY_SEPARATOR .
        "site" . DIRECTORY_SEPARATOR .
        "img" . DIRECTORY_SEPARATOR .
        "products" . DIRECTORY_SEPARATOR.
        $this->getidproduct() . ".jpg")){
            $url = "/res/site/img/products/".$this->getidproduct().".jpg";
        }else{
            $url = "/res/site/img/product.jpg";
        }
       return $this->setdesphoto($url);
    }

    public function getValues()
    {
        $this->checkPhoto();
        $value = parent::getValues();
        return $value;
    }

    public function setPhoto($photo)
    {
        $extension = explode('.', $photo["name"]);
        $extension = end($extension);

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            $image = imagecreatefromjpeg($photo["tmp_name"]);
            break;

            case "png":
                $image = imagecreatefrompng($photo["tmp_name"]);
            break;

            case "gif":
                $image = imagecreatefromgif($photo["tmp_name"]);
            break;
        }

        $dist = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
        "res" . DIRECTORY_SEPARATOR .
        "site" . DIRECTORY_SEPARATOR .
        "img" . DIRECTORY_SEPARATOR .
        "products" . DIRECTORY_SEPARATOR.
        $this->getidproduct() . ".jpg";

        imagejpeg($image, $dist);
        imagedestroy($image);

        $this->checkPhoto();

    }
}
?>