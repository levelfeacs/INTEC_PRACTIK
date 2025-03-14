<?php
require_once "db.php";

class CsvHandler {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    // Функция для импорта CSV в базу данных
    public function importCSV($filePath, $userId) {
        $rowCount = 0;
        $updatedCount = 0;
        $addedCount = 0;

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            fgetcsv($handle); // Пропускаем заголовок
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $rowCount++;
                list($id, $name, $name_trans, $price, $small_text, $big_text) = $data;

                // Очищаем `small_text` от тегов и ограничиваем 30 символами
                $small_text = strip_tags($small_text);
                if (strlen($small_text) > 30) {
                    $small_text = substr($small_text, 0, 30);
                }
                if (empty($small_text)) {
                    $small_text = substr(strip_tags($big_text), 0, 30);
                }

                // Проверяем, есть ли товар с таким ID и user_id
                $stmt = $this->db->prepare("SELECT id FROM product WHERE id = ? AND user_id = ?");
                $stmt->execute([$id, $userId]);
                $existing = $stmt->fetch();

                if ($existing) {
                    // Обновляем товар
                    $stmt = $this->db->prepare("UPDATE product SET name = ?, name_trans = ?, price = ?, small_text = ?, big_text = ? WHERE id = ? AND user_id = ?");
                    $stmt->execute([$name, $name_trans, $price, $small_text, $big_text, $id, $userId]);
                    $updatedCount++;
                } else {
                    // Добавляем новый товар
                    $stmt = $this->db->prepare("INSERT INTO product (name, name_trans, price, small_text, big_text, user_id) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $name_trans, $price, $small_text, $big_text, $userId]);
                    $addedCount++;
                }
            }
            fclose($handle);
        }

        return "Добавлено: $addedCount | Обновлено: $updatedCount";
    }
}
?>