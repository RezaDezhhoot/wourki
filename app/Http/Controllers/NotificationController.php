<?php

namespace App\Http\Controllers;

use App\Libraries\Swal;
use App\Notification;
use App\Process\PrNotification;
use App\Store;
use App\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::paginate(15);
        foreach($notifications as $index => $notification){
            $userIds = unserialize($notification->users);
            if(is_array($userIds)){
                $notifications[$index]->users   = User::whereIn('id' , $userIds)->select('first_name', 'last_name')->get();
            }else{
                $notifications[$index]->users  = [];
            }
        }
        return view('admin.notification.index' , compact('notifications'));
    }

    public function sendNotificationFrom()
    {
        $users = User::where('banned' , 0)->orderBy('first_name', 'asc')->paginate(20);
        $selectUsers = User::where('banned' , 0)->orderBy('first_name', 'asc')->get();
        $stores = Store::join('users' , 'users.id' , '=' , 'store.user_id')->select('store.*')->orderBy('name', 'asc')->get();
        return view('admin.notification.send' , compact('users' , 'stores' , 'selectUsers'));
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'notify' => 'array|min:1',
            'notify.*' => 'required|numeric|exists:users,id',
            'message' => 'required|string|max:120'
        ] , [
            'notify.min' => 'انتخاب حداقل یک کاربر الزامی است.',
            'notify.*.exists' => 'انتخاب کاربر نامعتبر است.',
            'message.required' => 'پیام الزامی است.',
            'message.max' => 'حداکثر طول پیام 120 کاراکتر میباشد.',
        ]);
        if ($request->has('selected')){
//            dd(111);
            $users = User::find($request->notify);
        } else {
//            dd(222);
            $users = User::all();
        }
        $gcmCodes = $users->pluck('gcm_code')->toArray();
        $notification = new PrNotification();
        $notification
            ->setTitle('وورکی')
            ->setBody($request->message)
            ->addData('type', 'message')
//                ->addData('id', $bill->id)
            ->addData('picture', null)
            ->addUsers($gcmCodes)
            ->send();
        $users = $request->notify;
        Notification::create([
            'message' => $request->message,
            'users' => serialize($users),
        ]);
        Swal::success('موفقیت آمیز.', 'ارسال اعلان با موفقیت به کاربران ارسال شد.');
        return back();
    }
}
