<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mail
{
    function Kirim($to, $sbj, $msg)
    {
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
        $config['smtp_port'] = 465;
        $config['smtp_user'] = 'rsudpn@gmail.com';
        $config['smtp_pass'] = 'zxcvbnm456';
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;

        $CI = &get_instance();
        $CI->load->library('email', $config);
        $body = $CI->load->view('mail/template', $msg, true); //$this->load->view(view,data,true);
        $CI->email->set_newline("\r\n");

        $CI->email->from('rsudpn@gmail.com', 'RSUD Panti Nugroho');
        $CI->email->to($to);
        $CI->email->subject($sbj);
        $CI->email->message($body);

        if ($CI->email->send()) {
            return true;
        } else {
            return false;
        }
        // $CI->email->send(FALSE);
        // return $CI->email->print_debugger(array('headers'));
    }
}
