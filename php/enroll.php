<?php
// Student Enrollment System - Handles form submission and validation for new student registrations
$errors  = [];  // Array to store validation error messages
$success = false;  // Flag to track if enrollment was successful
$data    = [];  // Array to store submitted student data

// Available degree programs
$courses = [
    'BSCS' => 'BS Computer Science',
    'BSIT' => 'BS Information Technology',
    'BSED' => 'BS Education',
    'BSN'  => 'BS Nursing',
    'BSBA' => 'BS Business Administration',
    'BSCE' => 'BS Civil Engineering',
    'BSEE' => 'BS Electrical Engineering',
];

// Available academic year levels
$year_levels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

// Check if form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve and sanitize form inputs
    $name    = trim($_POST['name']    ?? '');
    $id      = trim($_POST['id']      ?? '');
    $email   = trim($_POST['email']   ?? '');
    $age     = trim($_POST['age']     ?? '');
    $course  = trim($_POST['course']  ?? '');
    $year    = trim($_POST['year']    ?? '');
    $mobile  = trim($_POST['mobile']  ?? '');
    $address = trim($_POST['address'] ?? '');

    // Validate full name
    if ($name === '') {
        $errors['name'] = 'Full name is required.';
    }

    // Validate student ID format (YYYY-XXXXX)
    if ($id === '') {
        $errors['id'] = 'Student ID is required.';
    } elseif (!preg_match('/^\d{4}-\d{5}$/', $id)) {
        $errors['id'] = 'Format must be YYYY-XXXXX (e.g. 2024-12345).';
    }

    // Validate email address
    if ($email === '') {
        $errors['email'] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    // Validate age (must be numeric and between 15-60)
    if ($age === '' || !is_numeric($age)) {
        $errors['age'] = 'Age is required and must be a number.';
    } elseif ((int)$age < 15 || (int)$age > 60) {
        $errors['age'] = 'Age must be between 15 and 60.';
    }

    // Validate course selection
    if ($course === '' || !array_key_exists($course, $courses)) {
        $errors['course'] = 'Please select a valid course.';
    }

    // Validate year level selection
    if ($year === '' || !in_array($year, $year_levels)) {
        $errors['year'] = 'Please select a year level.';
    }

    // Validate mobile number (must start with 09 and be 11 digits)
    if ($mobile === '') {
        $errors['mobile'] = 'Mobile number is required.';
    } elseif (!preg_match('/^09\d{9}$/', $mobile)) {
        $errors['mobile'] = 'Mobile must be 11 digits and start with 09.';
    }

    // Validate address
    if ($address === '') {
        $errors['address'] = 'Address is required.';
    }

    // If all validations pass, mark as successful and store data
    if (empty($errors)) {
        $success = true;
        $data = compact('name', 'id', 'email', 'age', 'course', 'year', 'mobile', 'address');
    }
}

// Helper function: Safely retrieve and escape POST data for form field values
function val($key) {
    return htmlspecialchars($_POST[$key] ?? '');
}

// Helper function: Display error message for a specific form field
function err($errors, $key) {
    if (isset($errors[$key])) {
        echo '<span class="error">&#10060; ' . htmlspecialchars($errors[$key]) . '</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment</title>
    <link rel="stylesheet" href="enroll.css">
</head>
<body>

<div class="container">

    <p class="page-label">Academic Registration</p>
    <h1 class="page-heading">Student Enrollment</h1>

    <!-- Display success message and enrollment confirmation -->
    <?php if ($success): ?>

        <div class="card">
            <div class="success-badge">
                <span class="check">&#9989;</span>
                <h2>Enrollment Confirmed!</h2>
                <p>Your information has been successfully submitted.</p>
            </div>
            <!-- Display submitted personal information -->
            <div class="section-label">Personal Information</div>
            <div class="result-row"><span class="result-label">Full Name</span><span class="result-value"><?php echo htmlspecialchars($data['name']); ?></span></div>
            <div class="result-row"><span class="result-label">Student ID</span><span class="result-value"><?php echo htmlspecialchars($data['id']); ?></span></div>
            <div class="result-row"><span class="result-label">Email</span><span class="result-value"><?php echo htmlspecialchars($data['email']); ?></span></div>
            <div class="result-row"><span class="result-label">Age</span><span class="result-value"><?php echo htmlspecialchars($data['age']); ?></span></div>
            <div class="result-row"><span class="result-label">Mobile</span><span class="result-value"><?php echo htmlspecialchars($data['mobile']); ?></span></div>
            <div class="result-row"><span class="result-label">Address</span><span class="result-value"><?php echo htmlspecialchars($data['address']); ?></span></div>
            <!-- Display submitted academic information -->
            <div class="section-label">Academic Information</div>
            <div class="result-row"><span class="result-label">Course</span><span class="result-value"><?php echo htmlspecialchars($data['course']); ?></span></div>
            <div class="result-row"><span class="result-label">Year Level</span><span class="result-value"><?php echo htmlspecialchars($data['year']); ?></span></div>
            <br>
            <a href="enroll.php" class="btn-back">&#8592; Enroll Another</a>
        </div>

    <!-- Display enrollment form if not yet submitted -->
    <?php else: ?>

        <div class="card">
            <!-- Enrollment form with validation -->
            <form method="post" action="">

                <!-- Personal information section -->
                <div class="section-label">Personal Information</div>

                <div class="field">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="e.g. Juan dela Cruz"
                        class="<?php echo isset($errors['name']) ? 'invalid' : ''; ?>"
                        value="<?php echo val('name'); ?>">
                    <?php err($errors, 'name'); ?>
                </div>

                <div class="field">
                    <label for="id">Student ID</label>
                    <input type="text" id="id" name="id" placeholder="e.g. 2024-12345"
                        class="<?php echo isset($errors['id']) ? 'invalid' : ''; ?>"
                        value="<?php echo val('id'); ?>">
                    <?php err($errors, 'id'); ?>
                </div>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="e.g. juan@email.com"
                        class="<?php echo isset($errors['email']) ? 'invalid' : ''; ?>"
                        value="<?php echo val('email'); ?>">
                    <?php err($errors, 'email'); ?>
                </div>

                <div class="field">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" placeholder="15–60"
                        class="<?php echo isset($errors['age']) ? 'invalid' : ''; ?>"
                        value="<?php echo val('age'); ?>">
                    <?php err($errors, 'age'); ?>
                </div>

                <div class="field">
                    <label for="mobile">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile" placeholder="e.g. 09171234567"
                        class="<?php echo isset($errors['mobile']) ? 'invalid' : ''; ?>"
                        value="<?php echo val('mobile'); ?>">
                    <?php err($errors, 'mobile'); ?>
                </div>

                <div class="field">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" placeholder="Street, City, Province"
                        class="<?php echo isset($errors['address']) ? 'invalid' : ''; ?>"><?php echo val('address'); ?></textarea>
                    <?php err($errors, 'address'); ?>
                </div>

                <!-- Academic information section -->
                <div class="section-label">Academic Information</div>

                <!-- Course selection -->
                <div class="field">
                    <label for="course">Course</label>
                    <select id="course" name="course" class="<?php echo isset($errors['course']) ? 'invalid' : ''; ?>">
                        <option value="">— Select a course —</option>
                        <?php foreach ($courses as $code => $label): ?>
                            <option value="<?php echo $code; ?>" <?php echo (($_POST['course'] ?? '') === $code) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php err($errors, 'course'); ?>
                </div>

                <!-- Year level selection (radio buttons) -->
                <div class="field">
                    <label>Year Level</label>
                    <div class="radio-group">
                        <?php foreach ($year_levels as $yl): ?>
                            <label>
                                <input type="radio" name="year" value="<?php echo $yl; ?>"
                                    <?php echo (($_POST['year'] ?? '') === $yl) ? 'checked' : ''; ?>>
                                <?php echo $yl; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php err($errors, 'year'); ?>
                </div>

                <button type="submit">Submit Enrollment</button>

            </form>
        </div>

    <?php endif; ?>

</div>

</body>
</html>