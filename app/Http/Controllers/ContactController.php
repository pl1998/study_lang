<?php

namespace App\Http\Controllers;

use App\Models\ContactRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\AboutMeNoticeMail;
use App\Models\ConfigSite;
use App\Http\Traits\EmailConfig;
class ContactController extends Controller
{
    use EmailConfig;
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'email' => 'required|email|max:191',
            'message' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $id = ContactRecord::insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);
        $this->setEmailConfig();

        Mail::to('chineseteacherelena@outlook.com')
        ->queue(new AboutMeNoticeMail(ContactRecord::find($id),ConfigSite::getConfig()));

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'status' => 200
        ]);
    }
}