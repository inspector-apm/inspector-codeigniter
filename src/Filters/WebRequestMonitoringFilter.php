<?php

namespace Inspector\CodeIgniter\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class WebRequestMonitoringFilter extends FilterInterface
{
    public function __construct()
    {
        helper('inspector');
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $matchedRoute = service('router')->getMatchedRoute();

        inspector()
            ->startTransaction($matchedRoute[0] . ' ' . $matchedRoute[1])
            ->markAsRequest();

        /*if (service('auth')->isLoggedIn()) {
            inspector()->transaction()->withUser();
        }*/
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        inspector()->transaction()->setResult($response->getStatusCode());
    }
}
