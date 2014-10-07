<?php

App::uses('AppController', 'Controller');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ShopsController extends AppController {

    public function beforeFilter() {
        $this->Auth->allow('index');
    }

    public function index() {
        if ($this->Auth->loggedIn()) {
            $this->Session->setFlash(__('ログインしています。'));
        } else {
            $this->Session->setFlash(__('ログインしていません。'));
        }
    }

}