<?php
session_start();
require_once '../includes/db_connect.php';

$clientID = '78698731201-o8ildr7r3bqdfh0peoor9o0e77eut636.apps.googleusercontent.com'; 
$clientSecret = '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';

$redirectUri = 'http://' . $_SERVER['HTTP_HOST'] . '/auth/google_callback.php'; 

if (isset($_GET['code'])) {

    $tokenUrl = 'https://oauth2.googleapis.com/token';
    $postData = [
        'code' => $_GET['code'],
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    $tokenData = json_decode($response, true);
    if ($response === false) {
        die("cURL Error: " . $curl_error);
    }

    if (isset($tokenData['access_token'])) {
        $accessToken = $tokenData['access_token'];

        $userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $accessToken;
        $userInfoResponse = file_get_contents($userInfoUrl);
        $userInfo = json_decode($userInfoResponse, true);

        if (!empty($userInfo['email'])) {
            $google_id = $userInfo['id'];
            $email = $userInfo['email'];
            $name = $userInfo['name'];
            $picture = $userInfo['picture']; 

            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR google_id = ?");
            $stmt->execute([$email, $google_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {

                if (empty($user['google_id'])) {
                    $pdo->prepare("UPDATE users SET google_id = ?, avatar_url = ? WHERE id = ?")
                        ->execute([$google_id, $picture, $user['id']]);
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['user_role'];

            } else {

                $dummy_password = password_hash(bin2hex(random_bytes(10)), PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, google_id, avatar_url, user_role) VALUES (?, ?, ?, ?, ?, 'user')");
                $stmt->execute([$name, $email, $dummy_password, $google_id, $picture]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $name;
                $_SESSION['user_role'] = 'user';
            }

            header("Location: ../user/profile.php");
            exit;
        } else {
            die("Помилка отримання даних користувача: " . json_encode($userInfo));
        }
    } else {
        die("Помилка отримання токена: " . json_encode($tokenData));
    }
} else {
    die("Немає коду авторизації від Google.");
}

die("Невідома помилка авторизації через Google. <a href='login.php'>Повернутися назад</a>");
?>