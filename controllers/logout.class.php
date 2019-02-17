<?php
namespace Aoe2CM;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

class LogoutController
{
    public static function processLogout(Request $request, Response $response, ServiceProvider $service)
    {
        // Redirect to the logged in home page
        $service->session(Constants::LOGGED_IN, false);

        $response->redirect(ROOTDIR);
    }
}
