<?php

namespace App\Controllers;
use App\Models\Accountmodel;

class CreateAccount extends BaseController
{
    public function index()
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL);
        try{
        helper(['form']);
        $data = [];
        $session = session();
        $model = new Accountmodel();

         if ($this->request->getPost()){
            $rules = [
               'fld_Username' => 'required|min_length[6]|max_length[100]|valid_email|is_unique[tbl_account.fld_Username]',
               'fld_Name' => 'required|min_length[3]|max_length[100]',
               'fld_Sex' => 'required',
               'fld_Address' => 'required|min_length[4]|max_length[100]',
               'fld_Contact' => 'required|min_length[11]|max_length[13]',
               'fld_Email2' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[tbl_account.fld_Username]',
               'fld_Password' => 'required|min_length[8]|max_length[255]',
               'fld_Birthdate' => 'required|valid_date',
               'fld_ConfirmPassword' => 'matches[fld_Password]',
            ];

            if (!$this->validate($rules)) {
               
                $session->setFlashdata('error', $this->validator->getErrors());
                return view('create_account');
            } else {
                $newData = [
                    'fld_Username' => $this->request->getVar('fld_Username'),
                    'fld_Password' => password_hash($this->request->getVar('fld_Password'), PASSWORD_DEFAULT),
                    'fld_Name' => $this->request->getVar('fld_Name'),
                    'fld_Sex' => $this->request->getVar('fld_Sex'),
                    'fld_Address' => $this->request->getVar('fld_Address'),
                    'fld_Contact' => $this->request->getVar('fld_Contact'),
                    'fld_Email' => $this->request->getVar('fld_Email2'),
                    'fld_Status' => 'Active',
                ];
                $model->save($newData);
                $session->setFlashdata('success', 'Account created successfully');
                return redirect()->to(base_url().'');
            }
        }
        } catch (Exception $e) {
            $session->setFlashdata('error', 'An error occurred: ' . $e->getMessage());
        }
        return view('create_account');
    }

}
