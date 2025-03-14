<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Импорт CSV</title>
</head>
<body>
<h2>Импорт товаров из CSV</h2>
<form action="import.php" method="post" enctype="multipart/form-data">
    <input type="file" name="csv_file" required>
    <button type="submit">Загрузить</button>
</form>
</body>
</html>