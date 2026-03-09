<?php
$errors = [];
$profile = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name'] ?? '');
    $age    = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $hobbies = $_POST['hobbies'] ?? [];

    // Validate name
    if ($name === '') {
        $errors[] = 'Full name cannot be empty.';
    }

    // Validate age
    if ($age === '' || !is_numeric($age) || (int)$age < 1 || (int)$age > 120) {
        $errors[] = 'Age must be a number between 1 and 120.';
    }

    if (empty($errors)) {
        $profile = [
            'name'    => $name,
            'age'     => (int)$age,
            'gender'  => $gender !== '' ? $gender : 'Not specified',
            'hobbies' => !empty($hobbies) ? implode(', ', $hobbies) : 'None selected',
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Info Form </title>
    <link rel="stylesheet" href="#.css">
</head>
<body>

<h1>Personal Information Form</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li style="color: red;"><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($profile === null || !empty($errors)): ?>
    <form method="post" action="">

        <p>
            <label for="name">Full Name:</label><br>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </p>

        <p>
            <label for="age">Age:</label><br>
            <input type="number" id="age" name="age" min="1" max="120" value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>">
        </p>

        <p>
            <strong>Gender:</strong><br>
            <label>
                <input type="radio" name="gender" value="Male" <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'checked' : ''; ?>>
                Male
            </label><br>
            <label>
                <input type="radio" name="gender" value="Female" <?php echo (($_POST['gender'] ?? '') === 'Female') ? 'checked' : ''; ?>>
                Female
            </label><br>
            <label>
                <input type="radio" name="gender" value="Prefer not to say" <?php echo (($_POST['gender'] ?? '') === 'Prefer not to say') ? 'checked' : ''; ?>>
                Prefer not to say
            </label>
        </p>

        <p>
            <strong>Hobbies:</strong><br>
            <?php
            $hobby_options = ['Reading', 'Coding', 'Gaming', 'Cooking', 'Traveling', 'Music'];
            $checked_hobbies = $_POST['hobbies'] ?? [];
            foreach ($hobby_options as $hobby):
            ?>
                <label>
                    <input type="checkbox" name="hobbies[]" value="<?php echo htmlspecialchars($hobby); ?>"
                        <?php echo in_array($hobby, $checked_hobbies) ? 'checked' : ''; ?>>
                    <?php echo htmlspecialchars($hobby); ?>
                </label><br>
            <?php endforeach; ?>
        </p>

        <button type="submit">Submit</button>

    </form>

<?php else: ?>

    <hr>
    <h2>&#128100; Profile Card</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Name</th>
            <td><?php echo htmlspecialchars($profile['name']); ?></td>
        </tr>
        <tr>
            <th>Age</th>
            <td><?php echo htmlspecialchars($profile['age']); ?></td>
        </tr>
        <tr>
            <th>Gender</th>
            <td><?php echo htmlspecialchars($profile['gender']); ?></td>
        </tr>
        <tr>
            <th>Hobbies</th>
            <td><?php echo htmlspecialchars($profile['hobbies']); ?></td>
        </tr>
    </table>
    <br>
    <a href="profile.php">&#8592; Fill out another profile</a>

<?php endif; ?>

</body>
</html>