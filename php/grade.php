<?php
// Student Grade Evaluator - Processes student name and score to assign grades
$errors = [];
$result = null;

// Check if form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize student name and score from form input
    $name  = trim($_POST['name'] ?? '');
    $score = $_POST['score'] ?? '';

    // Validate that student name is not empty
    if ($name === '') {
        $errors[] = 'Student name cannot be empty.';
    }

    // Validate that score is a valid number and within range 0-100
    if ($score === '' || !is_numeric($score)) {
        $errors[] = 'Score must be a valid number.';
    } elseif ((int)$score < 0 || (int)$score > 100) {
        $errors[] = 'Score must be between 0 and 100.';
    }

    // If no validation errors, proceed with grade calculation
    if (empty($errors)) {
        $score = (int)$score;

        // Determine grade letter, remark, and color based on score
        if ($score >= 90) {
            $grade  = 'A';
            $remark = 'Excellent';
            $color  = 'green';
        } elseif ($score >= 80) {
            $grade  = 'B';
            $remark = 'Good';
            $color  = 'green';
        } elseif ($score >= 70) {
            $grade  = 'C';
            $remark = 'Average';
            $color  = 'orange';
        } elseif ($score >= 60) {
            $grade  = 'D';
            $remark = 'Below Average';
            $color  = 'red';
        } else {
            $grade  = 'F';
            $remark = 'Failed';
            $color  = 'red';
        }

        // Store the evaluation result in an array
        $result = [
            'name'   => $name,
            'score'  => $score,
            'grade'  => $grade,
            'remark' => $remark,
            'color'  => $color,
        ];
    }
}
?>
<!-- HTML Form and Results Display -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Evaluator</title>
    <link rel="stylesheet" href="#.css">
</head>
<body>

<!-- Main heading -->
<h1>Student Grade Evaluator</h1>

<!-- Form for student input -->
<form method="post" action="">
    <p>
        <label for="name">Student Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
    </p>
    <p>
        <label for="score">Score (0–100):</label><br>
        <input type="number" id="score" name="score" min="0" max="100" value="<?php echo htmlspecialchars($_POST['score'] ?? ''); ?>">
    </p>
    <button type="submit">Evaluate</button>
</form>

<hr>

<!-- Display validation errors if any -->
<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <p style="color: red;">&#10060; <?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Display grade evaluation result if successful -->
<?php if ($result !== null): ?>
    <h2 style="color: <?php echo $result['color']; ?>;">
        &#127891; <?php echo htmlspecialchars($result['name']); ?>
    </h2>
    <!-- Results table showing score, grade, and remark -->
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Score</th>
            <td><?php echo htmlspecialchars($result['score']); ?></td>
        </tr>
        <tr>
            <th>Grade</th>
            <td style="color: <?php echo $result['color']; ?>; font-weight: bold;">
                <?php echo htmlspecialchars($result['grade']); ?>
            </td>
        </tr>
        <tr>
            <th>Remark</th>
            <td style="color: <?php echo $result['color']; ?>;">
                <?php echo htmlspecialchars($result['remark']); ?>
            </td>
        </tr>
    </table>
<?php endif; ?>

</body>
</html>