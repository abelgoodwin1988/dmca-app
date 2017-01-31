<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrepareNoticeRequest;
use App\Notice;
use App\Provider;
use Auth;
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
        return Auth::user()->notices;
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

    /**
     * Create a new DMCA notice
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->createNotice($request);

         // persist it with this data.
         //and then fire off the email.

        //return Notice::last();

        return redirect('notices');
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

    /**
     * Create and persist a new DMCA notice
     * @param Request $request
     */
    public function createNotice(Request $request)
    {
        $data = session()->get('dmca');
        $notice = Notice::open($data)->useTemplate($request->input('template'));
        Auth::user()->notices()->save($notice);
    }
}
