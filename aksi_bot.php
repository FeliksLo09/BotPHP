<?php
include("token.php");
//Fungsi url api telegran dengan token BOT yang berada didalam file "token.php".
function req_url($method)
{
	global $TOKEN;
	return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
}
//Fungsi untuk mengirim pesan
function kirim_pesan($chatid, $msgid, $text)
{
    $data = array(
        'chat_id' => $chatid,
        'text'  => $text
    );
    $options = array(
    	'http' => array(
        	'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        	'method'  => 'POST',
        	'content' => http_build_query($data),
    	),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents(req_url('sendMessage'), false, $context);
}

//Fungsi untuk memproses pesan yang diterima 
function proses_pesan($message)
{
        $updateid = $message["update_id"];
        $message_data = $message["message"];
        if (isset($message_data["text"])) {
	    $chatid = $message_data["chat"]["id"];
        $message_id = $message_data["message_id"];
        $text = $message_data["text"];
        
                    //Pesan yang dibalaskan ke pengguna.
                    $text ="Pesan berhasil";
                
        $response = $text;
        kirim_pesan($chatid, $message_id, $response);
    }
    return $updateid;
}
$entityBody = file_get_contents('php://input');
$message = json_decode($entityBody, true);

proses_pesan($message);

?>