<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table = 'tbl_account';

    protected $primaryKey = 'fld_UserId';

    protected $allowedFields = ['fld_Username', 'fld_Password', 'fld_Name', 'fld_Sex', 'fld_Address', 'fld_Email', 'fld_ContactNumber','fld_Status'];
}

?>