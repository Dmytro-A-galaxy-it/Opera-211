<?php
class ControllerExtensionModuleAutoClient extends Controller {
    private $error = array();

    public function index(){
        $this->load->language('extension/module/auto_client');

        $this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('auto_client', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        if (isset($this->error['auto_client_count'])) {
			if (empty($this->request->post['auto_client_count'])) {
				$data['error_empty_field'] = $this->error['auto_client_count'];
				$data['error_not_numeric'] = '';
				$data['error_negative_value'] = '';
			} elseif (!is_numeric($this->request->post['auto_client_count'])) {
				$data['error_empty_field'] = '';
				$data['error_not_numeric'] = $this->error['auto_client_count'];
				$data['error_negative_value'] = '';
			} elseif ($this->request->post['auto_client_count'] < 0) {
				$data['error_empty_field'] = ''; 
				$data['error_not_numeric'] = '';  
				$data['error_negative_value'] = $this->error['auto_client_count'];
			}
		} else {
			$data['error_empty_field'] = '';
			$data['error_not_numeric'] = '';
			$data['error_negative_value'] = '';
		}

        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/auto_client', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['action'] = $this->url->link('extension/module/auto_client', 'user_token=' . $this->session->data['user_token'], true);
			
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['auto_client_status'])) {
			$data['auto_client_status'] = $this->request->post['auto_client_status'];
		} else {
			$data['auto_client_status'] = $this->config->get('auto_client_status');
		}

        if (isset($this->request->post['auto_client_count'])) {
			$data['auto_client_count'] = $this->request->post['auto_client_count'];
		} else {
			$data['auto_client_count'] = $this->config->get('auto_client_count');
		} 
        
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/auto_client/auto_client', $data));
    }

    protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/auto_client')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['auto_client_count'])) {
            $this->error['auto_client_count'] =$this->language->get('error_empty_field');
        } elseif (!is_numeric($this->request->post['auto_client_count'])) {
            $this->error['auto_client_count'] = $this->language->get('error_not_numeric');
        } elseif ($this->request->post['auto_client_count'] < 0) {
            $this->error['auto_client_count'] = $this->language->get('error_negative_value');
        }

		return !$this->error;
	}
}

?>