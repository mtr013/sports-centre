<?php
require_once("db_connect.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateJoinForm() {
    $errors = [];
    $data = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // First Name
        if (empty($_POST["firstname"])) {
            $errors['firstname'] = "First Name is required";
        } else {
            $data['firstname'] = test_input($_POST["firstname"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $data['firstname'])) {
                $errors['firstname'] = "Only letters and white space allowed";
            }
        }

        // Last Name
        if (empty($_POST["lastname"])) {
            $errors['lastname'] = "Last Name is required";
        } else {
            $data['lastname'] = test_input($_POST["lastname"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $data['lastname'])) {
                $errors['lastname'] = "Only letters and white space allowed";
            }
        }

        // Date of Birth
        if (empty($_POST["dateofbirth"])) {
            $errors['dateofbirth'] = "Date of Birth is required";
        } else {
            $data['dateofbirth'] = test_input($_POST["dateofbirth"]);
            // Accepts YYYY-MM-DD (from <input type="date">)
            if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $data['dateofbirth'])) {
                $errors['dateofbirth'] = "Invalid date format";
            }
        }

        // Email
        if (empty($_POST["email"])) {
            $errors['email'] = "Email is required";
        } else {
            $data['email'] = test_input($_POST["email"]);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format";
            }
        }

        // Phone
        if (empty($_POST["phone"])) {
            $errors['phone'] = "Phone is required";
        } else {
            $data['phone'] = test_input($_POST["phone"]);
            if (!preg_match("/^[0-9]{11}$/", $data['phone'])) {
                $errors['phone'] = "11 digit number required";
            }
        }

        // Gender
        if (empty($_POST["gender"])) {
            $errors['gender'] = "Gender is required";
        } else {
            $data['gender'] = test_input($_POST["gender"]);
        }

        // Address
        if (empty($_POST["address"])) {
            $errors['address'] = "Address is required";
        } else {
            $data['address'] = test_input($_POST["address"]);
        }

        // Membership
        if (empty($_POST["membership"])) {
            $errors['membership'] = "Membership type is required";
        } else {
            $data['membership'] = test_input($_POST["membership"]);
        }
    }

    return [$errors, $data];
}

function join($data) {
    $conn = connect();
    $sql = "INSERT INTO Member (Mem_FName, Mem_Lname, Mem_Address, Mem_Gender, Mem_DOB, Mem_Email, Mem_Phone, Membership_Type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssss",
        $data['firstname'],
        $data['lastname'],
        $data['address'],
        $data['gender'],
        $data['dateofbirth'],
        $data['email'],
        $data['phone'],
        $data['membership']
    );
    $result = false;
    if ($stmt->execute()) {
        $result = true;
    }
    $stmt->close();
    $conn->close();
    return $result;
}

$errors = [];
$success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    list($errors, $data) = validateJoinForm();
    if (empty($errors)) {
        if (join($data)) {
            $success = "Your account is created.";
            // Optionally clear POST data for a fresh form
            $_POST = [];
        } else {
            $errors['general'] = "There was a problem creating your account. Please try again.";
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>Become a Member</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <header class="header" title="Navigation">
    <ul>
      <li><a href="./index.php" title="Home">HOME</a></li>
      <li><a href="./join.php" title="Join">JOIN</a></li>
      <li><a href="./book.php" title="Book">BOOK</a></li>
    </ul>
  </header>
  <h2>Become a Member</h2>
  <?php
    if ($success) echo "<p style='color:green;'>$success</p>";
    foreach ($errors as $error) echo "<p style='color:red;'>$error</p>";
  ?>
  <form action="join.php" method="post">
    First Name:<br>
    <input type="text" name="firstname" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>"><br><br>
    Last Name:<br>
    <input type="text" name="lastname" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>"><br><br>
    Date of Birth:<br>
    <input type="date" name="dateofbirth" value="<?= htmlspecialchars($_POST['dateofbirth'] ?? '') ?>"><br><br>
    Gender:<br>
    <input type="radio" name="gender" value="female" <?= (($_POST['gender'] ?? '') == 'female') ? 'checked' : '' ?>>Female
    <input type="radio" name="gender" value="male" <?= (($_POST['gender'] ?? '') == 'male') ? 'checked' : '' ?>>Male
    <input type="radio" name="gender" value="other" <?= (($_POST['gender'] ?? '') == 'other') ? 'checked' : '' ?>>Other<br><br>
    Membership Type:<br>
    <input type="radio" name="membership" value="Gold" <?= (($_POST['membership'] ?? '') == 'Gold') ? 'checked' : '' ?>>Gold
    <input type="radio" name="membership" value="Silver" <?= (($_POST['membership'] ?? '') == 'Silver') ? 'checked' : '' ?>>Silver
    <input type="radio" name="membership" value="Team" <?= (($_POST['membership'] ?? '') == 'Team') ? 'checked' : '' ?>>Team<br><br>
    Address:<br>
    <input type="text" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"><br><br>
    Email:<br>
    <input type="text" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"><br><br>
    Phone:<br>
    <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"><br><br>
    <input type="submit" value="Join">
  </form>
  <hr>
  <footer class="footer" title="Contacts">
   Address & Contacts:<br>
    Greenfield Sports Centre, 123 Park Lane, Greenfield, GF1 2AB Tel. 01234 567890
  </footer>
</body>
</html>
