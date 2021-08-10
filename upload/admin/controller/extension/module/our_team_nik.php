<?php
class ControllerExtensionModuleOurTeamNik extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/our_team_nik');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/module/our_team_nik');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_our_team_nik', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

        $this->getList();
	}

	public function addMember() {
        $this->load->language('extension/module/our_team_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/our_team_nik');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateMemberForm()) {
            $this->model_extension_module_our_team_nik->addMember($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/our_team_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getFormMember();
    }

    public function editMember() {
        $this->load->language('extension/module/our_team_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/our_team_nik');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateMemberForm()) {
            $this->model_extension_module_our_team_nik->editMember($this->request->get['member_id'],$this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/our_team_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getFormMember();
    }

    public function deleteMember() {
        $this->load->language('extension/module/our_team_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/our_team_nik');

        if (isset($this->request->get['member_id']) && $this->validateDelete()) {
            $this->model_extension_module_our_team_nik->deleteMember($this->request->get['member_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/our_team_nik', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

	protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'hsd.title';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
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
            'href' => $this->url->link('extension/module/our_team_nik', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/our_team_nik', 'user_token=' . $this->session->data['user_token'], true);
        $data['addMember'] = $this->url->link('extension/module/our_team_nik/addMember', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        $data['sort_member_fio'] = $this->url->link('extension/module/our_team_nik', 'user_token=' . $this->session->data['user_token'] . '&sort=otd.fio' . $url, true);
        $data['sort_member_sort_order'] = $this->url->link('extension/module/our_team_nik', 'user_token=' . $this->session->data['user_token'] . '&sort=ot.sort_order' . $url, true);

        if (isset($this->request->post['module_our_team_nik_status'])) {
            $data['module_our_team_nik_status'] = $this->request->post['module_our_team_nik_status'];
        } else {
            $data['module_our_team_nik_status'] = $this->config->get('module_our_team_nik_status');
        }

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
        );

        $results = $this->model_extension_module_our_team_nik->getMembers($filter_data);

        foreach ($results as $result) {
            $data['members'][] = array(
                'member_id'     => $result['member_id'],
                'fio'           => $result['fio'],
                'sort_order'    => $result['sort_order'],
                'edit'          => $this->url->link('extension/module/our_team_nik/editMember', 'user_token=' . $this->session->data['user_token'] . '&member_id=' . $result['member_id'], true),
                'delete'        => $this->url->link('extension/module/our_team_nik/deleteMember', 'user_token=' . $this->session->data['user_token'] . '&member_id=' . $result['member_id'], true)
            );
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/our_team_nik', $data));
    }

    protected function getFormMember() {
        $data['text_form'] = !isset($this->request->get['member_id']) ? $this->language->get('text_add_member') : $this->language->get('text_edit_member');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['fio'])) {
            $data['error_fio'] = $this->error['fio'];
        } else {
            $data['error_fio'] = array();
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/our_team_nik', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['member_id'])) {
            $data['action'] = $this->url->link('extension/module/our_team_nik/addMember', 'user_token=' . $this->session->data['user_token'] . $url, true);
        } else {
            $data['action'] = $this->url->link('extension/module/our_team_nik/editMember', 'user_token=' . $this->session->data['user_token'] . '&member_id=' . $this->request->get['member_id'] . $url, true);
        }

        $data['cancel'] = $this->url->link('extension/module/our_team_nik', 'user_token=' . $this->session->data['user_token'] . $url, true);

        if (isset($this->request->get['member_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $member_info = $this->model_extension_module_our_team_nik->getMember($this->request->get['member_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['member_description'])) {
            $data['member_description'] = $this->request->post['member_description'];
        } elseif (isset($this->request->get['member_id'])) {
            $data['member_description'] = $this->model_extension_module_our_team_nik->getMemberDescription($this->request->get['member_id']);
        } else {
            $data['member_description'] = array();
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($member_info)) {
            $data['telephone'] = $member_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($member_info)) {
            $data['email'] = $member_info['email'];
        } else {
            $data['email'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($member_info)) {
            $data['image'] = $member_info['image'];
        } else {
            $data['image'] = '';
        }

        $data['thumb'] = $data['image'] ? $this->model_tool_image->resize($data['image'], 100, 100) : $this->model_tool_image->resize('no_image.png', 100, 100);
        $data['img_placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($member_info)) {
            $data['sort_order'] = $member_info['sort_order'];
        } else {
            $data['sort_order'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($member_info)) {
            $data['status'] = $member_info['status'];
        } else {
            $data['status'] = true;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/our_team_member_form_nik', $data));
    }

    public function install() {
        if ($this->user->hasPermission('modify', 'extension/module/our_team_nik')) {
            $this->load->model('extension/module/our_team_nik');

            $this->model_extension_module_our_team_nik->install();
        }
    }

    public function uninstall() {
        if ($this->user->hasPermission('modify', 'extension/module/our_team_nik')) {
            $this->load->model('extension/module/our_team_nik');

            $this->model_extension_module_our_team_nik->uninstall();
        }
    }

    protected function validateMemberForm() {
        if (!$this->user->hasPermission('modify', 'extension/module/our_team_nik')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['member_description'] as $language_id => $value) {
            if ((utf8_strlen($value['fio']) < 1) || (utf8_strlen($value['fio']) > 64)) {
                $this->error['fio'][$language_id] = $this->language->get('error_fio');
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'extension/module/our_team_nik')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

	protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/our_team_nik')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

		return !$this->error;
	}
}