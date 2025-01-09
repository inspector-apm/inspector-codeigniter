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

    /**
     * Before the request enters into the application.
     *
     * @param RequestInterface $request
     * @param $arguments
     * @return void
     * @throws \Exception
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $matchedRoute = service('router')->getMatchedRoute();

        inspector()
            ->startTransaction($matchedRoute[0] . ' ' . $matchedRoute[1])
            ->addContext('Request Body', $request->getBody())
            ->markAsRequest();

        /*if (service('auth')->isLoggedIn()) {
            inspector()->transaction()->withUser();
        }*/
    }

    /**
     * After the controller.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $arguments
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        inspector()->transaction()->setResult($response->getStatusCode());
    }
}
