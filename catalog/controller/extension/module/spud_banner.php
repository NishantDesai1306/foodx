<?php
class ControllerExtensionModuleSpudBanner extends Controller {
	public function index($setting) {
		$data = array();

		if (!$this->customer->isLogged()) {
			$data['login_link'] = $this->url->link('account/login', true);
			$data['register_link'] = $this->url->link('account/register', true);
		}

		return $this->load->view('extension/module/spud_banner', $data);
	}
}
