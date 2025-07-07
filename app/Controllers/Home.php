<?php

namespace App\Controllers;
use App\Models\Accountmodel;

class Home extends BaseController
{
    public function index()
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
        $session = session();
        $model = new Accountmodel();
        helper(['form']);
        try{
            if ($this->request->getPost()) {
                $rules = [
                    'fld_Username' => 'required|min_length[6]|max_length[100]|valid_email',
                    'fld_Password' => 'required|min_length[8]|max_length[255]',
                ];

                if (!$this->validate($rules)) {
                    $session->setFlashdata('error', $this->validator->getErrors());
                    return view('index');
                } else {
                    $username = $this->request->getVar('fld_Username');
                    $password = $this->request->getVar('fld_Password');

                    $user = $model->where('fld_Username', $username)->first();

                    if ($user && password_verify($password, $user['fld_Password'])) {
                        $sessionData = [
                            'user_id' => $user['fld_UserId'],
                            'username' => $user['fld_Username'],
                            'name' => $user['fld_Name'],
                            'contact' => $user['fld_ContactNumber'],
                            'email' => $user['fld_Email'],
                            'address' => $user['fld_Address'],
                            'isLoggedIn' => true,
                        ];
                        $session->set($sessionData);
                        $session->setFlashdata('success', 'Successfully Logged in');
                        return redirect()->to(base_url() . '');
                    } else {
                        $session->setFlashdata('error', 'Invalid username or password');
                    }
                }
            }
        } catch (Exception $e) {
            $session = session();
            $session->setFlashdata('error', 'An error occurred: ' . $e->getMessage());
        }
        return view('index');
    }

    public function Otp()
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
        $session = session();
        $model = new Accountmodel();
        helper(['form']);
        try {
            if ($this->request->getPost()) {
                $rules = [
                    'fld_Username2' => 'required|min_length[6]|max_length[100]|valid_email',
                ];

                if (!$this->validate($rules)) {
                    $session->setFlashdata('error', $this->validator->getErrors());
                    return view('index');
                } else {
                    $username = $this->request->getVar('fld_Username2');
                    $user = $model->where('fld_Username', $username)->first();

                    if ($user) {
                        // Here you would typically send an OTP to the user's email or phone
                        // For simplicity, we will just set a flash message
                        $from = 'PiP@gmail.com';
                        $to = $user['fld_Email'];
                        $subject = 'OTP Verification';
                        $otpForgot = rand(100000, 999999); // Generate a random 6-digit OTP
                        $message = "Hello $username, <br><br>
                        We received a request to reset your password. <br>
                        If you did not make this request, please ignore this email. <br>
                        Otherwise, please use the following OTP to reset your password. <br><br>
                        Your OTP is: $otpForgot <br><br>
                        This OTP is valid for 10 minutes. <br><br>
                        Thank you,<br>
                        PiP Team";
                        $headers = "From: $from\r\n";
                        $headers .= "Reply-To: $from\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $email = \Config\Services::email();
                        $email->setFrom($from, 'PiP');
                        $email->setTo($to);
                        $email->setSubject($subject);
                        $email->setMessage($message);
                        if (!$email->send()) {
                            $session->setFlashdata('error', 'Failed to send OTP. Please try again.');
                            return redirect()->to(base_url() . '');
                        }
                        $sessionData = [
                            'username' => $user['fld_Username'],
                            'otpForgot' => $otpForgot, // Store the OTP in session for verification later
                        ];
                        $session->set($sessionData);
                        $session->setFlashdata('otp', 'OTP sent to your registered email address.');
                        // I want to back to the login page that loginmodal is open
                        return redirect()->to(base_url() . '');
                    } else {
                        $session->setFlashdata('error', 'An error occurred.');
                    }
                }
            }
        } catch (Exception $e) {
            $session = session();
            $session->setFlashdata('error', 'An error occurred: ' . $e->getMessage());
        }
        return view('index');
    }

    public function OtpVerify()
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
        $session = session();
        $model = new Accountmodel();
        helper(['form']);
        try {
            if ($this->request->getPost()) {
                $rules = [
                    'fld_Otp' => 'required|min_length[6]|max_length[6]',
                ];

                if (!$this->validate($rules)) {
                    $session->setFlashdata('error', $this->validator->getErrors());
                    return redirect()->to(base_url() . '');
                } else {
                    $otp = $this->request->getVar('fld_Otp');
                    $storedOtp = $session->get('otpForgot'); // Retrieve the OTP from session
                    if (!strcmp($otp, $storedOtp) == 0) {
                        $session->setFlashdata('error', $otp . ' is not correct. Please try again. and ' . $storedOtp . ' is stored OTP');
                        return redirect()->to(base_url() . '');
                    }
                    $session->setFlashdata('success', 'OTP verified successfully.');
                    return redirect()->to(base_url() . '');
                }
            }
        } catch (Exception $e) {
            $session = session();
            $session->setFlashdata('error', 'An error occurred: ' . $e->getMessage());
        }
        return view('index');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url() . '');
    }
}
