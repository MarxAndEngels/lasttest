<?php
declare(strict_types=1);

namespace App\Services\ApiClient;

class TelegramClient
{

  public string $chat_id;
  public string $api_key;
  public string $parse_mode;
  public bool $disable_web_page_preview;
  public bool $disable_notification;
  public string $reply_markup;

  public function __construct($api_key = '1255697282:AAEIxsJy1fzlXm1uIi0G1drI0VUbxct6oXo', $chat_id = '-1001159865472')
  {
    $this->chat_id = $chat_id;
    $this->api_key = $api_key;
    $this->parse_mode = 'html';
    $this->disable_web_page_preview = false;
    $this->disable_notification = false;
    $this->reply_markup = '{}';
  }

  public function telegramSendMessage(array $params = []) : array
  {
    $mode = '/sendMessage/';
    $params = array_merge([
      'chat_id' => $this->chat_id,
      'text' => null,
      'parse_mode' => $this->parse_mode,
      'disable_web_page_preview' => $this->disable_web_page_preview,
      'disable_notification' => $this->disable_notification,
      'reply_to_message_id' => null,
      'reply_markup' => $this->reply_markup,
    ], $params);
    return $this->tgRequest($mode, $params);
  }

  public function tgRequest($mode = '', $params = null, $url = '') : array
  {
    $mode = trim($mode, '/');

    if (!$url) {
      $url = $this->telegramGetApiUrl($mode);
    }

    $post = is_array($params);

    if($post){
      $response = \Http::asForm()->post($url, $params);
    }else{
      $response = \Http::asJson()->get($url);
    }
    if ($response->successful()){
      $data = $response->body();
    }else{
      $data = [];
    }


    return $this->tgPrepareData($data, $mode);
  }


  protected function telegramGetApiUrl($mode = '', $sfx = 'bot') : ?string
  {
    $url = 'https://api.telegram.org/';
    $key = $this->api_key;
    return rtrim($url, '/') . '/' . $sfx . $key . '/' . $mode;
  }

  protected function tgPrepareData($data, $mode = '') : array
  {
    $mode = strtolower($mode);

    switch ($mode) {
      case '':
        break;
      default:
        $data = $data['result'] ?? array();
        break;
    }

    return $data;
  }

  public function telegramSendMediaGroup(array $params = array()) : array
  {
    $mode = '/sendMediaGroup/';
    $params = array_merge(array(
      'chat_id' => $this->chat_id,
      'media' => null,
      'disable_notification' => false,
      'allow_sending_without_reply' => true,
    ), $params);

    // $params['photo'] = $this->telegramEncodeFile($params['photo']);
    return $this->tgRequest($mode, $params);
  }

}
