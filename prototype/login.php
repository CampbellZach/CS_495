<?php 
session_start();
/*$dns = 'mysql:host=localhost;dbname=cs_350';
$username = 'student';
$p = 'CS350';
$db = new PDO($dns, $username, $p);
*/
$servername = 'localhost';
$username = 'student';
$password = 'CS350';
$dbname = 'tour_db';
$_SESSION['isVerified'] = -1;


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $passwd = $_POST['password'];

  $sql = "SELECT * FROM users WHERE uName = '$username' AND passwd = '$passwd'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $users = array();
    while($row = $result->fetch_assoc()){
        $user = array(
          'id' => $row['id'],
          'name' => $row['uName'],
        );
        array_push($users,$user);
      }
    // Username and password match
    $id = $sites['id'];
    session_regenerate_id();
    $_SESSION['isVerified'] = 1;
    $_SESSION['name'] = $username;
    $_SESSION['id'] = $id;
    echo "<script>location.href='admin.php';</script>";
  } else {
    // Username and password do not match
    echo "Invalid username or password.";
  }
}

/*
if($_SERVER["REQUEST_METHOD"] == "POST"){    
    $username = $_POST['username'];
    $password = $_POST['password'];
  
    $sql = "SELECT * FROM users WHERE uName = :username AND passwd = :password";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Username and password match
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row['id'];
        $passwd = $row['passwd'];
        if($password === $passwd){
            session_regenerate_id();
            $_SESSION['isVerified'] = 1;
            $_SESSION['name'] = $username;
            $_SESSION['id'] = $id;
            echo "<script>location.href='admin.php';</script>";
        }
    } else {
        // Username and password do not match
        echo "Invalid username or password.";
    }
}*/
?>

<html>
<head> 

</head>
<body>
    <h1>Login</h1>

    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>
