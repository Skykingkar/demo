<?php
// Database connection (replace with your actual database details)
$servername = "115.245.190.162/32";
$username = "sgroot";
$password = "BO479WvME-OQvxb3";
$dbname = "dbpal";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for database connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $name = $_POST['name'];
    $father = $_POST['father'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $married = $_POST['married'];
    $education = $_POST['education'];
    $occupation = $_POST['occupation'];
    $house_type = $_POST['house_type'];
    $cow = $_POST['cow'];
    $goat = $_POST['goat'];
    $buffalo = $_POST['buffalo'];
    $sheep = $_POST['sheep'];
    $land = $_POST['land'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $religion = $_POST['religion'];
    $caste = $_POST['caste'];
    $gotra = $_POST['gotra'];
    
    // Handle the photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Set the file upload destination and name
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_name = time() . '_' . $_FILES['photo']['name'];
        $photo_path = "uploads/photos/" . $photo_name;

        // Move the uploaded file to the server
        if (move_uploaded_file($photo_tmp_name, $photo_path)) {
            echo "File uploaded successfully!<br>";
        } else {
            echo "Failed to upload file.<br>";
        }
    } else {
        $photo_path = null;
    }

    // Handle the cropped image if it exists
    if (isset($_POST['cropped_image']) && !empty($_POST['cropped_image'])) {
        $cropped_image_data = $_POST['cropped_image'];

        // Convert the base64 image data to an image file
        $image_data = base64_decode(str_replace('data:image/png;base64,', '', $cropped_image_data));
        $cropped_image_name = time() . '_cropped.png';
        $cropped_image_path = "uploads/cropped_images/" . $cropped_image_name;

        file_put_contents($cropped_image_path, $image_data);
    }

    // Insert the form data into the database
    $sql = "INSERT INTO registrations (name, father, dob, gender, married, education, occupation, house_type, cow, goat, buffalo, sheep, land, mobile, email, religion, caste, gotra, photo, cropped_image)
            VALUES ('$name', '$father', '$dob', '$gender', '$married', '$education', '$occupation', '$house_type', '$cow', '$goat', '$buffalo', '$sheep', '$land', '$mobile', '$email', '$religion', '$caste', '$gotra', '$photo_path', '$cropped_image_path')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
