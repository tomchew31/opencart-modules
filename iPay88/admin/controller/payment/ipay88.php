<?php
class ControllerPaymentIpay88 extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/ipay88');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('ipay88', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('extension/payment'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_sim'] = $this->language->get('text_sim');
		$this->data['text_test'] = $this->language->get('text_test');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_defered'] = $this->language->get('text_defered');
		$this->data['text_authenticate'] = $this->language->get('text_authenticate');
		
		$this->data['entry_vendor'] = $this->language->get('entry_vendor');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['vendor'])) {
			$this->data['error_vendor'] = $this->error['vendor'];
		} else {
			$this->data['error_vendor'] = '';
		}

 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extension/payment'),
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('payment/ipay88'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->https('payment/ipay88');

		$this->data['cancel'] = $this->url->https('extension/payment');
		
		if (isset($this->request->post['ipay88_vendor'])) {
			$this->data['ipay88_vendor'] = $this->request->post['ipay88_vendor'];
		} else {
			$this->data['ipay88_vendor'] = $this->config->get('ipay88_vendor');
		}
		
		if (isset($this->request->post['ipay88_password'])) {
			$this->data['ipay88_password'] = $this->request->post['ipay88_password'];
		} else {
			$this->data['ipay88_password'] = $this->config->get('ipay88_password');
		}

		
		if (isset($this->request->post['ipay88_order_status_id'])) {
			$this->data['ipay88_order_status_id'] = $this->request->post['ipay88_order_status_id'];
		} else {
			$this->data['ipay88_order_status_id'] = $this->config->get('ipay88_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['ipay88_geo_zone_id'])) {
			$this->data['ipay88_geo_zone_id'] = $this->request->post['ipay88_geo_zone_id'];
		} else {
			$this->data['ipay88_geo_zone_id'] = $this->config->get('ipay88_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['ipay88_status'])) {
			$this->data['ipay88_status'] = $this->request->post['ipay88_status'];
		} else {
			$this->data['ipay88_status'] = $this->config->get('ipay88_status');
		}
		
		if (isset($this->request->post['ipay88_sort_order'])) {
			$this->data['ipay88_sort_order'] = $this->request->post['ipay88_sort_order'];
		} else {
			$this->data['ipay88_sort_order'] = $this->config->get('ipay88_sort_order');
		}
		
		$this->template = 'payment/ipay88.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/ipay88')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
	   	if (!$this->request->post['ipay88_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}        

		if (!$this->request->post['ipay88_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>