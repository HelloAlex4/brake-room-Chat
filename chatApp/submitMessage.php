<?php

//sql credentials go here

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

$forbiddenWords = [//not allowed words go here hidden to prevent users finding the list];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["message"])) {
        $message = trim($_POST["message"]);
        
        if (strlen($message) > 370) {
            header("Location: https://brake-room.ch/anonymous");
        } else {
            $messageLower = strtolower($message);
            $containsForbiddenWord = false;
            foreach ($forbiddenWords as $word) {
                if (strpos($messageLower, $word) !== false) {
                    $containsForbiddenWord = true;
                    break;
                }
            }
            
            if ($containsForbiddenWord) {
                header("Location: https://brake-room.ch/anonymous/");;
            } else {
                $ipAddress = $_SERVER['REMOTE_ADDR'];
                $dateTime = date('Y-m-d H:i:s');
                
                $sql = "INSERT INTO anonymousChat (Message, IpAddress, DateTime) VALUES (:message, :ipAddress, :dateTime)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['message' => $message, 'ipAddress' => $ipAddress, 'dateTime' => $dateTime]);
                
                header("Location: https://brake-room.ch/anonymous");
            }
        }
    } else {
        header("Location: https://brake-room.ch/anonymous");;
    }
} else {
    header("Location: https://brake-room.ch/anonymous");
    exit;
}

?>
