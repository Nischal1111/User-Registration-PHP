<?php
$nameErr = $emailErr = $passwordErr = $confirmedErr = "";
$name = $email = $password = $confirmedPassword = ""; // Updated variable name

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form validation for name
if (empty($_POST["name"])) {
 $nameErr = "Name is required";
} else {
$name = test_input($_POST["name"]);
 if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
  $nameErr = "Only letters and white space allowed";
 }
}

    // Form validation for email
if (empty($_POST["email"])) {
 $emailErr = "Email is required";
} else {
 $email = test_input($_POST["email"]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $emailErr = "Invalid email format";
}
}

    // Form validation for password
if (empty($_POST["password"])) {
 $passwordErr = "Password is required";
} else {
 $password = test_input($_POST["password"]);
        // You can add more complex password validation here if needed
}

    // Form validation for confirm password
if (empty($_POST["confirmed"])) {
 $confirmedErr = "Confirm password is required";
} else {
 $confirmedPassword = test_input($_POST["confirmed"]); // Updated variable name
if ($confirmedPassword !== $password) { // Updated variable name
  $confirmedErr = "Password does not match";
}
}

    // If there are no validation errors, proceed with user registration
if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmedErr)) {
        // Step 5: Hash the user's password
 $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Step 6: Create the new user's associative array
 $newUser = array(
  "name" => $name,
"email" => $email,
"password" => $hashedPassword
);

        // Step 7: Read the existing user data from "users.json"
 $userData = json_decode(file_get_contents("users.json"), true);

        // Step 8: Add the new user to the existing array
 $userData[] = $newUser;

        // Step 9: Write the updated array back to "users.json"
 $jsonString = json_encode($userData, JSON_PRETTY_PRINT);
if (file_put_contents("users.json", $jsonString)) {
 echo "<div>Registration successful! Welcome, $name!</div>";
} else {
 echo "<p>Error: Failed to save user data.</p>";
}
}
}

function test_input($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
    .error {
    color: red;
 }
</style>
</head>
<body>
<h2>PHP Form Validation</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
 <label>Name:</label><br>
 <input type="text" name="name" placeholder="Enter your name">
 <span class="error"><?php echo $nameErr;?></span><br>

 <label>Email address:</label><br>
 <input type="text" name="email" placeholder="Enter your Email">
 <span class="error"><?php echo $emailErr;?></span><br>

 <label>Password:</label><br>
 <input type="password" name="password" placeholder="Enter your password">
 <span class="error"><?php echo $passwordErr;?></span><br>

<label>Confirm Password:</label><br>
<input type="password" name="confirmed" placeholder="Confirm password">
<span class="error"><?php echo $confirmedErr;?></span><br>

 <button id="btn" type="submit">Submit</button><br> </form>
</body>
</html>