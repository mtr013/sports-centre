<?php
require_once("db_connect.php");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simple validation
    $mem_id = $_POST['mem_id'] ?? '';
    $sport_id = $_POST['sport_id'] ?? '';

    if ($mem_id === "" || $sport_id === "") {
        $error = "All fields must be filled out.";
    } elseif (!is_numeric($mem_id) || !is_numeric($sport_id)) {
        $error = "IDs must be numeric.";
    } else {
        $conn = connect();
        $sql = "INSERT INTO Booking (Sport_ID, Mem_ID) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $sport_id, $mem_id);
        if ($stmt->execute()) {
            $success = "Booking successfully added to your account.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Make a Booking</title>
  <link rel="stylesheet" href="../css/style.css">
  <script>
    function validateBookForm() {
      let x = document.forms["myForm"]["mem_id"].value;
      let y = document.forms["myForm"]["sport_id"].value;
      if (x == "" || y == "") {
        alert("All fields must be filled out");
        return false;
      }
      if (isNaN(x) || isNaN(y)) {
        alert("IDs must be numeric");
        return false;
      }
      return true;
    }
  </script>
</head>
<body>
  <header class="header" title="Navigation">
    <ul>
      <li><a href="./index.php" title="Home">HOME</a></li>
      <li><a href="./join.php" title="Join">JOIN</a></li>
      <li><a href="./book.php" title="Book">BOOK</a></li>
    </ul>
  </header>
  <h2>Make a Booking</h2>
  <?php
    if ($success) echo "<p style='color:green;'>$success</p>";
    if ($error) echo "<p style='color:red;'>$error</p>";
  ?>
  <form name="myForm" action="book.php" onsubmit="return validateBookForm()" method="post">
    Member ID:<br>
    <input type="text" name="mem_id" value="<?= htmlspecialchars($_POST['mem_id'] ?? '') ?>"><br><br>
    Sports:<br>
    <p>
      00: Badminton<br>
      01: Squash<br>
      02: Swimming<br>
      03: GYM<br>
      04: Yoga<br>
      05: Climbing<br>
      06: Football<br>
      07: Basketball<br>
      08: Hockey<br>
      09: Tennis<br>
    </p>
    <input type="text" class="bold" name="sport_id" value="<?= htmlspecialchars($_POST['sport_id'] ?? '') ?>"><br><br>
    <input type="submit" name="join" value="Book"><br><br>
    <a href="./join.php" title="Home">Not a Member Yet?</a>
  </form>
  <hr>
  <footer class="footer" title="Contacts">
    Address & Contacts:<br>
    Greenfield Sports Centre, 123 Park Lane, Greenfield, GF1 2AB Tel. 01234 567890
  </footer>
</body>
</html>
