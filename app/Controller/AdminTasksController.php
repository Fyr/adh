<?php
App::uses('AppController', 'Controller');
App::uses('AdminController', 'Controller');
class AdminTasksController extends AdminController {
    public $name = 'AdminTasks';
    public $uses = array('Task');

    public $paginate = array(
        'fields' => array('created', 'exec_time', 'task_name', 'status', 'progress', 'total', 'xdata', 'user_id'),
        'order' => array('id' => 'desc'), // 'created' => 'desc',
        'limit' => 20
    );

    public function index() {
        $this->PCTableGrid->paginate('Task');
    }

    public function collectData() {
        $task = $this->Task->getActiveTask('CollectData');
        if ($task) {
            $this->Flash->success(__('Task is already running'));
        } else {
            $this->runBkg('BkgService collectData');
            sleep(1);
        }
        $this->redirect(array('action' => 'index'));
    }
}
