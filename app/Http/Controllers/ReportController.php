<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public function index()
    {
        $startDate = request()->query('startDate');
        $endDate = request()->query('endDate');

        return view('dashboard.report', compact('startDate', 'endDate'));
    }
}
