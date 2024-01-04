<?php

namespace App\Http\Controllers\Contact;

use App\Models\Support;
use App\Events\TechnicalSupport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\Contact\SupportRequest;
use DateTime;
use DateTimeZone;

class SupportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(SupportRequest $request)
    {
        $support = Support::create($request->validated());
        // Event
        
            TechnicalSupport::dispatch($support);
       
        $this->createsupportOnMonday($support);
        return back()->with('success', __('Message Send Successfully'));
    }

    function createsupportOnMonday(Support $support)
    {


        $token = "";


        $apiUrl = 'https://api.monday.com/v2';
        $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

        $dtz = new DateTimeZone("Asia/Riyadh"); //Your timezone
        $now = new DateTime(date("Y-m-d"), $dtz);
        $B = $now->format("Y-m-d");
        // return $now;

        $query = 'mutation ($myItemName: String!, $columnVals: JSON!) { create_item (board_id:1328061754, item_name:$myItemName, column_values:$columnVals) { id } }';
        $vars = [
            'myItemName' => $support->name,
            'columnVals' => json_encode([
                'status' => ['label' => 'New'],
                'date4' => ['date' => $B],
                'text' =>  $support->email,
                'text4' =>  $support->phone,
                'text2' =>  $support->message
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
        logger(json_encode($responseContent));
    }
}
