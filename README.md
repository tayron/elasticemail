# Laravel Elastic Email
Plugin que permite enviar email usando Elastic Email 
https://elasticemail.com/


## Instalação

Carregue via composer:
```bash
composer require tayron/elasticemail
```

Adicione a seguinte configuração no arquivo: config/services.php:
```php
'elasticemail' => [
    'key' => env('ELASTIC_EMAIL_API_KEY_KEY')
]
```

No seu arquivo .env adicione a configuração:
```env
    ...
    ELASTIC_EMAIL_API_KEY=sua_api_key_aqui
```

Abra o arquivo config/app.php e adicione um novo ServiceProvider:
```php
'providers' => [
    ...
    Tayron\ElasticEmail\MailServiceProvider::class,
],
```

Abra seu arquivo .env e adicione elasticemail como drive para envio de email:
```env
    ...
    MAIL_DRIVER=elastic_email
```

