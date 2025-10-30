<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('dashboard.report', $this->queryParams());
    }

    public function categoryReport(): View
    {
        return view('categories.report', $this->queryParams());
    }

    public function donorReport(): View
    {
        return view('donors.report', $this->queryParams());
    }

    protected function queryParams(): array
    {
        return [
            'startDate' => request()->query('startDate'),
            'endDate' => request()->query('endDate'),
        ];
    }
}
