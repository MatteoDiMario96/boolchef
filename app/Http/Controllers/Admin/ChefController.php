<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChefRequest;
use App\Http\Requests\UpdateChefRequest;
use App\Models\Chef;
use App\Models\Specialization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChefController extends Controller
{
    /**
     * Display una lista della risorsa
     */
    public function index()
    {
        /**
         * Prendi l'utente attualmete autenticato
         */
        $user = auth()->user();

        /**
         * Se non lo è ritorna nella pagina login con messaggio key:not-auth
         */
        if (!$user) {
            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }

        /**
         * Prendi tutti gli chefs
         */
        $chefs = Chef::all();

        /**
         * Ritorna la view index con chiave e valore di chefs grazie a compact
         */
        return view('admin.chefs.index', compact('chefs'));
    }



    /**
     * Per creare una nuova risorsa
     */
    public function create()
    {
        /**
         * Prendi l'utente attualmete autenticato
         */
        $user = auth()->user();


        /**
         * Se non lo è ritorna nella pagina login con messaggio key:not-auth
         */
        if (!$user) {
            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }

        $chef = new Chef();
        $specializations = Specialization::all();
        return view('admin.chefs.create', compact('chef', 'specializations'));
    }

    /**
     * * Stora la nuova risorsa creata
     */
    public function store(StoreChefRequest $request)
    {
        /**
         * Prendi i dati validati della request
         */
        $data = $request->validated();


        /**
         * Se la richiesta ha un file storala in public nel percorso...
         *
         */
        if ($request->hasFile('photograph')) {
            $img_path = Storage::disk('public')->put('upload/img', $data['photograph']);
            $data["photograph"] = $img_path;
        }
        if ($request->hasFile('CV')) {
            $file_path = Storage::disk('public')->put('upload/cv', $data['CV']);
            $data["CV"] = $file_path;
        }

        // Questo per associare lo user_id a l'id dell'utente autenticato
        $data['user_id'] = Auth::id();


        /**
         * Crea uno chef con le fillable
         *
         * facciamo in modo che
         */
        $newChef = Chef::create($data);

        /**
         * Associa la relazione specializations a newChef e ci aggiunge o toglie le specializzazioni della request
         */
        $newChef->specializations()->sync($data['specializations']);
        return redirect()->route('admin.chefs.show', $newChef)->with('create-chef', $newChef->user->name . ' ' . 'has been CREATE with success');
    }

    /**
     * Mostra una specifica risorsa
     */
    public function show(Chef $chef)
    {
        /**
         * Controlla se l'utente è autenticato
         */
        if (!Auth::check()) {
            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }

        /**
         * Se l'id dell'utente autenticato è uguale all attributo user_id di chef
         *
         *    se si rirtorna la view show
         *
         *    altrimenti ritorna la dashboard con messaggio wrong-user
         */
        if (Auth::id() === $chef->user_id) {
            return view('admin.chefs.show', compact('chef'));
        } else {
            return redirect()->route('admin.dashboard')->with('wrong-user',  $chef->user->name . ' ' . 'it\'s not your profile');
        }
    }

    /**
     * Metodo per tornare view per lo user autenticato
     */
    public function userAuthenticated($user_id)
    {
        return view('admin.chefs.show', compact('user_id'));
    }


    /**
     * Metodo per controllo autenticazione per vedere la dashboard
     */
    public function viewDashboard()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }
        return view('admin.dashboard');
    }


    /**
     * Metodo per controllo autenticazione per vedere i messaggi nella dashboard
     */
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


    /**
     * Metodo per controllo autenticazione per vedere le recensioni nella dashboard
     */
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
     * Mostra il form per la modifica se l'utente è autenticato
     */
    public function edit(Chef $chef)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('not-auth', "Devi aver effettutato l'accesso per visualizzare questa pagina.");
        }

        $specializations = Specialization::all();

        return view('admin.chefs.edit', compact('chef', 'specializations'));
    }

    /**
     * Aggiorna la risorsa specifica nello store
     */

    public function update(UpdateChefRequest $request, Chef $chef)
    {
        /**
         * prendi la request validata
         */
        $data = $request->validated();

        /**
         * Se nella request hai il file 'photograph' storala nel disco con name public e mettilo nel percorso....
         */
        if ($request->hasFile('photograph')) {
            $img_path = Storage::disk('public')->put('upload/img', $data['photograph']);
            $data["photograph"] = $img_path;
        }

        /**
         * Se nella request hai il file 'CV' storala nel disco con name public e mettilo nel percorso....
         */
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
     * Rimuovi la specifica risorsa dallo store
     */
    public function destroy(Chef $chef)
    {
        //
        $chef->delete();

        return redirect()->route('admin.dashboard')->with('delete-chef', $chef->user->name . ' ' . 'has been DELETE with success');
    }
}
