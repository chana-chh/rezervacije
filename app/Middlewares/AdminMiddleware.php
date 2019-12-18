<?php

namespace App\Middlewares;

class AdminMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->auth->isLoggedIn() || $this->auth->user()->nivo != 0) {
            $url = (string)$request->getUri();
            $_SESSION['LOGIN_URL'] = $url;
            $this->flash->addMessage('warning', 'Samo za prijavljene administratore');
            return $response->withRedirect($this->router->pathFor('pocetna'));
        }
        return $next($request, $response);
    }
}
