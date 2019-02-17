<?php

use Klein\{Request, Response, ServiceProvider};

class LogoutController
{
    static public function processLogout(Request $request, Response $response, ServiceProvider $service)
    {
        // Redirect to the logged in home page
        $service->session(Constants::LOGGED_IN, false);

        $response->redirect(ROOTDIR);
    }
}
