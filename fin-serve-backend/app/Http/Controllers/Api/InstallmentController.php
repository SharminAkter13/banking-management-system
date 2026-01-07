<?php

namespace App\Http\Controllers\Api;

use App\Models\Installment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InstallmentController extends Controller
{
    public function index()
    {
        return Installment::with('loan.customer')->get();
    }

    public function show($id)
    {
        return Installment::with('loan.customer')->findOrFail($id);
    }
}
