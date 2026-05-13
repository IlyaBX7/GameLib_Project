<?php
header('Content-Type: application/json');
if (!isset($_GET['appid'])) {
    echo json_encode(['error' => 'No appid provided']);
    exit;
}

$appid = (int)$_GET['appid'];
$url = "https://store.steampowered.com/api/appdetails?appids={$appid}&l=ukrainian";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $data = json_decode($response, true);
    if (isset($data[$appid]['success']) && $data[$appid]['success']) {
        $gameData = $data[$appid]['data'];
        
        $title = $gameData['name'] ?? '';
        $description = strip_tags($gameData['short_description'] ?? '');
        $cover_url = $gameData['header_image'] ?? '';
        
        $tags_arr = [];
        if (!empty($gameData['genres'])) {
            foreach ($gameData['genres'] as $genre) {
                $tags_arr[] = $genre['description'];
            }
        }
        
        $features_arr = [];
        if (!empty($gameData['categories'])) {
            foreach ($gameData['categories'] as $category) {
                $features_arr[] = $category['description'];
            }
        }
        
        $languages = '';
        if (!empty($gameData['supported_languages'])) {
            $langs_parts = explode('<br>', $gameData['supported_languages']);
            $langs_clean = strip_tags($langs_parts[0]); 
            $languages = trim(str_replace('*', '', $langs_clean)); 
        }
        
        $sys_min = '';
        $sys_rec = '';
        if (isset($gameData['pc_requirements']['minimum'])) {
            $sys_min = strip_tags(str_replace('<br>', "\n", $gameData['pc_requirements']['minimum']));
            $sys_min = preg_replace('/^Мінімальні:\s*/i', '', $sys_min);
        }
        if (isset($gameData['pc_requirements']['recommended'])) {
            $sys_rec = strip_tags(str_replace('<br>', "\n", $gameData['pc_requirements']['recommended']));
            $sys_rec = preg_replace('/^Рекомендовані:\s*/i', '', $sys_rec);
        }
        
        $developer = !empty($gameData['developers']) ? implode(', ', $gameData['developers']) : '';
        $publisher = !empty($gameData['publishers']) ? implode(', ', $gameData['publishers']) : '';
        
        $release_date = '';
        if (!empty($gameData['release_date']['date'])) {
            $parsed_date = strtotime($gameData['release_date']['date']);
            if ($parsed_date) {
                $release_date = date('Y-m-d', $parsed_date);
            }
        }
        
        $screenshots = [];
        if (!empty($gameData['screenshots'])) {
            for ($i = 0; $i < min(5, count($gameData['screenshots'])); $i++) {
                $screenshots[] = $gameData['screenshots'][$i]['path_thumbnail'];
            }
        }
        
        $achievements = [];
        if (!empty($gameData['achievements']['highlighted'])) {
            foreach ($gameData['achievements']['highlighted'] as $ach) {
                $achievements[] = [
                    'title' => $ach['name'],
                    'description' => 'Офіційне досягнення Steam',
                    'icon' => $ach['path']
                ];
            }
        }
        
        echo json_encode([
            'success' => true,
            'title' => $title,
            'description' => $description,
            'cover_url' => $cover_url,
            'tags' => $tags_arr,
            'features' => $features_arr,
            'languages' => $languages,
            'sys_min' => $sys_min,
            'sys_rec' => $sys_rec,
            'developer' => $developer,
            'publisher' => $publisher,
            'release_date' => $release_date,
            'screenshots' => $screenshots,
            'achievements' => $achievements
        ]);
        exit;
    }
}

echo json_encode(['error' => 'Failed to fetch or parse game from Steam API']);
