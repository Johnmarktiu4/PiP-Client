<?php

namespace App\Controllers;

class ChangePassword extends BaseController
{
    public function index()
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
        helper(['form']);
        $session = session();
        try {
            if ($this->request->getPost()) {
                $rules = [
                    'fld_Password' => 'required|min_length[8]|max_length[255]',
                    'fld_ConfirmPassword' => 'required|matches[fld_Password]',
                ];

                if (!$this->validate($rules)) {
                    $session->setFlashdata('error', $this->validator->getErrors());
                    return view('change_password');
                } else {
                    // Here you would typically handle the password change logic
                    // For example, updating the password in the database
                    $model = new \App\Models\Accountmodel();
                    $newPassword = password_hash($this->request->getVar('fld_Password'), PASSWORD_DEFAULT);
                    $data = $model->where('fld_Username', $session->get('fld_username'))->first();
                    $model->update($data['fld_UserId'], ['fld_Password' => $newPassword]);
                    $session->setFlashdata('success', 'Password changed successfully');
                    return redirect()->to(base_url() . '');
                }
            }
        } catch (Exception $e) {
            $session->setFlashdata('error', 'An error occurred: ' . $e->getMessage());
        }
        return view('change_password');
    }
}
