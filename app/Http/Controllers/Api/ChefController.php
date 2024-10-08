<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chef;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChefController extends Controller
{
    public function index()
    {
        //RITORNA UN JSON CON X COSE
        $chefs = Chef::with('user', 'sponsorships', 'specializations', 'votes', 'reviews')->withCount('reviews') // Con conteggio di rewievs
            /**
             * Aggiunge la colonna personalizzata is_sponsored per la query e crea una subquery per restituire un valore booleano
             */
            ->addSelect([
                'is_sponsored' => function ($query) {
                    $query->select(DB::raw('IF(COUNT(*) > 0, 1, 0)')) // true se ha almeno una sponsorizzazione
                        ->from('chef_sponsorship') // Cerca nella tabella chef_sponsorship
                        ->whereColumn('chef_sponsorship.chef_id', 'chefs.id') // Cerca l'id dello chef della tabella chefs nella tabella chef_sponsorship
                        ->where('chef_sponsorship.start_date', '<=', now()) // Verifica che la data di inzio sia nel passato o presente
                        ->where('chef_sponsorship.end_date', '>=', now());  // Verifica che la data di fine sia nel futuro o presente
                }
            ])
            ->orderByDesc('is_sponsored') // Ordina per sponsorizzato
            ->get();


        /**
         * Per ogni chef controlla se ha una foto o un cv
         * se ce l'ha crea un URL pubblico
         */
        $chefs->each(function ($chef) {
            if ($chef->photograph) {
                $chef->photograph = asset('storage/' . $chef->photograph);
            }
            if ($chef->CV) {
                $chef->CV = asset('storage/' . $chef->CV);
            }
        });

        return response()->json(
            [
                "success" => true,
                "results" => $chefs
            ]
        );
    }

    public function show(Chef $chef)
    {
        $chef = Chef::with('user', 'sponsorships', 'specializations', 'votes', 'messages', 'reviews')

            /**
             * Aggiunge la colonna personalizzata average_vote alla query e crea una subquery per restituire la media del voto
             */
            ->addSelect([
                'average_vote' => Vote::select(DB::raw('AVG(votes.vote)'))
                    ->join('chef_vote', 'votes.id', '=', 'chef_vote.vote_id')
                    ->whereColumn('chef_vote.chef_id', 'chefs.id')
            ])
            /**
             * Aggiunge la colonna personalizzata is_sponsored per la query e crea una subquery per restituire un valore booleano
             */
            ->addSelect([
                'is_sponsored' => function ($query) {
                    $query->select(DB::raw('IF(COUNT(*) > 0, 1, 0)')) // true se ha almeno una sponsorizzazione
                        ->from('chef_sponsorship') // Cerca nella tabella chef_sponsorship
                        ->whereColumn('chef_sponsorship.chef_id', 'chefs.id') // Cerca l'id dello chef della tabella chefs nella tabella chef_sponsorship
                        ->where('chef_sponsorship.start_date', '<=', now()) // Verifica che la data di inzio sia nel passato o presente
                        ->where('chef_sponsorship.end_date', '>=', now());  // Verifica che la data di fine sia nel futuro o presente
                }
            ])
            ->find($chef->id);

        /**
         * Controlla se lo chef ha una foto o un cv
         * se ce l'ha crea un URL pubblico
         */
        if ($chef->photograph) {
            $chef->photograph = asset('storage/' . $chef->photograph);
        }
        if ($chef->CV) {
            $chef->CV = asset('storage/' . $chef->CV);
        }

        return response()->json(
            [
                "success" => true,
                "results" => $chef
            ]
        );
    }

    public function SpecializationSearch(Request $request)
    {
        /**
         * Prendi lo specialization id dalla request di default sarà un array vuoto per gestire query con 0 specializzazioni
         */
        $specializationIds = $request->input('id', []);
        /**
         * Se non è un array trasformacelo
         * Serve per quando si invia una specializzazione e mantenere logia dell'array
         */
        if (!is_array($specializationIds)) {
            $specializationIds = [$specializationIds];
        }
        $vote = $request->input('vote');
        $reviews = $request->input('reviews');

        // Inizio a costuire la query
        $chefs = Chef::with('user', 'sponsorships', 'specializations', 'votes', 'reviews')

            // Con conteggio di rewievs
            ->withCount('reviews')

            /**
             * la query aggiunge la tabella sponsorships tramite le join per poter ordinare gli chef in base alla sponsorizzazione,
             * mostrando per primi quelli con le sponsorizzazioni più recenti.
             */
            ->leftJoin('chef_sponsorship', 'chefs.id', '=', 'chef_sponsorship.chef_id')
            ->leftJoin('sponsorships', 'chef_sponsorship.sponsorship_id', '=', 'sponsorships.id')
            ->orderBy('sponsorships.id', 'desc');

        /**
         * Aggiunge la colonna personalizzata average_vote alla query e crea una subquery per restituire la media del voto
         */
        $chefs = $chefs
            ->addSelect([
                'average_vote' => Vote::select(DB::raw('AVG(votes.vote)'))
                    ->join('chef_vote', 'votes.id', '=', 'chef_vote.vote_id')
                    ->whereColumn('chef_vote.chef_id', 'chefs.id')
            ]);

        /**
         * Aggiunge la colonna personalizzata is_sponsored per la query e crea una subquery per restituire un valore booleano
         */
        $chefs = $chefs->addSelect([
            'is_sponsored' => function ($query) {
                $query->select(DB::raw('COUNT(*) > 0'))
                    ->from('chef_sponsorship')
                    ->whereColumn('chef_sponsorship.chef_id', 'chefs.id')
                    ->where('chef_sponsorship.start_date', '<=', now())
                    ->where('chef_sponsorship.end_date', '>=', now());
            }
        ]);

        // Applica il filtro per le specializzazioni se presente

        if (!empty($specializationIds)) {
            $chefs = $chefs->whereHas('specializations', function ($query) use ($specializationIds) {
                $query->whereIn('specializations.id', $specializationIds);
            });
        }

        // Applica il filtro per voto se presente
        if (!empty($vote)) {
            $chefs = $chefs->having('average_vote', '>=', $vote * 2 );
        }

        // Applica filtro per recensioni se presente
        if (!empty($reviews)) {
            $chefs = $chefs->having('reviews_count', '>=', $reviews);
        }


        // Ordina i risultati mettendo prima i profili sponsorizzati
        $chefs = $chefs->orderByDesc('is_sponsored');


        // Esegui la query
        $chefs = $chefs->get();


        /**
         * Per ogni chef controlla se ha una foto o un cv
         * se ce l'ha crea un URL pubblico
         */
        $chefs->each(function ($chef) {
            if ($chef->photograph) {
                $chef->photograph = asset('storage/' . $chef->photograph);
            }
            if ($chef->CV) {
                $chef->CV = asset('storage/' . $chef->CV);
            }
        });

        return response()->json([
            'success' => true,
            'results' => $chefs
        ]);
    }

}
