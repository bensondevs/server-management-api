<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Newsletters\SaveNewsletterRequest;
use App\Http\Requests\Newsletters\FindNewsletterRequest;

use App\Repositories\NewsletterRepository;

class NewsletterController extends Controller
{
    private $newsletter;

    public function __construct(
    	NewsletterRepository $newsletterRepository
    )
    {
    	$this->newsletter = $newsletterRepository;
    }

    public function index()
    {
    	return view('dashboard.newsletters.index');
    }

    public function populate()
    {
    	$newsletters = $this->newsletter->all();
    	$newsletters = $this->newsletter->paginate();

    	return response()->json(['newsletters' => $newsletters]);
    }

    public function create()
    {
    	return view('dashboard.newsletters.create');
    }

    public function store(SaveNewsletterRequest $request)
    {
    	$input = $request->onlyInRules();
    	$newsletter = $this->newsletter->save($input);

    	return apiResponse($this->newsletter, $newsletter);
    }

    public function edit(FindNewsletterRequest $request)
    {
    	$newsletter = $request->getNewsletter();
    	
    	return view('dashboard.newsleters.edit', compact(['newsletter']));
    }

    public function update(SaveNewsletterRequest $request)
    {
    	$this->newsletter->setModel($request->getNewsletter());
    	$this->newsletter->save($request->onlyInRules());

    	session()->flash(
    		$this->newsletter->status, 
    		$this->newsletter->message
    	);

    	return redirect()->route('dashboard.newsletters.index');
    }

    public function confirmDelete(FindNewsletterRequest $request)
    {
    	return view(
    		'dashboard.newsletters.confirm-delete',
    		['newsletter' => $request->getNewsletter()]
    	);
    }

    public function delete(FindNewsletterRequest $request)
    {
    	$this->newsletter->setModel($request->getNewsletter());
    	$this->newsletter->delete();

    	return redirect()->route('dashboard.newsletters.index');
    }
}
