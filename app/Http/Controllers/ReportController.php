<?php

namespace App\Http\Controllers;

use App\Libraries\Swal;
use App\Report;
use App\ReportChat;
use App\Store;
use Illuminate\Http\Request;
use Validator;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'store' => 'nullable|numeric|exists:store,id' ,
        ] , [
            'store.numeric' => 'نام فروشگاه نامعتبر است.' ,
            'store.exists' => 'نام فروشگاه نامعتبر است.' ,
        ]);

        $stores = Store::where('visible' , 1)->select('id' , 'name')->get();
        $reports = Report::join('store' , 'store.id' , '=' , 'report_store.store_id')
            ->join('users' , 'users.id' , '=' , 'report_store.user_id')
            ->select('report_store.*' , 'users.first_name' , 'users.last_name' , 'store.name as storeName');
        if ($request->filled('store'))
            $reports->where('store.id' , $request->store);
        $reports = $reports->paginate(15);
        $reports->each(function ($report) {
           $report->update(['report.visible' => 1]);
        });
        return view('admin.report.index' , compact('reports' , 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
           'store_id' => 'required|numeric|exists:store,id' ,
           'body' => 'required|string'
        ]);
        if (auth()->guard('web')->check()) {
            Report::create([
               'user_id'  => auth()->guard('web')->user()->id ,
               'store_id' => $request->store_id ,
               'text'     => $request->body ,
               'visible'  => 0 ,
            ]);
            Swal::success('موفقیت آمیز!', 'گزارش شما برای فروشگاه مورد نظر ثبت با تشکر از شما.');
        } else
            Swal::error('ناموفق!', 'برای ثبت گزارش تخلف ابتدا ثبت نام کنید.');
        return back();
    }
    public function storeReportChat(Request $request){
        $validator = Validator::make($request->all() , [
            'chat_id' => 'required|exists:chats,id',
            'text' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->all()] , 200);
        }
        $report = new ReportChat();
        $report->user_id = auth()->guard('api')->user()->id;
        $report->chat_id = $request->chat_id;
        $report->text = $request->text;
        $report->save();
        return response()->json([ "status" => 200] , 200);
    }
    public function getAdminReportChat(Request $request){
        $request->validate([
            'user' => 'string',
        ]);
        $reports = ReportChat::join('chats', 'chats.id', '=', 'report_chat.chat_id')
        ->join('users', 'users.id', '=', 'report_chat.user_id')
        ->whereRaw('chats.sender_id=users.id OR chats.receiver_id=users.id')
        ->select('report_chat.*', 'users.first_name', 'users.last_name');
        if ($request->filled('user')){
            $reports->whereRaw("CONCAT(users.first_name,' ', users.last_name) LIKE %".$request->user."%");
        }
        $reports = $reports->paginate(15);
        $reports->each(function ($report) {
            $report->update(['seen' => true]);
            $report->contact = $report->getContact();
        });
        return view('admin.chats.report', compact('reports'));
    }
}
