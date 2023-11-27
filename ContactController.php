<?php

namespace App\Http\Controllers\Contact;

use App\Events\ContactUs;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\Contact\ContactRequest;
use DateTime;
use DateTimeZone;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        setSeoData(title: __('Rehan') . ' | ' . __('Contact Us'));
        return view('site.contacts');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ContactRequest $request)
    {
         $contact  = Contact::create($request->validated());
        // Event 
       
            ContactUs::dispatch($contact);
    
        $this->createContactOnMonday($contact);
        return back()->with('success', __('Message Send Successfully'));
    }


    function createContactOnMonday(Contact $contact)
    {


        $token = env('MONDAY_TOKEN');



        $apiUrl = 'https://api.monday.com/v2';
        $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

        $dtz = new DateTimeZone("Asia/Riyadh"); //Your timezone
        $now = new DateTime(date("Y-m-d"), $dtz);
        $B = $now->format("Y-m-d");
        // return $now;


        $type = '';
        if ($contact->user_typ == 0) {
            $type = 'Investor';
        } else if ($contact->user_type == 1) {
            $type = 'Fund Manager';
        } else {
            $type =         'Real Estate Developer';
        }

        $query = 'mutation ($myItemName: String!, $columnVals: JSON!) { create_item (board_id:1328010160, item_name:$myItemName, column_values:$columnVals) { id } }';
        $vars = [
            'myItemName' => $contact->name,
            'columnVals' => json_encode([
                'status' => ['label' => 'New'],
                'date4' => ['date' => $B],
                'text' =>  $contact->email,
                'text4' =>  $contact->phone,
                'text9' =>  $type,
                'text3' =>  $contact->message
            ])
        ];

        $data = @file_get_contents($apiUrl, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $headers,
                'content' => json_encode(['query' => $query, 'variables' => $vars]),
            ]
        ]));
        $responseContent = json_decode($data, true);

        // echo json_encode($responseContent);
        // logger(json_encode($responseContent));
    }
}
