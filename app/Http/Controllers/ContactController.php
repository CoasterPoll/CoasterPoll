<?php

namespace ChaseH\Http\Controllers;

use ChaseH\Models\Coasters\Category;
use ChaseH\Models\Coasters\Coaster;
use ChaseH\Models\Coasters\Type;
use ChaseH\Models\Contact;
use ChaseH\Models\Role;
use ChaseH\Models\User;
use ChaseH\Notifications\ContactUs;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    public function general(Request $request) {
        return view('contact.general', [
            'subject' => $request->input('about'),
        ]);
    }

    public function coaster($coaster = 0) {
        if($coaster != 0) {
            $coaster = Coaster::where('id', $coaster)->first();
        } else {
            $coaster = null;
        }

        $types = Cache::tags('coasters')->remember('all_types', 120, function() {
            return Type::select('id', 'name')->get();
        });
        $categories = Cache::tags('coasters')->remember('all_categories', 120, function() {
            return Category::select('id', 'name')->get();
        });

        return view('contact.coaster', [
            'coaster' => $coaster,
            'types' => $types,
            'categories' => $categories,
        ]);
    }

    public function post(Request $request) {
        if(!Auth::check()) {
            $client = new Client();
            $recaptcha = $client->post("https://www.google.com/recaptcha/api/siteverify", [
                'form_params' => [
                    'secret' => env('RECAPTCHA_SECRET_KEY'),
                    'response' => $request->input('g-recaptcha-response'),
                ]
            ]);

            if(!json_decode($recaptcha->getBody())->success) {
                return back()->withWarning("Hmm. Google think's you're a bot. We'll be unable to continue.");
            }
        }

        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if($request->input('type') == "coaster") {
            $extra['name'] = $request->input('coaster_name');
            $extra['slug'] = $request->input('slug');
            $extra['rcdb_id'] = $request->input('rcdb_id');
            $extra['type'] = $request->input('type');
            $extra['categories'] = $request->input('categories');

            $model = Coaster::find($request->input('coaster'));
        }

        $contact = Contact::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
            'extra' => (isset($extra)) ? $extra : null,
        ]);

        $contact->user()->associate($user);

        if(isset($model)) {
            $model->contact()->save($contact);
        }

        Role::where('name', 'Admin')->first()->notify(new ContactUs($contact));

        return redirect(route('home'))->withSuccess("We'll be checking on that shortly.");
    }

    public function admin($id = null) {
        if ($id === null) {
            return view('contact.contacts', [
                'contacts' => Contact::with('user')->paginate(25),
            ]);
        }

        try {
            $contact = Contact::where('id', $id)->with('user', 'contactable')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        return view('contact.admin', [
            'contact' => $contact
        ]);
    }
}
