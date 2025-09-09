<?php
    // validation of the join form
    function validateJoinForm() {
        // define variables and set to empty values
        $firstnameErr = $lastnameErr = $dateofbirthErr = $genderErr = $addressErr = $emailErr = $phoneErr = "";
        $firstname = $lastname = $dateofbirth = $gender = $address = $email = $phone = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          if (empty($_POST["firstname"])) {
            $firstnameErr = "First Name is required";
          } else {
            $firstname = test_input($_POST["firstname"]);
            // check if first name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/",$firstname)) {
              $firstnameErr = "Only letters and white space allowed";
            }
          }

          if (empty($_POST["lastname"])) {
            $lastnameErr = "Last Name is required";
          } else {
            $lastname = test_input($_POST["lastname"]);
            // check if last name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/",$lastname)) {
              $lastnameErr = "Only letters and white space allowed";
            }
          }

          if (empty($_POST["dateofbirth"])) {
            $dateofbirthErr = "Date of Birth is required";
          } else {
            $dateofbirth = test_input($_POST["dateofbirth"]);
            // check if date of birth is well-formed
            if (!preg_match("~^\d{2}/\d{2}/\d{4}$~", $dateofbirth)) {
              $dateofbirthErr = "DD/MM/YYYY required";
            }
          }

          if (empty($_POST["email"])) {
            $emailErr = "Email is required";
          } else {
            $email = test_input($_POST["email"]);
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $emailErr = "Invalid email format";
            }
          }

          if (empty($_POST["phone"])) {
            $comment = "Phone is required";
          } else {
            // check if phone is well-formed
            $phoneErr = test_input($_POST["phone"]);
          }
            if(!preg_match("/^[0-9]{11}$/", $phone)) {
              $dateofbirthErr = "00000000000 required";
            }
          }
          // check gender selection
          if (empty($_POST["gender"])) {
            $genderErr = "Gender is required";
          } else {
            $gender = test_input($_POST["gender"]);
          }
        }
    }
    // a function that adds a member and the membership they want to get
    function join() {
              $conn = connect();
              $sql = "INSERT INTO Member (Mem_FName, Mem_Lname, Mem_Address, Mem_Gender, Mem_DOB, Mem_Email, Mem_Phone, Membership_Type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param(
                "ssssssss",
                $_POST['firstname'],
                $_POST['lastname'],
                $_POST['address'],
                $_POST['gender'],
                $_POST['dateofbirth'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['membership']
              );
              if ($stmt->execute()) {
                echo "Your account is created.";
              } else {
                echo "Error: " . $stmt->error;
              }
              $stmt->close();
              $conn->close();
    }
?>
