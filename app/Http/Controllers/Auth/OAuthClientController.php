<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OAuthClient\Manager;
use App\LoginModule\AuthConnector;
use Session;

class OAuthClientController extends Controller
{


    public function redirect($provider) {
        $url = Manager::provider($provider)->getAuthorizationURL();
        return redirect($url);
    }


    public function callback($provider, Request $request) {
        if($auth = Manager::provider($provider)->callback($request)) {
            $auth['provider'] = $provider;
            if(AuthConnector::connect($auth)) {
                return redirect()->intended('/auth_connections');
            }
            Session::put('auth_connection', $auth);
            return redirect('/oauth_client/email_exists');
        }
        return redirect()->route('login');
    }


    public function emailExists() {
        if($auth_connection = Session::pull('auth_connection')) {
            return view('oauth_client.email_exists', $auth_connection);
        }
        return redirect()->route('login');
    }


    public function remove($provider) {
        AuthConnector::disconnect($provider);
        return redirect('/auth_connections');
    }
}