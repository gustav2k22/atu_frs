<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use League\OAuth2\Client\Provider\Google;

class Reminders extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('oauth2'); // Load the helper
    }

    public function send_single_reminder() {
        $student_id = $this->input->post('student_id');

        // Load database library
        $this->load->database();

        // Fetch student data from the database
        $this->db->select('name, email, amount_pending');
        $this->db->from('student');
        $this->db->join('payment_tbl', 'student.id = payment_tbl.id', 'left');
        $this->db->where('student.id', $student_id);
        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            $response = array('status' => 'error', 'message' => 'Student not found.');
            echo json_encode($response);
            return;
        }

        $student = $query->row();
        $email = $student->email;
        $name = $student->name;
        $amount_pending = $student->amount_pending;
        $expire_date = $this->input->post('expire_date');
       
        // Load email library
        $this->load->library('email');

        // Email configuration for Mailtrap
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'sandbox.smtp.mailtrap.io',
            'smtp_port' => 2525,
            'smtp_user' => '372677eba6f537',
            'smtp_pass' => 'bef9fb687b2b0b',
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );
        $this->email->initialize($config);

        // Email content
        $this->email->from('atu-admin@demomailtrap.com', 'Accra Technical University Admin');
        $this->email->to($email);
        $this->email->subject('Fees Reminder');
        $this->email->message("Dear $name,\n\nThis is a reminder that your payment is due on $expire_date. You have an amount of ₵$amount_pending.00 pending.\n\nThank you.");

        // Send email
        if ($this->email->send()) {
            $response = array('status' => 'success', 'message' => 'Reminder sent successfully.');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to send the reminder.', 'error' => $this->email->print_debugger(array('headers')));
        }

        echo json_encode($response);
    }
}
