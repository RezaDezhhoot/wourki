@extends('admin.master')
@section('styles')
    <style>
        table tbody th {
            font-size: 12px;
            font-weight: normal;
            color: #202020;
        }

        table thead th {
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }

        .dropdown-menu li a {
            border-radius: 0;
        }

        .list-unstyled li, textarea {
            font-size: 12px !important;
        }
    </style>
@endsection
@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-lg-12">
                                    @include('frontend.errors')
                                        {{ csrf_field() }}
                                        <h4 class="m-t-0 header-title"><b>لیست گفت و گو های کاربر : {{$selectedUser->first_name.' '.$selectedUser->last_name}}</b></h4><br>
                                        <p class="text-muted font-13"></p>
                                        <div class="p-20">
                                            <form action="{{ route('admin.chats.delete') }}"
                                             method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <table class="table table-striped m-0">
                                                <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkAll"></th>
                                                    <th>ردیف</th>
                                                    <th>کاربر</th>
                                                    <th> فروشگاه ها</th>
                                                    <th>شماره موبایل</th>
                                                    <th>تاریخ آخرین پیام</th>
                                                    <th>مشاهده پیام ها</th>
                                                </tr>
                                                </thead>

                                                <tbody id="sortable-list">
                                                @foreach($chats as $index => $chat)
                                                        @php
                                                         if($chat->sender_id = $selectedUser->id){
                                                            $user = $chat->receiver;
                                                         }
                                                         else{
                                                            $user = $chat->sender;
                                                         }   
                                                        @endphp
                                                        <tr>
                                                            <th><input type="checkbox" name="chat_ids[]"
                                                                       value="{{ $chat->id }}" class="chat_ids"></th>
                                                            <th scope="row">{{ ++$index }}</th>
                                                            <th>{{ $user->first_name .' '. $user->last_name }}</th>
                                                            <th>
                                                                @php
                                                                 $stores = $user->stores;   
                                                                @endphp
                                                                @if(count($stores) > 0)
                                                                @foreach ($stores as $i => $s)
                                                                
                                                                    <a style="font-weight: bold;"
                                                                       href="{{ route('listOfProductSeller' , $s->user_name) }}">{{ $s->name }}</a>
                                                                    @if($i != count($user->stores) - 1)
                                                                        -
                                                                    @endif
                                                                    @endforeach
                                                                @else ثبت نشده
                                                                @endif
                                                            </th>
                                                            <th>{{ $user->mobile }}</th>
                                                            <th>{{ $chat->last_message_datetime}}</th>
                                                            <th><a style="font-weight: bold;"
                                                                   href="{{ route('admin.chats.messages' , ['chat_id' => $chat->id , 'user_id' => $user->id]) }}">مشاهده</a>
                                                            </th>
                                                        </tr>
                                                        @endforeach
                                                </tbody>
                                            </table>
                                            <button class="btn btn-danger" type="submit">حذف گفت و گو های انتخاب شده</button>
                                            </form>
                                        </div>
                                        <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container -->

        </div> <!-- content -->
        @include('admin.footer')
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#checkAll').click(function () {
                if ($(this).prop('checked'))
                    $('.userId').prop('checked', true);
                else
                    $('.userId').prop('checked', false);
            });

        });


        $('#user').select2({
            placeholder: 'بخشی از نام کاربر را وارد کنید...',
            ajax: {
                url: '{{ route('users.get_via_ajax.in_support_page') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term
                    };
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.first_name + ' ' + item.last_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: false
            }
        });
        $('#store').select2({
            placeholder: 'بخشی از نام فروشگاه را وارد کنید...',
            ajax: {
                url: '{{ route('stores.get_via_ajax.in_support_page') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term
                    };
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: false
            }
        });

    </script>
@endsection