<?php

namespace Modules\Admin\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\App\Models\Email;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Modules\Admin\App\Models\Category;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emails = Email::all();
        return view('admin::email.index', compact('emails'));
    }

    /**
     * Store the form.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Email::create($data);

        // Send mail to admin
        Mail::raw("Name: {$data['name']}\nEmail: {$data['email']}\n\nMessage:\n{$data['message']}", function ($message) use ($data) {
            $message->to('admin@example.com')
                    ->subject($data['subject']);
        });

        return response()->json(['message' => 'Message sent'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $email = Email::findOrFail($id);
        $email->delete();

        return redirect()->route('admin.email.index')->with('success', 'Email sikeresen törölve!');
    }
}
