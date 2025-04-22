

<?php
$servername = "SG-dbpal-12390-mysql-master.servers.mongodirector.com";
$username = "sgroot";
$password = "BO479WvME-OQvxb3";
$dbname = "pal_samaj";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all form inputs safely
function getPost($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$name = getPost('name');
$father = getPost('father');
$dob = getPost('dob');
$gender = getPost('gender');
$married = getPost('married');
$education = getPost('education');
$occupation = getPost('occupation');
$house_type = getPost('house_type');
$cow = (int)getPost('cow');
$goat = (int)getPost('goat');
$buffalo = (int)getPost('buffalo');
$sheep = (int)getPost('sheep');
$land = (float)getPost('land');
$mobile = getPost('mobile');
$email = getPost('email');
$religion = getPost('religion');
$caste = getPost('caste');
$gotra = getPost('gotra');

// Handle image upload
$photo_path = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
    $photo_name = time() . '_' . basename($_FILES['photo']['name']);
    $photo_path = "uploads/photos/" . $photo_name;
    if (!is_dir("uploads/photos")) {
        mkdir("uploads/photos", 0777, true);
    }
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
}

// Handle cropped image
$cropped_image_path = null;
if (!empty($_POST['croppedImage'])) {
    $cropped_image = $_POST['croppedImage'];
    $cropped_image = str_replace('data:image/png;base64,', '', $cropped_image);
    $cropped_image = base64_decode($cropped_image);
    $cropped_image_path = "uploads/cropped_images/" . time() . "_cropped.png";
    if (!is_dir("uploads/cropped_images")) {
        mkdir("uploads/cropped_images", 0777, true);
    }
    file_put_contents($cropped_image_path, $cropped_image);
}

// Insert into database using prepared statement
$stmt = $conn->prepare("INSERT INTO registrations (name, father, dob, gender, married, education, occupation, house_type, cow, goat, buffalo, sheep, land, mobile, email, religion, caste, gotra, photo, cropped_image)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssssiiiissssssss", $name, $father, $dob, $gender, $married, $education, $occupation, $house_type, $cow, $goat, $buffalo, $sheep, $land, $mobile, $email, $religion, $caste, $gotra, $photo_path, $cropped_image_path);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

