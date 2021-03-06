<?php
require_once("Db.php");
require_once("Security.php");

class User
{
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
        //Check if not empty
        if( empty($username) ){
            throw new Exception("Username cannot be empty.");
        }

        //Check if username is not longer than 30chars
        if( User::maxLength($username, 30)){
            throw new Exception("Username cannot be longer than 30 characters.");
        }

        //Check if username is not in our DB yet
        if( !User::isUsernameAvailable($username) ){
            throw new Exception("This username is already registered.");
        }
        
        //username not too long, set username
        $this->username = $username;
        return $this;
        
        
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
        //Check if not empty
        if( empty($email) ){
            throw new Exception("Email cannot be empty.");
        }

        //Check if email is legit
        if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            throw new Exception("Please use a valid email address!");
        }
        //Check if email is not in our DB yet
        if( !User::isEmailAvailable($email) ){
            throw new Exception("A user with this email address is already registered.");
        }

        $this->email = $email;
        return $this;
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

        //Check if not empty
        if( empty($password) ){
            throw new Exception("Password cannot be empty.");
        }

        //Check if password has minimum length of 8 chars
        if( User::minLength($password, 8)){
            throw new Exception("Password must be minimum 8 chars long.");
        }
        
        $this->password = $password;
        return $this;
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
        //Check if not empty
        if( empty($passwordConfirmation) ){
            throw new Exception("Password Confirmation cannot be empty.");
        }

        //Do passwords equal?
        if( $this->getPassword() !== $passwordConfirmation){
            //Passwords do not equal, throw exception
            throw new Exception("Password fields are not equal, please enter them again");
        }

        $this->passwordConfirmation = $passwordConfirmation;
        return $this;
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
        //Check if not empty
        if( empty($firstname) ){
            throw new Exception("Firstname cannot be empty.");
        }

        //Check if firstname is not longer than 30chars
        if( User::maxLength($firstname, 30)){
            throw new Exception("Firstname cannot be longer than 30 characters.");
        }

        $this->firstname = $firstname;
        return $this;
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
        //Check if not empty
        if( empty($lastname) ){
            throw new Exception("Lastname cannot be empty.");
        }

        //Check if lastname is not longer than 30chars
        if( User::maxLength($lastname, 30)){
            throw new Exception("Lastname cannot be longer than 30 characters.");
        }

        $this->lastname = $lastname;
        return $this;
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
    public function register()
    {

        $hash = Security::hash($this->password);

        try {
            $pdo = Db::getConnection();
            $statement = $pdo->prepare("insert into user (firstname, lastname, username, email, password) values (:firstname,:lastname,:username,:email,:password)");
            $statement->bindParam(":firstname", $this->firstname);
            $statement->bindParam(":lastname", $this->lastname);
            $statement->bindParam(':username', $this->username);
            $statement->bindParam(":email", $this->email);
            $statement->bindParam(":password", $hash);
            $result = $statement->execute();
            return $result;
        } catch (Throwable $t) {
            $err = $t->getMessage();

            //Write this error to errorLog.txt file
            $file = fopen("errorLog.txt", "a");
            fwrite($file, $err . "\n");
            fclose($file);
        }

    }

    public function firstLogin(){
        //session start is already done in bootstrap!
        //set email session
        $_SESSION['email'] = $this->getEmail();

        $user_id = User::getUserId($this->getEmail() );
        //follow yourself so you see your own posts on the feed
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into followers (user_id, following_id) values (:user_id, :following_id)");
        $statement->bindParam(":user_id", $user_id);
        $statement->bindParam(":following_id", $user_id);
        $statement->execute();

        //Redirect to index
        header("location: index.php");
    }

    /*
    * Returns true if length of a string is longer than given allowedLength
    */
    public static function maxLength($string, $maxLength)
    {
        if (strlen($string) > $maxLength) {
            //String is too long, return true for error handling
            return true;
        } else {
            return false;
        }
    }

    /*
    * Returns true if length of a string is shorter than given allowedLength
    */
    public static function minLength($string, $minLength)
    {
        if (strlen($string) < $minLength) {
            //String is too short, return true for error handling
            return true;
        } else {
            return false;
        }
    }

    /*
    * Find a user based on email addres
    */
    public static function findByEmail($email)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from user where email = :email limit 1");
        $statement->bindParam(":email", $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //Check if a user exists by email address
    public static function isEmailAvailable($email)
    {
        $result = self::findByEmail($email);

        // PDO returns false if no records are found so let's check for that
        if ($result == false) {
            return true;
        } else {
            return false;
        }
    }

    /*
    * Find a user based on username
    */
    public static function findByUsername($username)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from user where username = :username limit 1");
        $statement->bindParam(":username", $username);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //Check if a user exists by username
    public static function isUsernameAvailable($username)
    {
        $result = self::findByUsername($username);

        // PDO returns false if no records are found so let's check for that
        if ($result == false) {
            return true;
        } else {
            return false;
        }
    }

    public static function getUserId()
    {
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


    public static function userHasReported($postId, $userId) {
        $conn = Db::getConnection();
        $statementCheck = $conn->prepare("select count(*) as count from inappropriate where post_id = :postId AND user_id = :userId AND inappropriate = '1'");
        $statementCheck->bindParam(":postId", $postId);
        $statementCheck->bindParam(":userId", $userId);
        $statementCheck->execute();
        $result = $statementCheck->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] == 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function getSessionEmail()
    {
        return $_SESSION['email'];
    }

    public static function doChangePassword($newPassword)
    {
        $conn = Db::getConnection();
        $sessionEmail = self::getSessionEmail();
        $hashNewPassword = Security::hash($newPassword);

        // UPDATE new data
        $updateStatement = $conn->prepare("update user set password= :newPassword where email = :email");
        $updateStatement->bindParam(":email", $sessionEmail);
        $updateStatement->bindParam(":newPassword", $hashNewPassword);
        $updateStatement->execute();
    }

    public static function checkIfNewUsername($username, $currentUsername)
    {
        if (empty($username)) {
            return false;
        } else if ($username == $currentUsername){
            return false;
        } else{
            if (User::isUsernameAvailable($username) != true) {
                throw new Exception("Username is not available");
            } else {
                return true;
            }
        }
    }

    public static function canChangePassword($oldPassword, $newPassword, $confirmNewPassword)
    {
        $sessionEmail = self::getSessionEmail();
        $user = self::findByEmail($sessionEmail);

        // CHECK password to change data
        # compare current password from input to database password
        if (password_verify($oldPassword, $user['password'])) {

            # check if newPassword is filled in // change to !empty?
            if (isset($newPassword)) {

                # check if newPassword is strong enough and is the same as confirmNewPassword
                if ((strlen($newPassword) >= 8) && $newPassword == $confirmNewPassword) {
                    return true;

                } else {
                    # check why the newPassword is not accepted
                    if ((strlen($newPassword) >= 8) == false) {
                        throw new Exception("New password is not strong/long enough");
                    } else {
                        throw new Exception("New password does not match the confirmation password");
                    }
                }
            } else {
                throw new Exception("Please fill in a new password");
            }
        } else {
            throw new Exception("Wrong password");
        }
    }

    public static function getCurrentUserProfile(){
        $conn = Db::getConnection();
        $email = $_SESSION['email'];
        $statement = $conn->prepare("select * from user where email = :email");
        $statement->bindParam(":email", $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function doChangeProfile($email, $password)
    {
        $sessionEmail = self::getSessionEmail();
        $userProfile = self::findByEmail($sessionEmail);
        $conn = Db::getConnection();

        if (password_verify($password, $userProfile['password'])) {

            # check if email has changed
            if ($email != $userProfile['email']) {

                # check if new email is available and is a valid email address
                if (User::isEmailAvailable($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    # echo !empty($_FILES['image']['size'] == 0) ? 'true' : 'false';
                    # https://stackoverflow.com/questions/14458553/check-if-specific-input-file-is-empty/14458594#14458594
                    # check if a new image has been uploaded - size has to be bigger than 0
                    if ($_FILES['image']['size'] != 0) {

                        # save new Email + image + description

                        $image = $_FILES['image']['name'];
                        $description = $_POST['description'];
                        $username = $_POST['username'];

                        if (self::checkIfNewUsername($username, $userProfile['username']) == false) {
                            $username = $userProfile['username'];
                        }

                        # update the database
                        $updateStatement = $conn->prepare("UPDATE user set description=:newDescription,username = :username, image=:image, email=:newEmail where email=:sessionEmail");
                        $updateStatement->bindParam(":newDescription", $description);
                        $updateStatement->bindParam(":image", $image);
                        $updateStatement->bindParam(":username", $username);
                        $updateStatement->bindParam(":newEmail", $email);
                        $updateStatement->bindParam(":sessionEmail", $sessionEmail);
                        $updateStatement->execute();

                        # change session email value
                        $_SESSION['email'] = $email;

                        // image file directory
                        $target = "images/profilePictures/" . $userProfile['id'] . basename($image);

                        // move file
                        move_uploaded_file($_FILES['image']['tmp_name'], $target);

                        return true;

                    } else {
                        # no new image
                        # save new email + description

                        $description = $_POST['description'];
                        $email = $_POST['email'];
                        $username = $_POST['username'];

                        if (self::checkIfNewUsername($username, $userProfile['username']) == false) {
                            $username = $userProfile['username'];
                        }

                        $updateStatement = $conn->prepare("UPDATE user set description= :newDescription, username = :username, email = :newEmail where email = :sessionEmail");
                        $updateStatement->bindParam(":newEmail", $email);
                        $updateStatement->bindParam(":newDescription", $description);
                        $updateStatement->bindParam(":username", $username);
                        $updateStatement->bindParam(":sessionEmail", $sessionEmail);
                        $updateStatement->execute();

                        # change session email value
                        $_SESSION['email'] = $email;

                        return true;


                    }

                } else {
                    if (empty($email)) {
                        throw new Exception("Please fill in an email address");

                    } else if (User::isEmailAvailable($email) == false) {
                        throw new Exception("This email is not available");

                    } else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
                        throw new Exception("Please use a valid email address");
                    } else {
                        throw new Exception("Something went wrong");
                    }
                }

            } else {
                # no new email address
                # check if a new image has been uploaded

                if ($_FILES['image']['size'] != 0) {
                    # save new image
                    # save description - no need to check, description can be empty

                    $image = $_FILES['image']['name'];
                    $description = $_POST['description'];
                    $username = $_POST['username'];

                    if (self::checkIfNewUsername($username, $userProfile['username']) == false) {
                        $username = $userProfile['username'];
                    }

                    $updateStatement = $conn->prepare("UPDATE user set description=:newDescription, username = :username, image=:image where email=:sessionEmail");
                    $updateStatement->bindParam(":newDescription", $description);
                    $updateStatement->bindParam(":sessionEmail", $sessionEmail);
                    $updateStatement->bindParam(":username", $username);
                    $updateStatement->bindParam(":image", $image);
                    $updateStatement->execute();

                    # sla images lokaal op
                    // image file directory
                    $target = "images/profilePictures/" . $userProfile['id'] . basename($image);

                    // move file
                    move_uploaded_file($_FILES['image']['tmp_name'], $target);

                    return true;

                } else {
                    # no new image
                    #save description

                    $description = $_POST['description'];
                    $username = $_POST['username'];

                    if (self::checkIfNewUsername($username, $userProfile['username']) == false) {
                        $username = $userProfile['username'];
                    }

                    $updateStatement = $conn->prepare("UPDATE user set description= :newDescription, username = :username where email = :sessionEmail");
                    $updateStatement->bindParam(":newDescription", $description);
                    $updateStatement->bindParam(":sessionEmail", $sessionEmail);
                    $updateStatement->bindParam(":username", $username);
                    $updateStatement->execute();

                    return true;
                }

            }

        } else {
            throw new Exception("Your password is incorrect. Please try again.");

        }
    }
    public static function canLogin($email, $password){
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from user where email = :email");
        $statement->bindParam(":email", $email); # the email parameter is bound to :email to prevent sql-injection
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);


        if (password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function doLogin($email){
        $_SESSION['email'] = $email;
        header("location: index.php");
    }

    // check if the user is logged in
    public static function userLoggedIn() {
        if( isset($_SESSION['email']) ){ /* User is logged in, no redirect needed! */ }
        else{ /* User is not logged in, redirect to login.php! */ header("location: login.php"); }
    }

    /*
    * Get all posts of a specific user by his user_id
    */
    public static function getUserPosts($user_id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from photo where user_id = :user_id AND enable = 0");
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }


    public static function getFollowinghashtags($user_id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select hashtag from hashtag where user_id = :user_id");
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $sqlHashtag = "";

        foreach($results as $result) {
            $sqlHashtag .= $result['hashtag'] . "|" ;
        }

        $sqlHashtag = rtrim($sqlHashtag, '|');


        return $sqlHashtag;
    }



}
