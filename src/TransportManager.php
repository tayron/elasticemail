<?php

namespace Tayron\ElasticEmail;

use Illuminate\Mail\TransportManager as OriginalTransportManager;

class TransportManager extends OriginalTransportManager 
{
    protected function createElasticEmailDriver() 
    {
        $config = $this->app['config']->get('services.elastic_email', []);
        return new ElasticTransport($config['key']);
    }
}
