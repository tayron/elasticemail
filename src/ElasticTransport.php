<?php

namespace Tayron\ElasticEmail;

use Illuminate\Mail\Transport\Transport;
use Swift_Mime_Message;

class ElasticTransport extends Transport 
{
    protected $key;
    protected $url = 'https://api.elasticemail.com/v2/email/send';

    public function __construct($key) 
    {
        $this->key = $key;
    }

    public function send(Swift_Mime_Message $message, &$failedRecipients = null) 
    {
        $this->beforeSendPerformed($message);
        $post = ['from' => $this->getFromAddress($message)['email'],
            'fromName' => $this->getFromAddress($message)['name'],
            'apikey' => $this->key,
            'subject' => $message->getSubject(),
            'to' => $this->getEmailAddresses($message),
            'bodyHtml' => $message->getBody(),
            'bodyText' => $this->getText($message),
            'isTransactional' => false];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        if(!$result->success){
            throw new \Exception($result->error);
        }
        
        return $result;
    }

    protected function getText(Swift_Mime_Message $message) 
    {
        $text = null;

        foreach ($message->getChildren() as $child) {
            if ($child->getContentType() == 'text/plain') {
                $text = $child->getBody();
            }
        }

        return $text;
    }

    protected function getFromAddress(Swift_Mime_Message $message) 
    {
        return [
            'email' => array_keys($message->getFrom())[0],
            'name' => array_values($message->getFrom())[0],
        ];
    }

    protected function getEmailAddresses(Swift_Mime_Message $message, $method = 'getTo')
    {
        $data = call_user_func([$message, $method]);

        if (is_array($data)) {
            return implode(',', array_keys($data));
        }
        return '';
    }
}
