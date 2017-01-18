<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrepareNoticeRequest;
use App\Provider;
use Illuminate\Http\Request;

class NoticesController extends Controller
{

    /**
     * NoticesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all notices
     */
    public function index()
    {
        return 'all notices';
    }

    /**
     * Show a page to create a notice
     */
    public function create()
    {
        // get list of providers
        $providers = Provider::pluck('name', 'id');

        // load a view to create a new notice
        // return 'hello';
        return view('notices.create', compact('providers'));
    }

    public function confirm(PrepareNoticeRequest $request)
    {
        return $request->all();
    }

}
