<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use App\Http\Controllers\AppBaseController;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Response;

class ContactController extends AppBaseController
{
    /** @var  ContactRepository */
    private $contactRepository;

    public function __construct(ContactRepository $contactRepo)
    {
        $this->contactRepository = $contactRepo;
    }

    /**
     * Display a listing of the Contact.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {


        if (Auth::user()->role->name == "Admin"){
            $contacts = $this->contactRepository->all();
            return view('contacts.index')
                ->with('contacts', $contacts);
        }else{
            $contacts = $this->contactRepository->all()
                ->where('section_id','=',Auth::user()->section_id)
                ->where('prov','=','1');
            return view('contacts.index')
                ->with('contacts', $contacts);
        }


    }

    /**
     * Show the form for creating a new Contact.
     *
     * @return Response
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created Contact in storage.
     *
     * @param CreateContactRequest $request
     *
     * @return Response
     */
    public function store(CreateContactRequest $request)
    {
        $input = $request->all();
        $input['user_id']= Auth::user()->id;
        $input['section_id'] = Auth::user()->section_id;

        $contact = $this->contactRepository->create($input);

        Flash::success('Contact saved successfully.');

        $users =User::all()->where('role_id','=','1');


        Mail::send('mailer',array('users'=>$users), function($message) use ($users)
        {
            foreach ($users as $user){
                $message->from('no-reply@site.com', "Site name");
                $message->subject("Welcome to site name");
                $message->to($user['email']);
            }

        });

        return redirect(route('contacts.index'));
    }

    /**
     * Display the specified Contact.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contact = $this->contactRepository->find($id);

        if (empty($contact)) {
            Flash::error('Contact not found');

            return redirect(route('contacts.index'));
        }

        return view('contacts.show')->with('contact', $contact);
    }

    /**
     * Show the form for editing the specified Contact.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $contact = $this->contactRepository->find($id);

        if (empty($contact)) {
            Flash::error('Contact not found');

            return redirect(route('contacts.index'));
        }

        return view('contacts.edit')->with('contact', $contact);
    }

    /**
     * Update the specified Contact in storage.
     *
     * @param int $id
     * @param UpdateContactRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContactRequest $request)
    {
        $contact = $this->contactRepository->find($id);

        if (empty($contact)) {
            Flash::error('Contact not found');

            return redirect(route('contacts.index'));
        }
        DB::beginTransaction();

        try{

            $contact = $this->contactRepository->update($request->all(), $id);
        }catch(\Exception $e){
            Flash::error('canot update for contact ');

            DB::rollback();
        }



        Flash::success('Contact updated successfully.');

        return redirect(route('contacts.index'));
    }

    /**
     * Remove the specified Contact from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contact = $this->contactRepository->find($id);

        if (empty($contact)) {
            Flash::error('Contact not found');

            return redirect(route('contacts.index'));
        }

        $this->contactRepository->delete($id);

        Flash::success('Contact deleted successfully.');

        return redirect(route('contacts.index'));
    }

    public function prov($id){
        $contact = $this->contactRepository->find($id);

        if ($contact->prov == 0){
            $contact->prov = 1;
            $contact->update();
        }
        return redirect(route('contacts.index'));
    }
}
