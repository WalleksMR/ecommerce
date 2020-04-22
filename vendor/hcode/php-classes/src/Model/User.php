<?php 
    namespace hcode\Model;

    use \hcode\DB\Sql;
    use \hcode\Mailer;
    use \hcode\Model;

class User extends Model {
    const SESSION = "User";
    const SECRET = "HcodePhp7_Secret";
    const cipher = "AES-128-ECB";

    public static function getFromSession()
    {
        $user = new User();
        if(isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0){
            $user->setData($_SESSION[User::SESSION]);
        }
        return $user;
    }

    public static function checkLogin($inadmin = true)
    {
        if(
            !isset($_SESSION[User::SESSION])
            ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]["iduser"] > 0
        ){
            // Nao esta logado
            return false;
        }else{
            if($inadmin === true && (bool)$_SESSION[User::SESSION]['inadmin'] === true){
                return true;
            }else if($inadmin === false){
                return true;
            }else{
                return false;
            }
        }
    }

    public static function login($login,$password){
        $sql = new Sql();
        $result = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
            ":LOGIN"=>$login
        ));
        if (count($result) === 0){
            throw new \Exception("Usuarios ou Senha invalida.");
            
        }
        
        $data = $result[0];

        if(password_verify($password, $data["despassword"]) === true) {
            $user = new User();
            $user->setData($data);
            $_SESSION[User::SESSION] = $user->getValues();

            return $user;
        }else{
            throw new \Exception("Usuarios ou Senha invalida.");
        }

    }

    public static function verifyLoginAdmin($inadmin = true) {
        if(!User::checkLogin($inadmin)){
            if($inadmin){
                header("Location: /admin/login");
            }else{
                header("Location:/login");
            }
            exit;
        }
    }
    public static function logout() {
        $_SESSION[User::SESSION] = NULL;
    }

    public static function ListAll(){
        $sql = new Sql();
        return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
    }

    public function save()
    {
        $sql = new Sql();
        $resuts = $sql->select("CALL sp_users_save(:pdesperson, :pdeslogin, :pdespassword, :pdesemail, :pnrphon, :pinadmin)", array(
            ":pdesperson"=>$this->getdesperson(),
            ":pdeslogin"=>$this->getdeslogin(),
            ":pdespassword"=>$this->getdespassword(),
            ":pdesemail"=>$this->getdesemail(),
            ":pnrphon"=>$this->getnrphone(),
            ":pinadmin"=>$this->getinadmin()
        ));
        $this->setData($resuts[0]);
    }

    public function get($iduser){
        $sql = new Sql();
        $resuts = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
            ":iduser"=>$iduser
        ));
        $data = $resuts[0];
        $this->setData($data);
    }

    public function update()
    {
        $sql = new Sql();
        $resuts = $sql->select("CALL sp_usersupdate_save(:piduser, :pdesperson, :pdeslogin, :pdespassword, :pdesemail, :pnrphon, :pinadmin)", array(
            ":piduser"=>$this->getiduser(),
            ":pdesperson"=>$this->getdesperson(),
            ":pdeslogin"=>$this->getdeslogin(),
            ":pdespassword"=>$this->getdespassword(),
            ":pdesemail"=>$this->getdesemail(),
            ":pnrphon"=>$this->getnrphone(),
            ":pinadmin"=>$this->getinadmin()
        ));
        $this->setData($resuts[0]);
    }

    public function delete()
    {
        $user = new Sql();
        $user->query("CALL sp_users_delete(:iduser)",array(
            ":iduser"=>$this->getiduser()
        ));
    }

    public static function getForgot($email)
    {
        $user = new Sql();
        $resuts = $user->select("SELECT * FROM tb_persons a INNER JOIN tb_users b USING(idperson) WHERE a.desemail = :email", array(
            ":email"=>$email
        ));
         if(count($resuts) === 0) {
             throw new \Exception("Não foi possivel recuperar a senha!");
             
         }else{
             $data = $resuts[0];

             $results2 = $user->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
                 ":iduser"=>$data["iduser"],
                 ":desip"=>$_SERVER["REMOTE_ADDR"]
             ));

             if(count($results2) === 0 ){
                 throw new \Exception("Não foi possivel recuperar a senha!");
                 
             }else{
                 $dataRecovery = $results2[0];
                 $code = base64_encode(openssl_encrypt($dataRecovery["idrecovery"],User::cipher, User::SECRET));
                 $link = "http://www.hcodecommerce.com.br/admin/forgot/reset?code=$code";
                 $mailer = new Mailer($data["desemail"], $data["desperson"], "Redefinir Senha Hcode Store", "forgot", array(
                     "name"=>$data["desperson"],
                     "link"=>$link
                 ));

                 $mailer->send();
                 return $data;
             }
         }
    }

    public static function validForgotDecrypt($code)
    {
       $idRecovery = openssl_decrypt(base64_decode($code),User::cipher, User::SECRET);
       $user = new Sql();
       $results = $user->select("SELECT * FROM tb_userspasswordsrecoveries a 
       INNER JOIN tb_users b USING(iduser)
       INNER JOIN tb_persons c USING(idperson)
       WHERE
            a.idrecovery = :idrecovery
            AND
            a.dtrecovery IS NULL
            AND
            DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW()", array(
                ":idrecovery"=>$idRecovery
            ));

            if(count($results) === 0){
                throw new \Exception("Não foi possivel recuperar a Senha!");
            }else{
                return $results[0];
            }
    }

    public static function setForgotUsed($idRecovery){
        $sql = new Sql();
        $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
            ":idrecovery"=>$idRecovery
        ));
    }

    public function setPassword($password){
        $sql = new Sql();
        $sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser", array(
            "password"=>$password,
            ":iduser"=>$this->getiduser()
        ));
    }
}
?>