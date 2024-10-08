@extends('layouts.admin')

@section('content')
<div class="text-center">

    <h1>
        Scegli un pacchetto di Sponsorizzazioni
        <i class="fa-solid fa-sack-dollar"></i>
    </h1>
    <hr class="mb-5">

    @if(session('not-auth'))
    <div class="alert alert-danger">
        {{session('not-auth')}}
    </div>
    @endif

    <div class="text-center mb-5">
        <a class="btn btn-warning" href="{{route('admin.chefs.show', $chef)}}">Torna al tuo profilo</a>
    </div>

    <div>
        @if ($chef->isSponsored())
        <div>
            <h4>La tua sponsorizzazione attuale:</h4>
            <div class="d-flex justify-content-center m-3">

                @foreach ($chef->sponsorships as $sponsorship)
                @if ($sponsorship->pivot->start_date <= now() && $sponsorship->pivot->end_date >= now())


                <div class="card mx-2 p-2" style="width: 10rem;">

                    <h5>Tipo: {{$sponsorship->name}}</h5>
                    <p>Inizio: {{ $sponsorship->pivot->start_date }}</p>
                    <p>Fine: {{ $sponsorship->pivot->end_date }}</p>
                </div>

                @endif
                @endforeach
            </div>
        </div>

        @else

        <div>
            <h4>Non hai sponsorizzazioni attive</h4>
            <p>Acquistane una per i vantaggi eslusivi!</p>
        </div>

        @endif
    </div>
</div>




<div class="container d-flex justify-content-evenly flex-wrap mt-5">


            <div>
                <h4>
                    Base
                    <i class="fa-solid fa-sack-dollar"></i>
                </h4>
                <p class="card-text">
                    2,99 € per 24 ore di sponsorizzazione
                </p>
                <div class="dropdown text-center">
                    <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Effettua pagamento
                    </button>
                    <div class="dropdown-menu p-3">

                        <form action="{{ route('admin.sponsorships.checkout') }}" method="POST" id="payment-form-1">
                            @method("POST")
                            @csrf
                            <input type="hidden" name="sponsorship_id" value="1">
                            <div id="dropin-container-1"></div>
                            <div class="text-center">

                                <button type="submit" class="btn btn-primary">Acquista</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            <div>
                <h4>
                    Premium
                    <i class="fa-solid fa-sack-dollar"></i>
                    <i class="fa-solid fa-sack-dollar"></i>
                </h4>
                <p class="card-text">
                    5,99 € per 72 ore di sponsorizzazione
                </p>
                <div class="dropdown text-center">
                    <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Effettua pagamento
                    </button>
                    <div class="dropdown-menu p-3">

                        <form action="{{ route('admin.sponsorships.checkout') }}" method="POST" id="payment-form-2">
                            @method("POST")
                            @csrf
                            <input type="hidden" name="sponsorship_id" value="2">
                            <div id="dropin-container-2"></div>
                            <div class="text-center">

                                <button type="submit" class="btn btn-primary">Acquista</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




            <div>


                    <h4>
                        Ultimate
                        <i class="fa-solid fa-sack-dollar"></i>
                        <i class="fa-solid fa-sack-dollar"></i>
                        <i class="fa-solid fa-sack-dollar"></i>
                    </h4>
                    <p class="card-text">
                        9,99 € per 144 ore di sponsorizzazione
                    </p>

                <div class="dropdown text-center">
                    <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Effettua pagamento
                    </button>
                    <div class="dropdown-menu p-3">

                        <form action="{{ route('admin.sponsorships.checkout') }}" method="POST" id="payment-form-3">
                            @method("POST")
                            @csrf
                            <input type="hidden" name="sponsorship_id" value="3">
                            <div id="dropin-container-3"></div>
                            <div class="text-center">

                                <button type="submit" class="btn btn-primary">Acquista</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


    </div>


@endsection


@section('scripts')
<script src="https://js.braintreegateway.com/web/dropin/1.31.0/js/dropin.min.js"></script>
<script>
    window.clientToken = @json($clientToken);
</script>
@vite('resources/js/myScript/sponsorshipBraintree.js')
@endsection
