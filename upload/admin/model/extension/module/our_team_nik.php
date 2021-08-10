<?php
class ModelExtensionModuleOurTeamNik extends Model {
    public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "our_team` (
			`member_id` INT(11) NOT NULL AUTO_INCREMENT,
			`telephone` VARCHAR(32) NOT NULL,
			`email` VARCHAR(96) NOT NULL,
			`image` VARCHAR(255) NOT NULL,
			`sort_order` INT(11) NOT NULL,
			`status` INT(11) NOT NULL,
			PRIMARY KEY (`member_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "our_team_description` (
			`member_id` INT(11) NOT NULL,
			`language_id` INT(11) NOT NULL,
			`fio` VARCHAR(75) NOT NULL,
			`position` VARCHAR(50) NOT NULL,
			`about_me` TEXT NOT NULL,
			`commentary`TEXT NOT NULL,
			PRIMARY KEY (`member_id`, `language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "our_team`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "our_team_description`");
    }

    public function addMember($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "our_team SET `telephone` = '" . $this->db->escape($data['telephone']) . "', `email` = '" . $this->db->escape($data['email']) . "', `image` = '" . $this->db->escape($data['image']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "'");

        $member_id = $this->db->getLastId();

        foreach ($data['member_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "our_team_description SET member_id = '" . (int)$member_id . "', language_id = '" . (int)$language_id . "', fio = '" . $this->db->escape($value['fio']) . "', `position` = '" . $this->db->escape($value['position']) . "', `about_me` = '" . $this->db->escape($value['about_me']) . "', `commentary` = '" . $this->db->escape($value['commentary']) . "'");
        }

        $this->cache->delete('our_team');

        return $member_id;
    }

    public function editMember($member_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "our_team SET `telephone` = '" . $this->db->escape($data['telephone']) . "', `email` = '" . $this->db->escape($data['email']) . "', `image` = '" . $this->db->escape($data['image']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE member_id = '" . $member_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "our_team_description WHERE member_id = '" . (int)$member_id . "'");

        foreach ($data['member_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "our_team_description SET member_id = '" . (int)$member_id . "', language_id = '" . (int)$language_id . "', fio = '" . $this->db->escape($value['fio']) . "', `position` = '" . $this->db->escape($value['position']) . "', `about_me` = '" . $this->db->escape($value['about_me']) . "', `commentary` = '" . $this->db->escape($value['commentary']) . "'");
        }

        $this->cache->delete('our_team');
    }

    public function deleteMember($member_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "our_team` WHERE member_id = '" . (int)$member_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "our_team_description` WHERE member_id = '" . (int)$member_id . "'");

        $this->cache->delete('our_team');
    }

    public function getMember($member_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "our_team WHERE member_id = '" . (int)$member_id . "'");

        return $query->row;
    }

    public function getMemberDescription($member_id) {
        $member_description_data = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "our_team_description WHERE member_id = '" . (int)$member_id . "'");

        foreach ($query->rows as $result) {
            $member_description_data[$result['language_id']] = array(
                'fio'            => $result['fio'],
                'position'       => $result['position'],
                'about_me'       => $result['about_me'],
                'commentary'     => $result['commentary']
            );
        }

        return $member_description_data;
    }

    public function getMembers($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "our_team ot LEFT JOIN " . DB_PREFIX . "our_team_description otd ON (ot.member_id = otd.member_id) WHERE otd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sort_data = array(
            'otd.fio',
            'ot.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY otd.fio";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }
}