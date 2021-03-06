<?php

include_once ('Db.class.php');

class User{
    private $email;
    private $password;
    private $firstname;
    private $lastname;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        if(strlen($password)<8){
            throw new exception("Password must be at least 8 characters long.");
        } else {
            $this->password = $password;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    private function hashPassword(){
        $hash = password_hash($this->password,PASSWORD_BCRYPT);
        return $hash;
    }

    public function register(){
        $conn = Db::getInstance();

        $statement = $conn->prepare("insert into users(email,password,firstname,lastname) values (:email,:password,:firstname,:lastname)");
        $statement->bindParam(':email',$this->email);
        $statement->bindValue(':password',$this->hashPassword());
        $statement->bindParam(':firstname',$this->firstname);
        $statement->bindParam(':lastname',$this->lastname);
        $result = $statement->execute();
        return $result;

    }

    public function login(){
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['user']= $this->email;
        $this->getid();
        header('Location: index.php');
    }

    public function canLogin()
    {
        $password = $this->getPassword();
        $conn=Db::getInstance();
        $q = "select * from users where email = :email";
        $statement = $conn->prepare($q);
        $statement->bindParam(":email",$this->email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $hash = $user['password'];
        if ( password_verify($password, $hash)) {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function checkpass()
    {
        $password = $this->getPassword();
        $conn=Db::getInstance();
        $q = "select * from users where email = :email";
        $statement = $conn->prepare($q);
        $statement->bindParam(":email",$_SESSION['user']);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $hash = $user['password'];
        if ( password_verify($password, $hash)) {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function getid(){
        $conn = Db::getInstance();
        $q="SELECT id FROM users where email = :email";
        $statement = $conn->prepare($q);
        $statement->bindParam(":email",$_SESSION['user']);
        $statement->execute();
        $result = $statement->fetch();
        $res = $result['id'];
        $_SESSION['userid']=$res;
    }

}