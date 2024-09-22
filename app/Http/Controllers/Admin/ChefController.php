<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChefRequest;
use App\Http\Requests\UpdateChefRequest;
use App\Models\Chef;
use App\Models\Specialization;
use App\Models\User;
use App\Models\Message;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChefController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user) {

            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }
        $chefs = Chef::all();



        return view('admin.chefs.index', compact('chefs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $user = auth()->user();
        if (!$user) {

            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }

        $chefs = new Chef();
        $specializations = Specialization::all();




        return view('admin.chefs.create', compact('chefs', 'specializations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChefRequest $request)
    {



        $data = $request->validated();
        if ($request->hasFile('photograph')) {
            $img_path = Storage::disk('public')->put('upload/img', $data['photograph']);
            $data["photograph"] = $img_path;
        }
        if ($request->hasFile('CV')) {
            $file_path = Storage::disk('public')->put('upload/cv', $data['CV']);
            $data["CV"] = $file_path;
        }

        $data['user_id'] = Auth::id();
        $newChef = Chef::create($data);
        $newChef->specializations()->sync($data['specializations']);
        return redirect()->route('admin.chefs.show', $newChef)->with('create-chef', $newChef->user->name . ' ' . 'has been CREATE with success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chef $chef)
    {
        //$user = auth()->user();
        if (!Auth::check()) {

            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }
        if (Auth::id() === $chef->user_id) {
            return view('admin.chefs.show', compact('chef'));
        } else {
            return redirect()->route('admin.dashboard')->with('wrong-user',  $chef->user->name . ' ' . 'it\'s not your profile');
        }
    }


    public function userAuthenticated($user_id)
    {

        return view('admin.chefs.show', compact('user_id'));
    }

    public function viewDashboard()
    {
        $user = auth()->user();
        if (!$user) {

            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }
        return view('admin.dashboard');
    }

    public function viewMessage(Chef $chef)
    {
        $user = auth()->user();
        if (!$user) {

            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }

        $chef = Chef::with('messages')->find($chef->id);
        //dd($chef);

        return view('admin.chefs.profile.message', compact('chef'));
    }

    public function viewReview(Chef $chef)
    {
        $user = auth()->user();
        if (!$user) {

            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }

        $chef = Chef::with('reviews')->find($chef->id);
        //dd($chef);

        return view('admin.chefs.profile.review', compact('chef'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chef $chef)
    {
        $user = auth()->user();
        if (!$user) {

            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }
        //
        $specializations = Specialization::all();
        return view('admin.chefs.edit', compact('chef', 'specializations'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateChefRequest $request, Chef $chef)
    {
        $data = $request->validated();

        // Se nella request hai il file 'photograph' manda avanti la modifica. Altrimenti non fare nulla.
        if ($request->hasFile('photograph')) {
            $img_path = Storage::disk('public')->put('upload/img', $data['photograph']);
            $data["photograph"] = $img_path;
        }

        if ($request->hasFile('CV')) {
            $file_path = Storage::disk('public')->put('upload/cv', $data['CV']);
            $data["CV"] = $file_path;
        }

        $chef->update($data);

        // Parentesi relazione. Senza parentesi chiamo il model
        $chef->specializations()->sync($data['specializations']);
        return redirect()->route('admin.chefs.show', $chef)->with('edit-chef', $chef->user->name . ' ' . 'è stato MODIFICATO con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chef $chef)
    {
        //
        $chef->delete();

        return redirect()->route('admin.dashboard')->with('delete-chef', $chef->user->name . ' ' . 'has been DELETE with success');
    }
}
