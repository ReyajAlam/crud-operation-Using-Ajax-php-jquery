<?php
class User {
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "class";
    private $conn;
    public function __construct() {
        $this->conn = mysqli_connect($this->server, $this->username, $this->password, $this->db);
        if (!$this->conn) {
            echo "Cannot connect to database: " . mysqli_connect_error();
        }else{
            echo "Connected successfully";
        }
    }
    public function create($name, $email, $password, $username, $image, $country, $state) {
        $passwrd = password_hash($password, PASSWORD_DEFAULT);
        
        $e_query = "SELECT id FROM users WHERE email = '$email'";
        $result_query = mysqli_query($this->conn, $e_query);
        
        if (mysqli_num_rows($result_query) > 0) {
            echo "Error: This email is already registered!<br>";
            return false;
        }

        $query = "INSERT INTO users (name, email, password, username, image, country, state) 
                 VALUES ('$name', '$email', '$passwrd', '$username', '$image', '$country', '$state')";
        
        if (mysqli_query($this->conn, $query)) {
            return true;
        }
        return false;
    }
    public function show() {
        $query = "SELECT * FROM users";
        $result = mysqli_query($this->conn, $query);
        $users = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        return $users;
    }
    public function readById($id) {
        $query = "SELECT * FROM users WHERE id = $id";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }
    public function update($id, $name, $email, $username, $image, $country, $state) {
        $query = "UPDATE users SET 
        name = '$name', 
        email = '$email', 
        username = '$username', 
        image = '$image', 
        country = '$country',
        state = '$state' WHERE id = $id";
        if (mysqli_query($this->conn, $query)) {
            return true;
        }
        return false;
    }
    public function delete($id) {
        $query = "DELETE FROM users WHERE id = $id";
        if (mysqli_query($this->conn, $query)) {
            return true;
        }
        return false;
    }
    public function login($username, $password) {
        $query = "SELECT id, password FROM users WHERE username = '$username'";
        $result = mysqli_query($this->conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                return true;
            }
        }
        return false;
    }
}
?>