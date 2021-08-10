<?php
class ModelExtensionModuleOurTeamNik extends Model {
    public function getMembers() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "our_team ot LEFT JOIN " . DB_PREFIX . "our_team_description otd ON (ot.member_id = otd.member_id) WHERE otd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ot.status = 1 ORDER BY ot.sort_order ASC");

        return $query->rows;
    }

    public function getMember($member_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "our_team ot LEFT JOIN " . DB_PREFIX . "our_team_description otd ON (ot.member_id = otd.member_id) WHERE otd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ot.member_id = '" . $member_id . "' AND ot.status = 1");

        return $query->row;
    }
}