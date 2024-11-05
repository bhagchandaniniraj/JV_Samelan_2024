<?php
class UserAuthentication {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "samelan_db");

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function verifyUser($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (strcmp(md5($password), $user['password']) === 0) {
                return true; // Password verified
            }else{
                $_SESSION['message'] = "Wrong Username and Password";
                echo "Something went Wrong ".md5($password)." - ". $user['password'];
            }
        }

        return false; // Invalid credentials
    }
}