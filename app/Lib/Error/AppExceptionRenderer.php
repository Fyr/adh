<?
App::uses('ExceptionRenderer', 'Error');
class AppExceptionRenderer extends ExceptionRenderer {

    public function render() {
        $this->controller->set('title_for_layout', __('Error!'));
        if (Configure::read('debug') == 2) {
            parent::render();
        } else {
            if ($this->method !== 'error500') {
                $this->method = 'error400';
            }
            parent::render();
        }
    }

}
