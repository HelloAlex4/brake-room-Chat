<?php
//sql login credentials go here

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

try {
    // SQL query to select the last 100 messages (corrected for MySQL)
    $sql = "SELECT messageId, Message, DateTime FROM anonymousChat ORDER BY messageId DESC LIMIT 100";

    // Prepare and execute the query using $pdo
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch all rows from the query result as an associative array
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set header to output as JSON
    header('Content-Type: application/json');

    // Output the messages as JSON
    echo json_encode($messages);
} catch (PDOException $e) {
    // If an error occurs, return an error message as JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
