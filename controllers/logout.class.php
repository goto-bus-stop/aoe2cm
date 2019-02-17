<?php
class LogoutController
{
    static public function processLogout($request, $response, $service)
    {
        // Redirect to the logged in home page
        $service->session(Constants::LOGGED_IN, false);

        $response->redirect(ROOTDIR);
    }
}
