<?php
require_once("Db.php");
require_once("Security.php");
class User{
    private $username;
    private $email;
    private $password;
    private $passwordConfirmation;
    private $firstname;
    private $lastname;
    private $image;
    private $description;
    /**
     * @return username
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /**
     * @return email
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    /**
     * @return password
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * @return passwordConfirmation
     */
    public function getPasswordConfirmation()
    {
        return $this->passwordConfirmation;
    }
    /**
     * @param $passwordConfirmation
     */
    public function setPasswordConfirmation($passwordConfirmation)
    {
        $this->passwordConfirmation = $passwordConfirmation;
    }
    /**
     * @return firstname
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    /**
     * @param $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }
    /**
     * @return lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    /**
     * @param $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
    /**
     * @return image
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @param $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
    /**
     * @return description (bio)
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * @return boolean - true if successful, false if unsuccessful
     */
    public function register(){
        $hash = Security::hash($this->password);
        try{
            $pdo = Db::getConnection();
            $statement = $pdo->prepare("insert into user (firstname, lastname, username, email, password) values (:firstname,:lastname,:username,:email,:password)");
            $statement->bindParam(":firstname", $this->firstname);
            $statement->bindParam(":lastname", $this->lastname);
            $statement->bindParam(':username', $this->username);
            $statement->bindParam(":email", $this->email);
            $statement->bindParam(":password", $hash);
            $result = $statement->execute();
            return $result;
        }
        catch( Throwable $t){
            $err = $t->getMessage();
            //Write this error to errorLog.txt file
            $file = fopen("errorLog.txt", "a");
            fwrite($file, $err."\n");
            fclose($file);
        }
    }
    /*
    * Returns true if length of a string is longer than given allowedLength
    */
    public static function maxLength($string, $maxLength){
        if( strlen($string) > $maxLength){
            //String is too long, return true for error handling
            return true;
        }
        else{
            return false;
        }
    }
    /*
    * Returns true if length of a string is shorter than given allowedLength
    */
    public static function minLength($string, $minLength){
        if( strlen($string) < $minLength){
            //String is too short, return true for error handling
            return true;
        }
        else{
            return false;
        }
    }
    /*
    * Find a user based on email addres
    */
    public static function findByEmail($email){
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from user where email = :email limit 1");
        $statement->bindParam(":email", $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    //Check if a user exists by email address
    public static function isEmailAvailable($email){
        $result = self::findByEmail($email);
        // PDO returns false if no records are found so let's check for that
        if($result == false){
            return true;
        } else {
            return false;
        }
    }
    /*
    * Find a user based on username
    */
    public static function findByUsername($username){
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from user where username = :username limit 1");
        $statement->bindParam(":username", $username);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    //Check if a user exists by username
    public static function isUsernameAvailable($username){
        $result = self::findByUsername($username);
        // PDO returns false if no records are found so let's check for that
        if($result == false){
            return true;
        } else {
            return false;
        }
    }
    public static function getUserId(){
        //Get email of loggedin user via session
        $sessionEmail = $_SESSION['email'];
        //Get the ID of current user
        $conn = Db::getConnection();
        $statement = $conn->prepare("select id from user where email = :sessionEmail");
        $statement->bindParam(":sessionEmail", $sessionEmail);
        $statement->execute();
        $user_id = $statement->fetch(PDO::FETCH_ASSOC);
        $user_id = $user_id['id'];
        return $user_id;
    }
}