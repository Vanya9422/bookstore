<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class DashboardViewController extends BaseController {

    /**
     * Показывает страницу входа.
     * @throws \Exception
     */
    public function __invoke(): void {
        $this->view('admin/dashboard', ['activePage' => 'dashboard']);
    }
}