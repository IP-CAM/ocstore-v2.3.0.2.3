<?php
/**
 * Модуль оплаты через ЗАО "РФИ БАНК" https://rficb.ru,
 * 
 * This code is provided under FreeBSD Copyright (license.txt)
 * Исходный код распространяется по лицензии FreeBSD (license.txt)
 */
class ControllerExtensionPaymentRficb extends Controller {
	private $error = array();
	private $form;
	
  	public function index() {
		$this->load->language('extension/payment/rficb');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');

    	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
    		if ($this->validate($this->request->post)) {
				$this->model_setting_setting->editSetting('rficb', $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');
	
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
    		}
    	}

      	$data['breadcrumbs'] = $this->getBreadCrumbs();
		$data['lang'] = $this->language;

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['_error'] = $this->error;
		
    	$data['action'] = $this->url->link('extension/payment/rficb', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$defaults = array(
			'rficb_name' => '',
			'rficb_callback' => '',
			'rficb_success' => '',
			'rficb_error' => '',
			'rficb_secret' => '',
			'rficb_key' => '',
			'rficb_total' => '',
			'rficb_commission' => '', 
      			'rficb_payment_type' => '',
			'rficb_payment_spg' => '',
			'rficb_payment_wm' => '',
			'rficb_payment_ym' => '',
			'rficb_payment_mc' => '',
			'rficb_payment_qiwi' => '',
			'rficb_order_status_id' => '',
			'rficb_geo_zone_id' => '',
			'rficb_status' => '',
			'rficb_sort_order' => '',
		);
		foreach ($defaults as $key=>$value) {
			if (isset($this->request->post[$key])) {
				$defautls[$key] = $this->request->post[$key];
			}
			else {
				$defautls[$key] = $this->config->get($key);
			}
			$data[$key] = $defautls[$key];
			$data['entry_'.$key] = $this->language->get('entry_'.$key);
		}
		$data['text_edit'] = $this->language->get('text_edit');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_total'] = $this->language->get('entry_total');
		//$data['text_edit'] = $this->language->get('text_edit');
		//$data['text_edit'] = $this->language->get('text_edit');
		
    		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
    		$data['heading_title'] = $this->language->get('heading_title');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');		
		//$this->response->setOutput($this->render());	
    $this->response->setOutput($this->load->view('extension/payment/rficb.tpl', $data));
  	}

  	private function validate($post_data) {
		if (!$this->user->hasPermission('modify', 'extension/payment/rficb')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$post_data['rficb_name']) {
			$this->error['warning'] = $this->language->get('error_form');
			$this->error['rficb_name'] = $this->language->get('error_empty_field');
		}
		if (!$post_data['rficb_secret']) {
			$this->error['warning'] = $this->language->get('error_form');
			$this->error['rficb_secret'] = $this->language->get('error_empty_field');
		}
		if (!$post_data['rficb_key']) {
			$this->error['warning'] = $this->language->get('error_form');
			$this->error['rficb_key'] = $this->language->get('error_empty_field');
		}
		
    	if (!$this->error || !sizeof($this->error)) {
      		return true;
    	} else {
      		return false;
    	}
  	}
	
	private function getBreadCrumbs() {
		$breadcrumbs = array();

   		$breadcrumbs[] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$breadcrumbs[] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$breadcrumbs[] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/rficb', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
   		
      	return $breadcrumbs;
	}
}
?>
