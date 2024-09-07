@php
    use App\Mail\DevisMailing;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis N°{{ $devis->ref }}</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 20px;">
    <div style="max-width: 800px; margin: 0 auto;">

        <!-- Header Section -->
        <div style="text-align: center; margin-bottom: 50px;">
            <h1 style="font-size: 28px; margin: 0;">Devis N°{{ $devis->ref }}</h1>
            <h4 style="font-size: 18px; color: #6c757d; margin: 10px 0;">En date du: {{ $devis->date }}</h4>
        </div>

        <!-- Client and Company Information -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 50px;">
            <div style="width: 48%;">
                <h4 style="font-size: 20px; margin: 0;">Client:</h4>
                <p style="margin: 5px 0;">{{ $devis->client->nom }}</p>
                <p style="margin: 5px 0;">{{ $devis->client->adresse }}</p>
                <p style="margin: 5px 0;">{{ $devis->client->tel1 }}</p>
            </div>
            <div style="width: 48%; text-align: right;">
                <h4 style="font-size: 20px; margin: 0;">Votre entreprise:</h4>
                <p style="margin: 5px 0;">Nom: {{ $parameters->titre }}</p>
                <p style="margin: 5px 0;">Adresse: {{  $parameters->adresse }}</p>
                <p style="margin: 5px 0;">Telephone: {{  $parameters->tel }}</p>
                <p style="margin: 5px 0;">M.F: {{  $parameters->numero_fiscal }}</p>
            </div>
        </div>

        <!-- Products Table -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 50px;">
            <thead>
                <tr>
                    <th style="border-bottom: 2px solid #ddd; padding: 10px; text-align: left;">Produit</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 10px; text-align: right;">Quantité</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 10px; text-align: right;">Prix Unitaire</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 10px; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($devis->produits as $item)


                    <tr>
                        <td style="border-bottom: 1px solid #ddd; padding: 10px;">{{ $item->titre }}</td>
                        <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: right;">{{ $item->pivot->qte }}
                        </td>
                        <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: right;">
                            {{ number_format(DevisMailing::PrixGrosOrVente($item, $item->pivot->qte), 2, ',', ' ') }}
                        </td>
                        <td style="border-bottom: 1px solid #ddd; padding: 10px; text-align: right;">
                            {{ number_format($item->pivot->qte * DevisMailing::PrixGrosOrVente($item, $item->pivot->qte), 2, ',', ' ') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </tbody>
        </table>

        <div style="display: flex; flex-direction: row-reverse; justify-content: space-between">
            <div style="flex: 0 0 50%; max-width: 50%;">
                <div style="border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 1px 1px rgba(0,0,0,.05);">
                    <div
                        style="padding: 10px 15px; border-bottom: 1px solid #ddd; border-top-left-radius: 3px; border-top-right-radius: 3px;">
                        <h3 style="margin-top: 0; margin-bottom: 0; font-size: 16px; color: inherit;">Arreter le present
                            devis a la somme</h3>
                    </div>
                    <div style="padding: 15px;">
                        {{ $totalLettres }}
                    </div>
                </div>
                <div
                    style="border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 1px 1px rgba(0,0,0,.05); margin-top: 15px;">
                    <div style="padding: 15px;">
                        <div style="padding: 15px;">
                            Frais de livraison: {{ $devis->totalFraisLivraison }} DT <br />
                            Delai de Delai de livraison: ----
                        </div>
                    </div>
                </div>
            </div>
            <div style="flex: 0 0 50%; max-width: 50%;">
                <div style="border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 1px 1px rgba(0,0,0,.05);"
                    @if(count($devis->items) > 0) @endif>
                    <div
                        style="padding: 10px 15px; border-bottom: 1px solid #ddd; border-top-left-radius: 3px; border-top-right-radius: 3px;">
                        <h3 style="margin-top: 0; margin-bottom: 0; font-size: 16px; color: inherit;">Services</h3>
                    </div>
                    <div style="padding: 15px;">
                        <table style="width: 100%; max-width: 100%; margin-bottom: 20px; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ddd; padding: 8px;">Description</th>
                                    <th style="border: 1px solid #ddd; padding: 8px;">Quantity</th>
                                    <th style="border: 1px solid #ddd; padding: 8px;">Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($devis->items as $service)
                                    <tr>
                                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $service->description }}</td>
                                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $service['qte'] }}</td>
                                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $service->cost }} DT</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px;"></td>
                                    <td style="border: 1px solid #ddd; padding: 8px;"></td>
                                    <td style="border: 1px solid #ddd; padding: 8px;">
                                        <strong>{{ $devis->totalServices }} DT</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    style="border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 1px 1px rgba(0,0,0,.05); margin-top: 15px;">
                    <div>
                        <table style="width: 100%; max-width: 100%; margin-bottom: 20px; border-collapse: collapse;">
                            <tbody>
                                @foreach(DevisMailing::productsWithPromo($devis) as $prod)
                                    <tr>
                                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                            <ul style="list-style: none; padding: 0; margin: 0;">
                                                <li>{{$prod['titre'] }}: {{ $prod['promo'] }} %</li>
                                            </ul>
                                        </td>
                                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">


                                            {{ DevisMailing::calculateValue(DevisMailing::calculateTotalProd(false, $prod, $prod['pivot']['qte']), $prod['promo']) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style="background-color: #337ab7; color: white;">
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><strong>Total
                                            remises</strong></td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                        {{ $devis->totalRemises }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <table style="width: 100%; max-width: 100%; margin-bottom: 20px; border-collapse: collapse;">
                    <tbody>
                        @foreach($devis->taxes as $t)
                            <tr>
                                @if($t['rate'] !== null)
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                            <li>{{ $t['name'] }} {{ $t['rate'] }}%</li>
                                        </ul>
                                    </td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                        {{ DevisMailing::calculateValue((float) $devis->totalHT + (float) $devis->totalServices + (float) $devis->totalFraisLivraison - (float) $devis->totalRemises, $t['rate']) }}
                                    </td>
                                @elseif($t['name'] == 'Droit Timbre')
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                            <li>{{ $t['name'] }}</li>
                                        </ul>
                                    </td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                            <li>{{ $parameters->timbre_fiscale }}</li>
                                        </ul>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        <tr style="background-color: #337ab7; color: white;">
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">totalTTC</td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $devis->totalTTC }}
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <!-- Totals Section -->
        <!-- <div style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span style="font-size: 16px;">Sous-total:</span>
                <span style="font-size: 16px; text-align: right;">{{ number_format($devis->subtotal, 2, ',', ' ') }} €</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span style="font-size: 16px;">TVA ({{ $devis->tax_percentage }}%):</span>
                <span style="font-size: 16px; text-align: right;">{{ number_format($devis->tax, 2, ',', ' ') }} €</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-weight: bold;">
                <span style="font-size: 18px;">Total:</span>
                <span style="font-size: 18px; text-align: right;">{{ number_format($devis->total, 2, ',', ' ') }} €</span>
            </div>
        </div> -->


    </div>
</body>

</html>