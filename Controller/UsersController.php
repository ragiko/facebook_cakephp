<?php

App::uses('AppController', 'Controller');
App::import('Vendor', 'facebook/php-sdk/src/facebook');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class UsersController extends AppController {

    public $Facebook;

    public function beforeFilter() {
        $this->Facebook = new Facebook(array(
            'appId' => '374929902578634',
            'secret' => '2279d1bfb45e1cb14d61a5d66c6ae1cf',
            'cookie' => true,
        ));
        $this->Auth->allow('login', 'logout');
    }

    public function index() {
        if ($this->Auth->loggedIn()) {
            $facebookId = $this->Facebook->getUser();
            $this->set('user', $this->User->find('first', ['conditions' => ['User.id' => $facebookId]]));
            $this->set(compact('facebookId'));

            echo "<pre>";
            $me = $this->Facebook->api('/me');
            print_r($me);
            $f = $this->Facebook->api("/v1.0/me?fields=friends{gender}");
            print_r($f);
            echo "</pre>";

        } else {
            $this->redirect(['action' => 'logout']);
        }
    }

    public function login() {
        $this->autoRender = false;
        // facebook OAuth login
        $facebookId = $this->Facebook->getUser();
        if (!$facebookId) {
            $this->_authFacebook();
        }

        $user = $this->User->find('first', ['conditions' => ['User.id' => $facebookId]]);
        if (!empty($user['User'])) {
            if ($this->Auth->login($user['User'])) {
                $this->redirect(['action' => 'index']);
            }
        } else {
            $this->_add();
        }

        $this->redirect(['action' => 'logout']);
    }

    protected function _authFacebook() {
        $loginUrl = $this->Facebook->getLoginUrl(['scope' => 'email,publish_stream,user_birthday,user_education_history,user_likes', 'redirect_uri' => Router::fullBaseUrl() . Router::url(['controller' => 'users', 'action' => 'login'])]);
        return $this->redirect($loginUrl);
    }

    public function logout() {
        $this->Facebook->destroySession();
        $this->redirect($this->Auth->logout());
    }

    protected function _add() {
        $this->autoRender = false;

        $facebookInfo = $this->Facebook->api('/me', 'GET');
        $user = array(
            'User' => [
                'id' => $facebookInfo['id'],
                'name' => $facebookInfo['name'],
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'link' => $facebookInfo['link'],
            ]
        );
        $this->User->create();
        if ($this->User->save($user)) {
            $this->Session->setFlash(__('登録が完了しました。'));
        } else {
            $this->Session->setFlash(__('登録てきません.'));
        }

        $this->redirect(['action' => 'index']);
    }

}
