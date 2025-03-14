<?php
require_once "CsvHandler.php";

session_start();
$userId = $_SESSION['user_id'] ?? 1; // Заглушка, пока нет авторизации

if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['csv_file']['tmp_name'];
    $csvHandler = new CsvHandler();
    $result = $csvHandler->importCSV($fileTmpPath, $userId);
    echo "<p>$result</p>";
} else {
    echo "<p>Ошибка загрузки файла.</p>";
}
?>