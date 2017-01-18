<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrepareNoticeRequest;
use App\Provider;
use Illuminate\Contracts\Auth\Guard;
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

    /*
     * Ask the user to confirm the DmCA that will be delivered.
     */
    public function confirm(PrepareNoticeRequest $request, Guard $auth)
    {
        // return $request->all();
        $template = $this->compileDmcaTemplate($data = $request->all(), $auth);
        // flash the data to the page request so it's available to be stored.
        session()->flash('dmca',$data);
        // return $template;
        return view('notices.confirm', compact('template'));
    }

    public function store()
    {
        //$data = session()->get('dmca');
        //return $data;

        /**
         * Pseudocode:
         * Form data is flashed. Get with session()->get('dmca')
         * Template i in request. Request::input('template')
         * So build up a notice object (create table too)
         * persist it wit this data.
         * and then fire off the email.
         */
    }

    /**
     * Compile the DMCA view from the form data
     * @param $data
     * @param Guard $auth
     * @return \Illuminate\Contracts\View\View
     */
    public function compileDmcaTemplate($data, Guard $auth)
    {
        $data = $data + [
                'name' => $auth->user()->name,
                'email' => $auth->user()->email,
            ];

        return view()->file(app_path('Http/Templates/dmca.blade.php'), $data);
    }
}
