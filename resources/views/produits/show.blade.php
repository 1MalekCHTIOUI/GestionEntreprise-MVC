<head>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<link rel="stylesheet" href="{{ asset('css/showProduit.css') }}">
@extends('layouts.layout')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">{{ $produit->titre }}</h3>
                <h6 class="card-subtitle"><u>Reference: #{{ $produit->ref }}</u></h6>
                <h6 class="card-subtitle"><u>Categorie: {{ $produit->categories->titreCateg }}</u></h6>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-6">
                        <div class="white-box text-center"><img src="{{ asset($produit->imagePrincipale) }}"
                                class="img-fluid"></div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-6">
                        <h4 class="box-title mt-5">Description</h4>
                        {!! $produit->description !!}
                        {{-- <h2 class="mt-5">
                        $153<small class="text-success">(36%off)</small>
                    </h2>
                    <button class="btn btn-dark btn-rounded mr-1" data-toggle="tooltip" title=""
                        data-original-title="Add to cart">
                        <i class="fa fa-shopping-cart"></i>
                    </button> --}}
                        {{-- <button class="btn btn-primary btn-rounded">Buy Now</button> --}}
                        <h3 class="box-title mt-5">Description technique</h3>
                        {!! $produit->descriptionTechnique !!}
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h3 class="box-title mt-5">Images de produit</h3>
                        <div class="row">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <ul class="list-inline" id="imageList">
                                        @foreach ($produit->images as $image)
                                            <li class="list-inline-item"><img
                                                    style="max-width: 100px;max-height: 100px;margin: 5px"
                                                    src="{{ asset($image->titreImg) }}" class="img-thumbnail"></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            {{-- @foreach ($produit->images as $image)
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="white-box text-center">
                                        <img src="{{ asset($image->titreImg) }}" class="img-thumbnail">
                                    </div>
                                </div>
                            @endforeach --}}
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h3 class="box-title mt-5">Informations Générales</h3>
                        <div class="table-responsive">
                            <table class="table table-striped table-product">
                                <tbody>

                                    <tr>
                                        <td>Prix de charge</td>
                                        <td>{{ $produit->prixCharge }} DT</td>
                                    </tr>
                                    <tr>
                                        <td>Prix de vente</td>
                                        <td>{{ $produit->prixVente }} DT</td>
                                    </tr>
                                    <tr>
                                        <td>Quantité</td>

                                        <td>{{ $produit->qte }}</td>

                                    </tr>
                                    <tr>
                                        <td>Quantité minimum pour gros</td>

                                        <td>{{ $produit->qteMinGros }}</td>

                                    </tr>
                                    <tr>
                                        <td>Prix de gros</td>
                                        <td>{{ $produit->prixMinGros }} DT</td>
                                    </tr>
                                    <tr>
                                        <td>Promotion</td>
                                        <td>{{ $produit->promo }} %</td>
                                    </tr>
                                    <tr>
                                        <td>Longueur</td>

                                        <td>{{ $produit->longueur }} cm</td>

                                    </tr>
                                    <tr>
                                        <td>Largeur</td>
                                        <td>{{ $produit->largeur }} cm</td>
                                    </tr>
                                    <tr>
                                        <td>Hauteur</td>
                                        <td>{{ $produit->hauteur }} cm</td>
                                    </tr>
                                    <tr>
                                        <td>Profondeur</td>
                                        <td>{{ $produit->profondeur }}</td>
                                    </tr>
                                    <tr>
                                        <td>Temps de production</td>
                                        <td>{{ $produit->tempsProduction }} Heure(s)</td>
                                    </tr>
                                    <tr>
                                        <td>Matériaux</td>
                                        <td>{{ $produit->matiers }}</td>
                                    </tr>


                                    <tr>
                                        <td>Publication sur les réseaux sociaux</td>
                                        <td>...</td>
                                    </tr>
                                    <tr>
                                        <td>Frais de transport</td>
                                        <td>{{ $produit->fraisTransport }} DT</td>
                                    </tr>

                                    <tr>
                                        <td>Actif</td>
                                        <td>{{ $produit->active ? 'Oui' : 'Non' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Date de création</td>
                                        <td>{{ $produit->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
