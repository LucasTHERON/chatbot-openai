<?php

# Get fetched content with POST
$json = file_get_contents("php://input");
$data = json_decode($json);
$query = $data->query;


# If you fetched an empty history, then we return a value to confirm that we erases SESSION variable
if(count($query) == 0){
    $_SESSION['chat_history'] = $query;
    return 'empty';
}


$messages = [];
$messages[] = [
    "role" => "system",
    "content" => "You are a friendly robot who is always happy and polite."
];


foreach ($query as $line) {
    $messages[] = [
        "role" => $line->sender,
        "content" => $line->message
    ];
}


# Put your api keys here for developpement and testing, but remember you shouldn't hardcode sensible data this way
$projectId = "<YOUR PROJECT ID>";
$apiKey = "<YOUR API KEY>";
$url = "https://api.openai.com/v1/chat/completions";

$data = array(
    "model" => "chatgpt-4o-latest",
    "messages" => $messages
);
$headers = array(
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
# Next 2 lines are to ignore SSL if you are using localhost for example
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
######################################################################
$response = curl_exec($ch);


if($response === false){
    echo "Error: " . curl_error($ch);
    $answer = 'An error occured';
}else{
    $decodedResponse = json_decode($response, true);
    $answer = $decodedResponse["choices"][0]["message"]["content"];
}

curl_close($ch);

$object = new stdClass();
$object->sender = 'assistant';
$object->message = $answer;
$query[] = $object;
$_SESSION['chat_history'] = $query;
return $answer;
