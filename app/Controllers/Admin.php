<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\NotificationModel;

class Admin extends BaseController
{
   
    public function dashboard()
    {
        // Redirect to unified dashboard
        return redirect()->to('/dashboard');
    }
}
