<?php
class Student_model extends CI_Model {
    
    // Function to fetch students whose payments are due in 2 weeks or less
    public function fetch_upcoming_due_students() {
        // Get students whose EXPIRE_DATE is 14 days from today
        $this->db->select('student.NAME, student.EMAIL, all_payments_tbl.EXPIRE_DATE');
        $this->db->from('student');
        $this->db->join('all_payments_tbl', 'all_payments_tbl.STUDENT = student.ID');
        
        // Check for payments expiring within 14 days
        $this->db->where('DATE_SUB(all_payments_tbl.EXPIRE_DATE, INTERVAL 14 DAY) <=', date('Y-m-d'));
        
        $query = $this->db->get();
        return $query->result_array();
    }
}
?>
