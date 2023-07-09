<?php
set_time_limit(0);

function request($url, $data = null, $headers = null, $outputheader = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    if ($data) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    if ($outputheader) {
        curl_setopt($ch, CURLOPT_HEADER, 1);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    // curl_setopt($ch, CURLOPT_ENCODING, "GZIP");
    return curl_exec($ch);
}

$uuid = sprintf(
    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0xffff)
);

$headers = array();
$headers[] = 'X-Bloks-Version-Id: 5f56efad68e1edec7801f630b5c122704ec5378adbee6609a448f105f34a9c73';
$headers[] = 'X-Ig-Www-Claim: hmac.AR2jr3_r-N6PqPM09G7tetqnPfD9P_Ux_HFjJvwyPwksRLqR';
$headers[] = 'X-Ig-Device-Id: '.$uuid;
$headers[] = 'X-Ig-Android-Id: android-6be35fa278d92525';
$headers[] = 'User-Agent: Barcelona 289.0.0.77.109 Android (31/12; 440dpi; 1080x2148; Google/google; sdk_gphone64_arm64; emulator64_arm64; ranchu; en_US; 489720145)';
$headers[] = 'Accept-Language: en-US';
$headers[] = 'Host: i.instagram.com';

$asciiArt = file_get_contents('ascii.txt');
echo $asciiArt;
echo "\n\n";
$username = readline("Masukkan username Instagram Anda: ");
$password = readline("Masukkan password Instagram Anda: ");
$getToken = request('https://i.instagram.com/api/v1/bloks/apps/com.bloks.www.bloks.caa.login.async.send_login_request/', 'params=%7B%22client_input_params%22%3A%7B%22device_id%22%3A%22android-6be35fa278d92525%22%2C%22login_attempt_count%22%3A1%2C%22secure_family_device_id%22%3A%22%22%2C%22machine_id%22%3A%22ZKoroAABAAGyzN_tN5j5gN3Q0kpR%22%2C%22accounts_list%22%3A%5B%5D%2C%22auth_secure_device_id%22%3A%22%22%2C%22password%22%3A%22'.$password.'%22%2C%22family_device_id%22%3A%22621af360-a821-4229-9e3a-678d59eb7d37%22%2C%22fb_ig_device_id%22%3A%5B%5D%2C%22device_emails%22%3A%5B%5D%2C%22try_num%22%3A1%2C%22event_flow%22%3A%22login_manual%22%2C%22event_step%22%3A%22home_page%22%2C%22openid_tokens%22%3A%7B%7D%2C%22client_known_key_hash%22%3A%22%22%2C%22contact_point%22%3A%22'.$username.'%22%2C%22encrypted_msisdn%22%3A%22%22%7D%2C%22server_params%22%3A%7B%22username_text_input_id%22%3A%22wktbih%3A48%22%2C%22device_id%22%3A%22android-6be35fa278d92525%22%2C%22should_trigger_override_login_success_action%22%3A0%2C%22server_login_source%22%3A%22login%22%2C%22waterfall_id%22%3A%226b2356be-c2a1-41ac-8f81-9af5ec0aee87%22%2C%22login_source%22%3A%22Login%22%2C%22INTERNAL__latency_qpl_instance_id%22%3A196987789700164%2C%22is_platform_login%22%3A0%2C%22credential_type%22%3A%22password%22%2C%22family_device_id%22%3A%22621af360-a821-4229-9e3a-678d59eb7d37%22%2C%22INTERNAL__latency_qpl_marker_id%22%3A36707139%2C%22offline_experiment_group%22%3A%22caa_iteration_v3_perf_ig_4%22%2C%22INTERNAL_INFRA_THEME%22%3A%22harm_f%22%2C%22password_text_input_id%22%3A%22wktbih%3A49%22%2C%22ar_event_source%22%3A%22login_home_page%22%7D%7D&bk_client_context=%7B%22bloks_version%22%3A%225f56efad68e1edec7801f630b5c122704ec5378adbee6609a448f105f34a9c73%22%2C%22styles_id%22%3A%22instagram%22%7D&bloks_versioning_id=5f56efad68e1edec7801f630b5c122704ec5378adbee6609a448f105f34a9c73', $headers, '');
$re = '/Bearer (.*?)\\\\/'; //biar ga error
preg_match_all($re, $getToken, $header);

if (!isset($header[1][0])){
    echo 'GAGAL GET AUTH, ULANGI..';
} else {
    $token = $header[1][0];
    echo 'Token Kamu : ' . $token;
}

function likeTimeline($token, $uuid)
{
    $headers = array();
    $headers[] = 'X-Bloks-Version-Id: 5f56efad68e1edec7801f630b5c122704ec5378adbee6609a448f105f34a9c73';
    $headers[] = 'X-Ig-Www-Claim: hmac.AR2jr3_r-N6PqPM09G7tetqnPfD9P_Ux_HFjJvwyPwksRLqR';
    $headers[] = 'X-Ig-Device-Id: ' . $uuid;
    $headers[] = 'X-Ig-Android-Id: android-6be35fa278d92525';
    $headers[] = 'User-Agent: Barcelona 289.0.0.77.109 Android (31/12; 440dpi; 1080x2148; Google/google; sdk_gphone64_arm64; emulator64_arm64; ranchu; en_US; 489720145)';
    $headers[] = 'Accept-Language: en-US';
    $headers[] = 'Authorization: Bearer ' . $token;
    $headers[] = 'Host: i.instagram.com';

    $likeCount = 0; 
    $maxLikeCount = 50; 

    while ($likeCount < $maxLikeCount) {
        $getFeeds = request('https://i.instagram.com/api/v1/feed/text_post_app_timeline/', 'feed_view_info=[]&max_id=&pagination_source=text_post_feed_threads&is_pull_to_refresh=0&_uuid=' . $uuid . '&bloks_versioning_id=5f56efad68e1edec7801f630b5c122704ec5378adbee6609a448f105f34a9c73', $headers, '');
        $parsegetFeeds = json_decode($getFeeds, TRUE);

        if (isset($parsegetFeeds['message']) && $parsegetFeeds['message'] == "login_required") {
            echo "TOKEN KAMU EXPIRED SILAHKAN JALANKAN ULANG PROGRAM";
            exit();
        }

        foreach ($parsegetFeeds['items'] as $postingan) {
            if ($likeCount >= $maxLikeCount) {
                break 2; 
            }

            $mediaid = $postingan['thread_items'][0]['post']['id'];
            echo "TARGET POSTINGAN : " . $mediaid . PHP_EOL;

            $gaslike = request('https://i.instagram.com/api/v1/media/' . $mediaid . '/like/', 'signed_body=SIGNATURE.%7B%22delivery_class%22%3A%22organic%22%2C%22tap_source%22%3A%22button%22%2C%22media_id%22%3A%22' . $mediaid . '%22%2C%22radio_type%22%3A%22wifi-none%22%2C%22_uuid%22%3A%22' . $uuid . '%2C%22recs_ix%22%3A%221%22%2C%22is_carousel_bumped_post%22%3A%22false%22%2C%22container_module%22%3A%22ig_text_feed_timeline%22%2C%22feed_position%22%3A%221%22%7D&d=0', $headers, '');
            $parseGaslike = json_decode($gaslike, TRUE);

            if (isset($parseGaslike['status'])) {
                if ($parseGaslike['status'] == "ok") {
                    echo "STATUS : Berhasil Like Postingan" . PHP_EOL;
                    $likeCount++;
                    sleep(5); // Delay 3 detik setiap like
                } else if ($parseGaslike['status'] == "fail") {
                    echo "Gagal Melakukan Like" . PHP_EOL;
                }
            }
        }
    }
}

likeTimeline($token, $uuid);
likeTimeline($token, $uuid); 
likeTimeline($token, $uuid); 

