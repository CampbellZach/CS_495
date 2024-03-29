<?php
function connectDb()
{
    $host = 'mysql';
    $database = 'tourdb';
    $username = 'user';
    $password = 'password';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit();
    }
    return $conn;
}

function createNavbar()
{
    echo
        '<header id="navbar">
    <nav class="navbar-container container">
      <a class="home-link">
        <div class="navbar-logo">
          <img src="pictures/logo_white.png" alt="navLogo" weight="65px" height="65px" />
        </div>
        <p> Historic Walking Tour</p>
      </a>
      <button type="button" id="navbar-toggle" aria-controls="navbar-menu" aria-label="Toggle menu"
        aria-expanded="false">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div id="navbar-menu" aria-labelledby="navbar-toggle">
        <ul class="navbar-links">
          <li class="navbar-item"><a class="navbar-link" href="index.php">Home</a></li>
          <li class="navbar-item"><a class="navbar-link" href="tourStops.php">Tours</a></li>
          <li class="navbar-item"><a class="navbar-link" href="about.php">About</a></li>
          <li class="navbar-item"><a class="navbar-link" href="login.php">Login</a></li>
        </ul>
      </div>
    </nav>
  </header>
  
  <script src="index.js"></script>';
}
function createEditorNavbar()
{
    echo
    '<header id="navbar">
    <nav class="navbar-container container">
      <a class="home-link">
        <div class="navbar-logo">
          <img src="pictures/logo_white.png" alt="navLogo" weight="65px" height="65px" />
        </div>
        <p> Historic Walking Tour</p>
      </a>
      <button type="button" id="navbar-toggle" aria-controls="navbar-menu" aria-label="Toggle menu"
        aria-expanded="false">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div id="navbar-menu" aria-labelledby="navbar-toggle">
        <ul class="navbar-links">
          <li class="navbar-item"><a class="navbar-link" href="index.php">Home</a></li>
          <li class="navbar-item"><a class="navbar-link" href="tourStops.php">Tours</a></li>
          <li class="navbar-item"><a class="navbar-link" href="about.php">About</a></li>
          <li class="navbar-item"><a class="navbar-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </nav>
  </header>
  
  <script src="index.js"></script>';
}


function createFooter($data) {
    echo '
      <footer>
        <div class="container">
          <div class="row">
            <div class="col-md-4">
              <img src="pictures/logo.png" alt="Logo" style = "height:200px;"><br><br>
              <a href="about.php">About Us</a><br><br>
              <ul>
                <li>' . $data["address"] . '</li>
                <li>' . $data["city_state_zip"] . '</li>
                <li>Phone: ' . $data["phone_number"] . '</li>
                <li>Email: ' . $data["email"] . '</li>
              </ul>
            </div>
          </div>
        </div>
      </footer>';
  }
  

function getSites($conn)
{
    $sites = array();
    $stmt = $conn->prepare("SELECT * FROM historic_sites");
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $site = array(
            'id' => $row['id'],
            'img1_fname' => $row['img1_fname'],
            'img1_altText' => $row['img1_altText'],
            'img1_caption' => $row['img1_caption'],
            'img2_fname' => $row['img2_fname'],
            'img2_altText' => $row['img2_altText'],
            'img2_caption' => $row['img2_caption'],
            'title' => $row['title'],
            'text1' => $row['text1'],
            'text2' => $row['text2'],
        );
        array_push($sites, $site);
    }
    return $sites;


}

function getHomeData($conn)
{
    $t1q = "SELECT * FROM home";
    $stmt1 = $conn->prepare($t1q);
    $stmt1->execute();
    $data = $stmt1->fetchAll();
    return $data;
}

function getAllTourData($conn)
{
    $t1q = "SELECT * FROM `historic_sites`";
    $stmt = $conn->prepare($t1q);
    $stmt->execute();
    $data = $stmt->fetchAll();
    return $data;
}

function getTourStopById($conn, $getId)
{

    $t1q = "SELECT * FROM `historic_sites` WHERE id = " . $getId;
    $statement = $conn->prepare($t1q);
    $statement->execute();
    $data = $statement->fetchAll();
    return $data;
}

function getAllUsers($conn)
{
    $t1q = "SELECT * FROM users WHERE id > 0";
    $stmt1 = $conn->prepare($t1q);
    $stmt1->execute();
    $data = $stmt1->fetchAll();
    return $data;
}

function getSlideshowPics($conn)
{
    $t1q = "SELECT * FROM slideShowImages";
    $stmt1 = $conn->prepare($t1q);
    $stmt1->execute();
    $slideShowPics = $stmt1->fetchAll();
    return $slideShowPics;
}

function update($conn, $update)
{
    switch ($update) {
        case "site":
            $update_id = $_POST['update_site_id'];
            $img1_altText = $_POST['img1_alt'];
            $img1_caption = $_POST['img1_cap'];

            $img2_altText = $_POST['img2_alt'];
            $img2_caption = $_POST['img2_cap'];

            $title = $_POST['title'];


            $stmt2 = $conn->prepare("UPDATE historic_sites
            SET 
                img1_altText = ?,
                img1_caption = ?,
                img2_altText = ?,
                img2_caption = ?,
                title = ?
                WHERE id= 
            " . $update_id);

            $stmt2->execute([$img1_altText, $img1_caption, $img2_altText, $img2_caption, $title]);


            echo "<script>alert('Updated Successfully!');</script>;";


            break;
        case "add_user":
            $username = $_POST['username'];
            $sql = "SELECT * FROM users WHERE uName = '$username'";

            // Execute the SELECT statement
            $result = $conn->query($sql);

            // Check if the username already exists
            if ($result->num_rows > 0) {
                echo "Username already exists";
            } else {
                $password1 = $_POST['password1'];
                $password2 = $_POST['password2'];
                if ($password1 === $password2) {
                    $password = password_hash($password1, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO users(`uName`, `passwd`) VALUES (:uName, :passwd)";

                    // Prepare the query
                    $stmt = $conn->prepare($sql);

                    // Bind the parameters
                    $stmt->bindParam(':uName', $username);
                    $stmt->bindParam(':passwd', $password);

                    // Execute the query and insert the data into the database
                    if ($stmt->execute()) {
                        // Query executed successfully
                        echo "<script>alert('A new user has been added!');</script>;";

                    } else {
                        // Error executing query
                        echo "<script>alert('ERROR ADDING USER.Please try again.');</script>;";

                    }
                } else {
                    echo "<script>alert('Passwords did not match, Please try again.');</script>;";

                }
            }
            echo "<script>location.href='admin.php';</script>";
            break;
        case "delete_stop":
            $name = $_POST['name'];
            $dID = $_POST['site_id'];
            $stmt = $conn->prepare("SELECT * FROM historic_sites WHERE id = :id");
            $stmt->bindParam(":id", $dID);
            $stmt->execute();
            $data = $stmt->fetch();
            if ($name == $data['title']) {
                $stmt = $conn->prepare("DELETE FROM historic_sites WHERE id = :id");
                $stmt->bindParam(":id", $dID);
                $stmt->execute();
                echo "<script>location.href='admin.php';</script>";


            } else {
                echo "<script>alert('ERROR: Delete from database failed, Please try again.');</script>;";

            }
            break;
        case "delete_user":
            $uid = $_POST['user_id'];
            $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(":id", $uid);
            if ($stmt->execute()) {
                // Query executed successfully
                echo "<script>alert('A user was deleted!');</script>;";
            } else {
                // Error executing query
                echo "<script>alert('ERROR DELETING USER.Please try again.');</script>;";
            }
            echo "<script>location.href='admin.php';</script>";

    }
}
function add_new_site($conn, $img1_altText, $img1_caption, $img2_altText, $img2_caption, $title, $text1, $text2, $img1_fname, $img2_fname)
{

    $sql = "INSERT INTO `historic_sites`(`img1_fname`, `img1_altText`, `img1_caption`, `img2_fname`, `img2_altText`, `img2_caption`, `title`, `text1`, `text2`) 
        VALUES (:img1_fname, :img1_altText, :img1_caption, :img2_fname, :img2_altText, :img2_caption, :title, :text1, :text2)";

    // Prepare the query
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':img1_fname', $img1_fname);
    $stmt->bindParam(':img1_altText', $img1_altText);
    $stmt->bindParam(':img1_caption', $img1_caption);
    $stmt->bindParam(':img2_fname', $img2_fname);
    $stmt->bindParam(':img2_altText', $img2_altText);
    $stmt->bindParam(':img2_caption', $img2_caption);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':text1', $text1);
    $stmt->bindParam(':text2', $text2);

    // Execute the query and insert the data into the database
    if ($stmt->execute()) {
        // Query executed successfully
        echo "<script>alert('New Site has been created!');</script>;";

    } else {
        // Error executing query
        echo "<script>alert('ERROR ADDING NEW SITE.Please try again.');</script>;";

    }
    echo "<script>location.href='admin.php';</script>";
}

function updateImg1($conn, $img1_fname, $site_id)
{
    $query = "UPDATE historic_sites SET img1_fname = '$img1_fname' WHERE id = $site_id";
    if ($stmt1 = $conn->prepare($query)) {
        echo "<script>alert('The First image  was updated Successfully!');</script>;";
        $stmt1->execute();
    } else {
        echo "<script>alert('Database error, please try again.');</script>;";
    }
}
function updateImg2($conn, $img2_fname, $site_id)
{
    $query = "UPDATE historic_sites SET img2_fname = '$img2_fname' WHERE id = $site_id";
    if ($stmt1 = $conn->prepare($query)) {
        echo "<script>alert('The Second image  was updated Successfully!');</script>;";
        $stmt1->execute();
    } else {
        echo "<script>alert('Database error, please try again.');</script>;";
    }
}

function editHomePage($conn, $intro_heading_text, $intro_text, $how_to_text, $address, $city_state_zip, $phone_number, $email)
{
    $query = "UPDATE home SET `intro_heading_text` = :intro_heading_text, 
            `intro_text` = :intro_text, 
            `how_to_text` = :how_to_text,
            `address` = :address, 
            `city_state_zip` = :city_state_zip, 
            `phone_number` = :phone_number, 
            `email` = :email";
    
    
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":intro_heading_text", $intro_heading_text);
        $stmt->bindParam(":intro_text", $intro_text);
        $stmt->bindParam(":how_to_text", $how_to_text);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":city_state_zip", $city_state_zip);
        $stmt->bindParam(":phone_number", $phone_number);
        $stmt->bindParam(":email", $email);
        if ($stmt->execute()) {
          echo "<script>alert('Updated Successfully!');</script>;";
        } else {
          echo "<script>alert('Error Updating, try again.');</script>;";
        }
      echo "<script>location.href='admin.php';</script>";
}
function deleteSlide($conn,$id)
{
    $stmt = $conn->prepare("DELETE FROM slideShowImages WHERE id = :id");
    $stmt->bindParam(":id", $id);
    if ($stmt->execute()) {
        // Query executed successfully
        echo "<script>alert('A image was deleted!');</script>;";
    } else {
        // Error executing query
        echo "<script>alert('ERROR DELETING IMAGE.Please try again.');</script>;";
    }
    echo "<script>location.href='admin.php';</script>";

}
function editText1($conn,$text2,$site_id){
  $query = "UPDATE historic_sites SET text1 = :text2 WHERE id = :site_id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':text2', $text2);
  $stmt->bindParam(':site_id', $site_id);
  if ($stmt->execute()) {
    echo "success";
    echo "<script>location.href='editor.php?function=edit_site_page&site_id=" . $site_id . "';</script>";
  } else {
    echo "<script>alert('Database error, please try again in a moment.');</script>";
  }
}
function editText2($conn,$text2,$site_id){
  $query = "UPDATE historic_sites SET text2 = :text2 WHERE id = :site_id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':text2', $text2);
  $stmt->bindParam(':site_id', $site_id);
  if ($stmt->execute()) {
    echo "success";
    echo "<script>location.href='editor.php?function=edit_site_page&site_id=" . $site_id . "';</script>";
  } else {
    echo "<script>alert('Database error, please try again in a moment.');</script>";
  }
}

?>