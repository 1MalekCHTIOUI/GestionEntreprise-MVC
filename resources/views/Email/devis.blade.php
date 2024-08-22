<html>

<head>
</head>

<body style="margin:0; padding:0; font-family:Arial, sans-serif; line-height:1.6">
    <main class="cd__main" id="printable">
        <div class="container invoice" style="margin:0 auto; max-width:1200px; padding:15px; width:100%" width="100%">
            <div class="invoice-header" style="margin-bottom:20px">
                <div class="row d-flex align-items-center justify-content-between"
                    style="display:flex; flex-wrap:wrap; margin-left:-15px; margin-right:-15px; align-items:center; justify-content:space-between">
                    <div class="col-xs-12 mb-3"
                        style="box-sizing:border-box; padding-left:15px; padding-right:15px; flex:0 0 100%; max-width:100%; margin-bottom:16px">
                        <h1 style="margin:0; padding:0; font-size:24px; margin-bottom:10px;text-align:center">Devis
                            N°{{ $devis->ref }}</h1>
                        <h4 class="text-muted" style="margin:0; padding:0; color:#6c757d;text-align:center">Date:
                            {{ $devis?->date }}</h4>
                    </div>
                </div>
            </div>
            <div class="invoice-body" style="margin-bottom:20px">
                <div class="row" style="display:flex; flex-wrap:wrap; margin-left:-15px; margin-right:-15px">
                    <div class="col-xs-5 col-md-6"
                        style="box-sizing:border-box; padding-left:15px; padding-right:15px; flex:0 0 50%; max-width:50%">
                        <div class="panel panel-default"
                            style="border:1px solid #ddd; border-radius:4px; box-shadow:0 1px 3px rgba(0, 0, 0, 0.1); margin-bottom:20px">
                            <div class="panel-heading"
                                style="background-color:#f5f5f5; border-bottom:1px solid #ddd; border-radius:4px 4px 0 0; padding:10px 15px"
                                bgcolor="#f5f5f5">
                                <h3 class="panel-title" style="margin:0; padding:0; font-size:18px">Company Details</h3>
                            </div>
                            <div class="panel-body" style="padding:15px">
                                <div>
                                    <img class="media-object logo" alt="logo" width="20%" height="20%">

                                    <dl class="dl-horizontal" style="margin:0; padding:0">
                                        <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                            align="right" width="100">Name</dt>
                                        <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                            <strong>{{ $parameters?->titre }}</strong>
                                        </dd>
                                        <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                            align="right" width="100">Industry</dt>
                                        <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">Software
                                            Development</dd>
                                        <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                            align="right" width="100">Address</dt>
                                        <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                            {{ $parameters?->adresse }}</dd>
                                        <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                            align="right" width="100">Phone</dt>
                                        <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                            {{ '+216 ' . $parameters?->tel }}</dd>
                                        <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                            align="right" width="100">Email</dt>
                                        <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                            {{ $parameters?->email }}</dd>
                                        <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                            align="right" width="100">Matricule Fiscale</dt>
                                        <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                            {{ $parameters?->numero_fiscal }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6"
                        style="box-sizing:border-box; padding-left:15px; padding-right:15px; flex:0 0 50%; max-width:50%">
                        <div class="panel panel-default"
                            style="border:1px solid #ddd; border-radius:4px; box-shadow:0 1px 3px rgba(0, 0, 0, 0.1); margin-bottom:20px">
                            <div class="panel-heading"
                                style="background-color:#f5f5f5; border-bottom:1px solid #ddd; border-radius:4px 4px 0 0; padding:10px 15px"
                                bgcolor="#f5f5f5">
                                <h3 class="panel-title" style="margin:0; padding:0; font-size:18px">Détail Client</h3>
                            </div>
                            <div class="panel-body" style="padding:15px">
                                <dl class="dl-horizontal" style="margin:0; padding:0">
                                    <div class="row"
                                        style="display:flex; flex-wrap:wrap; margin-left:-15px; margin-right:-15px">
                                        <div class="col-xs-12"
                                            style="box-sizing:border-box; padding-left:15px; padding-right:15px; flex:0 0 100%; max-width:100%">
                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Nom</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->nom_societe }}</dd>

                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Secteur</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->secteur }}</dd>
                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Adresse</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->adresse }},
                                                {{ $devis->client->state->name }},
                                                {{ $devis->client->state->country->name }}
                                            </dd>
                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Email</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->email }}</dd>

                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Tel 1</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->tel1 }}</dd>

                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Tel 2</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->tel2 }}</dd>

                                        </div>
                                        {{-- <div class="col-xs-12"
                                            style="box-sizing:border-box; padding-left:15px; padding-right:15px; flex:0 0 100%; max-width:100%">
                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Secteur</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->secteur }}</dd>
                                        </div>
                                        <div class="col-xs-12"
                                            style="box-sizing:border-box; padding-left:15px; padding-right:15px; flex:0 0 100%; max-width:100%">
                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Adresse</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->adresse }},
                                                {{ $devis->client->state->name }},
                                                {{ $devis->client->state->country->name }}
                                            </dd>
                                        </div>
                                        <div class="col-xs-12"
                                            style="box-sizing:border-box; padding-left:15px; padding-right:15px; flex:0 0 100%; max-width:100%">
                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Email</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->email }}</dd>
                                        </div>
                                        <div class="col-xs-12"
                                            style="box-sizing:border-box; padding-left:15px; padding-right:15px; flex:0 0 100%; max-width:100%">
                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Tel 1</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->tel1 }}</dd>
                                        </div>
                                        <div class="col-xs-12"
                                            style="box-sizing:border-box; padding-left:15px; padding-right:15px; flex:0 0 100%; max-width:100%">
                                            <dt style="margin:0; padding:0; clear:left; float:left; font-weight:bold; text-align:right; width:100px"
                                                align="right" width="100">Tel 2</dt>
                                            <dd style="margin:0; padding:0; margin-bottom:10px; margin-left:120px">
                                                {{ $devis->client->tel2 }}</dd>
                                        </div> --}}
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="panel panel-default"
                    style="border:1px solid #ddd; border-radius:4px; box-shadow:0 1px 3px rgba(0, 0, 0, 0.1); margin-bottom:20px">
                    <div class="panel-heading"
                        style="background-color:#f5f5f5; border-bottom:1px solid #ddd; border-radius:4px 4px 0 0; padding:10px 15px"
                        bgcolor="#f5f5f5">
                        <h3 class="panel-title" style="margin:0; padding:0; font-size:18px">Services / Produits</h3>
                    </div>
                    <table class="table table-bordered"
                        style="border-collapse:collapse; margin-bottom:20px; max-width:100%; width:100%"
                        width="100%">
                        <thead>
                            <tr>
                                <th style="border:1px solid #ddd; padding:8px">Item</th>
                                <th class="text-center" style="text-align:center; border:1px solid #ddd; padding:8px"
                                    align="center">Prix Unité</th>
                                <th class="text-center" style="text-align:center; border:1px solid #ddd; padding:8px"
                                    align="center">Total</th>
                                <th class="text-center" style="text-align:center; border:1px solid #ddd; padding:8px"
                                    align="center">Promotion</th>
                                <th class="text-center" style="text-align:center; border:1px solid #ddd; padding:8px"
                                    align="center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($devis->produits as $item)
                                <tr>

                                    <td style="border:1px solid #ddd; padding:8px">
                                        <a item.id>#{{ $item->ref }}</a>
                                        <br>
                                        <small class="text-muted" style="color:#6c757d">
                                            {{ \Illuminate\Support\Str::limit($item->titre, 10) }}</small>
                                    </td>

                                    <td class="text-right"
                                        style="text-align:right; border:1px solid #ddd; padding:8px" align="right">
                                        <span class="mono"
                                            style="font-family:monospace">{{ $item->pivot->qte > $item->qteMinGros ? $item->prixGros : $item->prixVente }}
                                            DT</span>
                                        <br>
                                    </td>
                                    <td class="text-right"
                                        style="text-align:right; border:1px solid #ddd; padding:8px" align="right">
                                        <span class="mono"
                                            style="font-family:monospace">{{ number_format($item->pivot->qte * ($item->pivot->qte > $item->qteMinGros ? $item->prixGros : $item->prixVente), 2) }}
                                            DT</span>
                                        <br>
                                        <small class="text-muted" style="color:#6c757d">{{ $item->pivot->qte }}
                                            Unités</small>
                                    </td>
                                    <td class="text-right"
                                        style="text-align:right; border:1px solid #ddd; padding:8px" align="right">
                                        <span class="mono"
                                            style="font-family:monospace">{{ $item->promo ? '-' . number_format($item->pivot->qte * ($item->pivot->qte > $item->qteMinGros ? $item->prixGros : $item->prixVente) * ($item->promo / 100), 2) : '- 0.00 DT' }}</span>
                                        <br>
                                        <small class="text-muted"
                                            style="color:#6c757d">{{ $item->promo ? 'Special ' . $item->promo . '%' : '' }}</small>
                                    </td>

                                    <td class="text-right"
                                        style="text-align:right; border:1px solid #ddd; padding:8px" align="right">
                                        <strong class="mono"
                                            style="font-family:monospace">{{ number_format($totalAvecPromoSansTva, 2) }}
                                            DT
                                        </strong>
                                        <br>
                                        <small class="text-muted mono"
                                            style="color:#6c757d; font-family:monospace">{{ number_format($item->pivot->qte * ($item->pivot->qte > $item->qteMinGros ? $item->prixGros : $item->prixVente), 2) . ' DT' }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- <div class="panel panel-default"
                    style="border:1px solid #ddd; border-radius:4px; box-shadow:0 1px 3px rgba(0, 0, 0, 0.1); margin-bottom:20px">
                    <table class="table table-bordered"
                        style="border-collapse:collapse; margin-bottom:20px; max-width:100%; width:100%"
                        width="100%">
                        <thead>
                            <tr>
                                <td class="text-center" style="text-align:center; border:1px solid #ddd; padding:8px"
                                    align="center">Total avec promotions</td>
                                <td class="text-center" style="text-align:center; border:1px solid #ddd; padding:8px"
                                    align="center">Taxe(s)</td>
                                <td class="text-center" style="text-align:center; border:1px solid #ddd; padding:8px"
                                    align="center">Finale</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center rowtotal mono"
                                    style="text-align:center; font-family:monospace; font-weight:bold; border:1px solid #ddd; padding:8px"
                                    align="center">
                                    {{ number_format($totalAvecPromoTVAHT, 2) }} DT
                                </td>
                                <td class="text-center rowtotal mono"
                                    style="text-align:center; font-family:monospace; font-weight:bold; border:1px solid #ddd; padding:8px"
                                    align="center">
                                    <ul style="margin:0; padding:0">
                                        @foreach ($devis->taxes as $tax)
                                            <li style="margin:0; padding:0">
                                                {{ $tax->name . ': ' . $tax->rate . ' %' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center rowtotal mono"
                                    style="text-align:center; font-family:monospace; font-weight:bold; border:1px solid #ddd; padding:8px"
                                    align="center">
                                    {{ number_format($totalAvecPromoTVATTC, 2) }} DT
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> --}}

                <div class="container" style="padding:20px; width:100%" width="100%">
                    <div class="row-reverse" style="display:flex; flex-wrap:wrap; justify-content:space-between">
                        <div class="col-md-6" style="width:48%" width="48%">
                            <div class="panel" style="border:1px solid #ddd; border-radius:5px; margin-bottom:20px">
                                <div class="panel-heading"
                                    style="background-color:#f7f7f7; border-bottom:1px solid #ddd; font-weight:bold; padding:10px"
                                    bgcolor="#f7f7f7">
                                    <h3 class="panel-title">Arreter le present devis a la somme</h3>
                                </div>
                                <div class="panel-body" style="padding:10px">
                                    {{ $totalLettres }}
                                </div>
                            </div>

                            <div class="panel" style="border:1px solid #ddd; border-radius:5px; margin-bottom:20px">
                                <div class="panel-body" style="padding:10px">
                                    Frais de livraison: {{ $fraisLivraison }} DT <br>
                                    Delai de livraison: ----
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" style="width:48%" width="48%">
                            <table class="table-horizontal"
                                style="border-collapse:collapse; margin-bottom:20px; width:100%" width="100%">
                                <thead>
                                    <tr>
                                        <th style="border:1px solid #ddd; padding:8px; text-align:center; background-color:#f2f2f2"
                                            align="center" bgcolor="#f2f2f2"><strong>Remise %</strong></th>
                                        <th style="border:1px solid #ddd; padding:8px; text-align:center; background-color:#f2f2f2"
                                            align="center" bgcolor="#f2f2f2">
                                            {{ $totalAvecPromoTVAHT }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($devis->taxes as $tax)
                                        <tr>
                                            <td style="border:1px solid #ddd; padding:8px; text-align:center"
                                                align="center">
                                                <ul>
                                                    <li>{{ $tax->name . ' ' . $tax->rate . '%' }}</li>
                                                </ul>
                                            </td>
                                            <?php
                                            
                                            function calculateValue($number, $rate)
                                            {
                                                $rateInDecimal = $rate / 100;
                                                return $number * $rateInDecimal;
                                            }
                                            
                                            ?>

                                            <td style="border:1px solid #ddd; padding:8px; text-align:center"
                                                align="center">
                                                {{ calculateValue($totalAvecPromoTVAHT, $tax->rate) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-primary" style="background-color:#007bff; color:white"
                                        bgcolor="#007bff">
                                        <td style="border:1px solid #ddd; padding:8px; text-align:center"
                                            align="center">totalTTC</td>
                                        <td style="border:1px solid #ddd; padding:8px; text-align:center"
                                            align="center">{{ $totalAvecPromoTVATTC }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="float-end" style="float:right">
                <img class="media-object logo mb-5" alt="cachet" width="100px"
                    src="{{ asset('storage/assets/images/parameters/' . $parameters->cachet) }}" height="100px"
                    style="margin-bottom:32px">


            </div>
        </div>
    </main>
</body>

</html>
