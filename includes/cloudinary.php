<?php

/**
 * Завантажує файл у Cloudinary за допомогою cURL (REST API).
 * 
 * @param array $file Масив файлу з $_FILES (наприклад, $_FILES['cover_image'])
 * @return string|false Повертає безпечне посилання (secure_url) або false у разі помилки.
 */
function uploadToCloudinary($file) {
    // Перевіряємо, чи файл дійсно був завантажений без помилок
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $cloudName = 'dzgoskwrg';
    $uploadPreset = 'gamelib_uploads'; // Unsigned preset

    $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

    // Отримуємо повний шлях до тимчасового файлу
    $filePath = $file['tmp_name'];

    // Ініціалізуємо cURL
    $ch = curl_init();

    // Підготовлюємо дані для POST-запиту
    $postFields = [
        'upload_preset' => $uploadPreset,
        // Використовуємо CURLFile для відправки файлу (обов'язково для PHP 5.5+)
        'file' => new CURLFile($filePath, $file['type'], $file['name'])
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Виконуємо запит
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // Якщо запит не вдався або Cloudinary повернув помилку
    if ($httpCode !== 200 || !$response) {
        // Можна додати логування помилки тут
        // error_log("Cloudinary Upload Error: " . $response);
        return false;
    }

    $data = json_decode($response, true);

    // Повертаємо secure_url, якщо він є у відповіді
    if (isset($data['secure_url'])) {
        return $data['secure_url'];
    }

    return false;
}
