<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
App::uses('AdminContentController', 'Controller');
class AdminUsersController extends AdminController {
    public $name = 'AdminUsers';
    public $uses = array('User');

    public $paginate = array(
        'conditions' => array('User.id <> ' => 1),
        'fields' => array('created', 'username', 'email', 'key', 'balance', 'active'),
        'order' => array('created' => 'desc'),
        'limit' => 20
    );
}
