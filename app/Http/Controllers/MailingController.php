<?php

namespace App\Http\Controllers;

use App\Mail\DevisMailing;
use App\Mail\DevisMailingAttachement;
use App\Mail\PromotionMailing;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Historique;
use App\Models\Parameter;
use App\Models\Produits;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailingController extends Controller
{
    public function sendDevis($id)
    {
        try {

            $devis = Devis::with('client', 'produits')->findOrFail($id);
            $parameters = Parameter::findOrFail(1); // Assuming parameters have a single row with id 1

            Mail::to($devis->client->email)->send(new DevisMailing($devis, $parameters));

            Historique::create([
                'table' => 'Devis',
                'id_record' => $devis->id,
                'action' => 'E-mail to: ' . $devis->client->nom,
                'data_before' => null,
                'data_after' => null,
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);

            return response()->json(['message' => 'Quote sent successfully!']);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function showDevis($id)
    {
        $devis = Devis::with('client', 'produits')->findOrFail($id);
        $parameters = Parameter::findOrFail(1); // Assuming parameters have a single row with id 1

        return view('Email.devis', compact('devis', 'parameters'));
    }
    public function showPromotion($id)
    {
        $promotion = Promotion::with('produits')->findOrFail($id);

        return view('Email.promotion', compact('devis'));
    }

    public function sendPromotion(Request $request, $id)
    {
        try {
            $promotion = Promotion::with('produits')->findOrFail($id);
            $clients = Client::whereIn('id', $request->clients)->get();

            $sentClients = "";
            foreach ($clients as $client) {
                Mail::to($client->email)->send(new PromotionMailing($promotion, $client));
                $sentClients .= ' Mr.' . $client->nom . ',';
            }

            $sentClients = rtrim($sentClients, ',');

            Historique::create([
                'table' => 'Promotion',
                'id_record' => $promotion->id,
                'action' => 'E-mail to: ' . $sentClients,
                'data_before' => null,
                'data_after' => null,
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);

            return response()->json(['message' => 'Newsletter sent successfully', 'promotion' => $promotion]);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }


    public function sendPdfToClient(Request $request, $clientId)
    {
        try {
            $request->validate([
                'file' => 'required|string',
            ]);

            $pdfBase64 = $request->input('file');
            $pdfContent = base64_decode(preg_replace('#^data:application/pdf;base64,#i', '', $pdfBase64));

            $client = Client::findOrFail($clientId);
            Mail::to($client->email)->send(new DevisMailingAttachement($pdfContent));

            // Historique::create([
            //     'table' => 'Devis',
            //     'id_record' => $devis->id,
            //     'action' => 'E-mail to: ' . $devis->client->nom,
            //     'data_before' => null,
            //     'data_after' => null,
            //     'changed_at' => now(),
            //     'changed_by' =>  null,
            // ]);

            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => $th->getMessage()]);
        }
    }
}
