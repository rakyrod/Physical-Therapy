<?php
// Initialize variables to store form data and errors
$name = $email = $about = $phone = $message = "";
$nameErr = $emailErr = $aboutErr = $phoneErr = $messageErr = "";
$formSubmitted = false;
$formError = false;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $formError = true;
    } else {
        $name = test_input($_POST["name"]);
    }
    
    // Validate Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $formError = true;
    } else {
        $email = test_input($_POST["email"]);
        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $formError = true;
        }
    }
    
    // Validate About
    if (empty($_POST["about"])) {
        $aboutErr = "About is required";
        $formError = true;
    } else {
        $about = test_input($_POST["about"]);
    }
    
    // Validate Phone
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone is required";
        $formError = true;
    } else {
        $phone = test_input($_POST["phone"]);
    }
    
    // Validate Message
    if (empty($_POST["message"])) {
        $messageErr = "Message is required";
        $formError = true;
    } else {
        $message = test_input($_POST["message"]);
    }
    
    // If no errors, process the form
    if (!$formError) {
        // Connect to database
        $servername = "localhost";
        $username = "root"; // Update with your database username
        $password = ""; // Update with your database password
        $dbname = "theracare";
        
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Check if the contact_inquiries table exists, create it if it doesn't
            $checkTableSQL = "SHOW TABLES LIKE 'contact_inquiries'";
            $result = $conn->query($checkTableSQL);
            
            if ($result->rowCount() == 0) {
                // Create the table if it doesn't exist
                $createTableSQL = "CREATE TABLE contact_inquiries (
                                  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                  name VARCHAR(100) NOT NULL,
                                  email VARCHAR(100) NOT NULL,
                                  about VARCHAR(255) NOT NULL,
                                  phone VARCHAR(20) NOT NULL,
                                  message TEXT NOT NULL,
                                  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                                )";
                $conn->exec($createTableSQL);
            }
            
            // Insert form data into the contact_inquiries table
            $sql = "INSERT INTO contact_inquiries (name, email, about, phone, message)
                    VALUES (:name, :email, :about, :phone, :message)";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':about', $about);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':message', $message);
            $stmt->execute();
            
            // Set success flag
            $formSubmitted = true;
            
            // Clear form data
            $name = $email = $about = $phone = $message = "";
            
        } catch(PDOException $e) {
            echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
        }
        
        $conn = null;
    }
}

// Function to sanitize form inputs
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>