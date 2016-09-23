<?
App::uses('AppShell', 'Console/Command');
App::uses('Task', 'Model');
class BkgServiceShell extends AppShell {
    public $uses = array('Task');

    public function collectData() {
        $id = $this->Task->add(0, 'CollectData');
        $this->args[0] = $id;
        $this->execTask();
        $this->Task->close($id);
    }

    public function execTask() {
        ignore_user_abort(true);
        set_time_limit(0);

        $id = $this->args[0];
        $taskData = $this->Task->findById($id);
        $taskName = $taskData['Task']['task_name'];
        $task = $this->Tasks->load($taskName);
        $task->id = $id;
        $task->user_id = $taskData['Task']['user_id'];
        $task->params = ($taskData['Task']['params']) ? unserialize($taskData['Task']['params']) : null;
        try {
            $task->execute();
        } catch (Exception $e) {
            $status = $this->Task->getStatus($id);
            $status = ($status == Task::ABORT) ? Task::ABORTED : Task::ERROR;
            $this->Task->setData($id, 'xdata', $e->getMessage());
            $this->Task->setStatus($id, $status);
            $this->out(mb_convert_encoding($e->getMessage(), 'cp1251', 'utf8'));
        }
    }

}

