<?php
class ControllerExtensionModuleOurTeamNik extends Controller {
	public function index() {
		$this->load->language('extension/module/our_team_nik');
		$this->load->model('extension/module/our_team_nik');
		$this->load->model('tool/image');

		if (isset($this->request->get['member_id'])) {
		    return $this->getMemberView($this->request->get['member_id']);
        }

        $data['members'] = $this->model_extension_module_our_team_nik->getMembers();
		$data['count_members'] = count($data['members']);

        foreach ($data['members'] as $key => $member) {
            if ($member['about_me']) {
                $data['members'][$key]['about_me'] = html_entity_decode($member['about_me']);
            }
            if ($member['commentary']) {
                $data['members'][$key]['commentary'] = html_entity_decode($member['commentary']);
            }

            $data['members'][$key]['link'] = $this->url->link('extension/module/our_team_nik', 'member_id=' . $member['member_id']);

            if ($key == 0) {
                if ($member['image']) {
                    $data['members'][$key]['thumb'] = $this->model_tool_image->resize($member['image'], 676, 400);
                } else {
                    $data['members'][$key]['thumb'] = '';
                }
            } else {
                if ($member['image']) {
                    $data['members'][$key]['thumb'] = $this->model_tool_image->resize($member['image'], 325, 237);
                } else {
                    $data['members'][$key]['thumb'] = '';
                }
            }
        }

		return $this->load->view('extension/module/our_team_nik', $data);
	}

	protected function getMemberView($member_id) {
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $member = $this->model_extension_module_our_team_nik->getMember($member_id);

        if ($member['about_me']) {
            $member['about_me'] = html_entity_decode($member['about_me']);
        }
        if ($member['commentary']) {
            $member['commentary'] = html_entity_decode($member['commentary']);
        }

        if ($member['image']) {
            $member['thumb'] = $this->model_tool_image->resize($member['image'], 676, 400);
        } else {
            $member['thumb'] = '';
        }

        $data['member'] = $member;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/module/our_team_member_info_nik', $data));
    }
}