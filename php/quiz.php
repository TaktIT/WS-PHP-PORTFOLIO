<?php

$questions = [
    [
        'q'       => 'Which PHP function is used to count elements in an array?',
        'choices' => ['A' => 'size()', 'B' => 'length()', 'C' => 'count()', 'D' => 'total()'],
        'answer'  => 'C', 
    ],
    [
        'q'       => 'What does HTML stand for?',
        'choices' => ['A' => 'Hyper Text Markup Language', 'B' => 'High Tech Modern Language', 'C' => 'Hyperlink Text Management Language', 'D' => 'Home Tool Markup Language'],
        'answer'  => 'A',
    ],
    [
        'q'       => 'Which superglobal holds form data sent via POST?',
        'choices' => ['A' => '$_GET', 'B' => '$_REQUEST', 'C' => '$_FORM', 'D' => '$_POST'],
        'answer'  => 'D',
    ],
    [
        'q'       => 'What is the output of echo 2 ** 8; in PHP?',
        'choices' => ['A' => '16', 'B' => '256', 'C' => '128', 'D' => '64'],
        'answer'  => 'B',
    ],
    [
        'q'       => 'Which function checks if a variable is set and not NULL?',
        'choices' => ['A' => 'exists()', 'B' => 'defined()', 'C' => 'isset()', 'D' => 'has()'],
        'answer'  => 'C',
    ],
];

// Variable for error messages
$error   = '';

// Variable to store quiz results
$results = null;

// Check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Loop through questions to check if all were answered
    for ($i = 0; $i < count($questions); $i++) {

        // If a question has no selected answer
        if (!isset($_POST["q$i"])) {

            // Show error message
            $error = 'Please answer all questions before submitting.';
            break;
        }
    }

    // Continue only if there is no error
    if ($error === '') {

        $score    = 0;       
        $breakdown = [];      

        // Check each question
        foreach ($questions as $i => $q) {

            // Answer submitted by the user
            $submitted = $_POST["q$i"];

            // Correct answer from the array
            $correct   = $q['answer'];

            // Check if user's answer is correct
            $is_right  = ($submitted === $correct);

            // Increase score if correct
            if ($is_right) $score++;

            // Save detailed result for this question
            $breakdown[] = [
                'q'         => $q['q'],
                'submitted' => $submitted,
                'correct'   => $correct,
                'is_right'  => $is_right,
            ];
        }

        // Total number of questions
        $total = count($questions);

        // Calculate percentage score
        $percentage = round(($score / $total) * 100);

        // Assign grade and remark based on percentage
        if ($percentage >= 90)      { $grade = 'A'; $remark = 'Excellent'; }
        elseif ($percentage >= 80)  { $grade = 'B'; $remark = 'Good'; }
        elseif ($percentage >= 70)  { $grade = 'C'; $remark = 'Average'; }
        elseif ($percentage >= 60)  { $grade = 'D'; $remark = 'Below Average'; }
        else                        { $grade = 'F'; $remark = 'Failed'; }

        // Store results in one array
        $results = compact('score', 'total', 'percentage', 'grade', 'remark', 'breakdown');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Makes the page responsive on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page title -->
    <title>PHP Quiz</title>
</head>
<body>

<!-- Main page title -->
<h1>PHP &amp; Web Knowledge Quiz</h1>

<!-- Quiz instructions -->
<p>5 questions &mdash; select one answer per question.</p>

<hr>

<?php if ($results !== null): ?>

    <!-- Display score summary -->
    <h2>Score Summary</h2>

    <p>
        Score: <?php echo $results['score']; ?> / <?php echo $results['total']; ?>
        (<?php echo $results['percentage']; ?>%)
    </p>

    <p>
        Grade: <?php echo $results['grade']; ?> — <?php echo $results['remark']; ?>
    </p>

    <hr>

    <!-- Display detailed results for each question -->
    <h2>Per-Question Breakdown</h2>

    <ol>
        <?php foreach ($results['breakdown'] as $i => $r): ?>
            <li>

                <!-- Display question -->
                <strong><?php echo htmlspecialchars($r['q']); ?></strong><br>

                <!-- Show user's answer -->
                Your answer: <strong><?php echo htmlspecialchars($r['submitted']); ?></strong>

                <!-- Check if correct -->
                <?php if ($r['is_right']): ?>

                    &#9989; Correct

                <?php else: ?>

                    &#10060; Wrong — Correct answer:
                    <strong><?php echo htmlspecialchars($r['correct']); ?></strong>

                <?php endif; ?>

            </li>
            <br>
        <?php endforeach; ?>
    </ol>

    <!-- Button to retake the quiz -->
    <a href="quiz.php">&#8592; Retake Quiz</a>

<?php else: ?>

    <!-- Show error if not all questions were answered -->
    <?php if ($error !== ''): ?>
        <p style="color: red;">&#10060; <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <!-- Quiz form -->
    <form method="post" action="">

        <?php foreach ($questions as $i => $q): ?>

            <!-- Display question -->
            <p><strong>Q<?php echo $i + 1; ?>:
            <?php echo htmlspecialchars($q['q']); ?></strong></p>

            <!-- Display choices -->
            <?php foreach ($q['choices'] as $key => $choice): ?>

                <label>

                    <input
                        type="radio"
                        name="q<?php echo $i; ?>"
                        value="<?php echo $key; ?>"

                        <!-- Keeps selected answer after submission -->
                        <?php echo (isset($_POST["q$i"]) && $_POST["q$i"] === $key) ? 'checked' : ''; ?>
                    >

                    <?php echo $key; ?>. <?php echo htmlspecialchars($choice); ?>

                </label><br>

            <?php endforeach; ?>

            <br>

        <?php endforeach; ?>

        <!-- Submit button -->
        <button type="submit">Submit Quiz</button>

    </form>

<?php endif; ?>

</body>
</html>