<?php 

class MembersRepository extends ReposittoryHelper {
    private $data;

    public function __construct() {
        $this->data = new MemberData();
    }

    public function GetMembers($id) {
        $result = $this->data->GetMembers($id); 
        return $this->GetDataAsArray($result);
    }

    public function GetMembersByMonth($id, $month, $year) {
        $result = $this->data->GetMembersByMonth($id, $month, $year); 
        return $this->GetDataAsArray($result);
    }

    public function GetMembersByRange($id, $start, $end) {
        $result = $this->data->GetMembersByRange($id, $start, $end);
        return $this->GetDataAsArray($result);
    }

    public function GetMembersActivityLogs($id) {
        $result = $this->data->GetMembersActivityLogs($id); 
        return $this->GetDataAsArray($result);
    }
}

?>