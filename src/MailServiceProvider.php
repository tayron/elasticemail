<?php

namespace Tayron\ElasticEmail;

use Illuminate\Mail\MailServiceProvider as OriginalServiceProvider;

class MailServiceProvider extends OriginalServiceProvider
{
    protected function registerSwiftTransport()
    {
        $this->app['swift.transport'] = $this->app->share(function ($app) {
            return new TransportManager($app);
        });
    }
}
