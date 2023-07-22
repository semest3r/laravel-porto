<?php

namespace App\Http\Controllers\Subscriber;

use App\Exports\SubscribersExport;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;

class SubscriberController extends Controller
{
    public function getSubscribers(Request $request)
    {
        $request->input('limit') ? $limit = $request->input('limit') : $limit = 15;
        $subscribers = Subscriber::query()->when($request->input('search'), function ($query, $search) {
            $query->where('email', 'LIKE', '%' . $search . '%');
        })->paginate($limit);
        return response()->json($subscribers, 200);
    }

    public function getSubscriber($id)
    {
        $subscriber = Subscriber::find($id);
        if (!$subscriber) return response()->json(['message' => 'Data Not Found'], 404);

        return response()->json($subscriber, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'email' => ['required', 'email', 'unique:App\Models\Subscriber,email']
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $create = Subscriber::create([
            'uuid' => Uuid::uuid4(),
            'email' => $request->input('email'),
            'is_active' => true
        ]);
        return response()->json(['message' => 'Create Subscriber Success'], 201);
    }


    public function update(Request $request, $id)
    {
        $subscriber = Subscriber::find($id);
        if (!$subscriber) return response()->json(['message' => ['Data Not Found']], 404);

        $validator = Validator::make($request->input(), [
            'email' => ['required', 'email', Rule::unique('users')->ignore($subscriber->id)]
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $subscriber->email = $request->input('email');
        $subscriber->save();
        return response()->json(['message' => 'Update Success'], 200);
    }

    public function delete($id)
    {
        $subscriber = Subscriber::find($id);
        if (!$subscriber) return response()->json(['message' => ['Data Not Found']], 404);
        $subscriber->delete();
        return response()->json(['message' => 'Delete Success'], 200);
    }

    public function editSubscriberStatus(Request $request, $id)
    {
        $subscriber = Subscriber::where('uuid', $id)->first();
        if (!$subscriber) return response()->json(['message' => ['Data Not Found']], 404);
        $subscriber->is_active = $subscriber->is_active ? false : true;
        $subscriber->save();
        return response()->json(['message' => 'Update Status Success'], 200);
    }

    public function csv(){
        return Excel::download(new SubscribersExport, 'subscribers.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function pdf()
    {
        $data = Subscriber::all();
        $pdf = Pdf::loadView('subscribersPdf', ['subscribers' => $data]);
        return $pdf->download();
    }
}
