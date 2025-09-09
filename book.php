<?php
    require_once("db_connect.php");

    function booking() {
        if (isset($_POST['mem_id']) && isset($_POST['sport_id'])) {
            if (is_numeric($_POST['mem_id']) && is_numeric($_POST['sport_id'])) {
                $conn = connect();
                $mem_id = intval($_POST['mem_id']);
                $sport_id = intval($_POST['sport_id']);
                $sql = "INSERT INTO Booking (Sport_ID, Mem_ID) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $sport_id, $mem_id);
                if ($stmt->execute()) {
                    echo "Booking successfully added to your account.";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
                $conn->close();
            } else {
                echo "Invalid ID";
            }
        } else {
            echo "Missing data";
        }
    }

    booking();
?>
