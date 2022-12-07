@extends('frontend.master')
@section('style')
    <title>وورکی | حساب کاربری من | تبلیغات</title>
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet"
    />
    <style>
        .chat-item{
            width: 100%;
            background-color: #F5F5F5;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            padding: 5px;
            border: 0.5px solid #ddd;
            cursor: pointer;
        }
        .img-thumbnail{
            border: 0;
            border-radius: 1000px;
            height: 60px;
            width: 60px
        }
        .person-info{
            margin-right: 20px;
        }
        .message-count{
            justify-self: flex-end;
            margin-right: auto;
            margin-left: 20px;
        }
        .selected{
            background-color: #F22822;
            color: white;
            border: 0.5px solid #F22822;
        }
        #messages{

            height: 439.5px;
            min-height: 439.5px;
            max-height: 439.5px;
            overflow-y: scroll;
            display : flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 20px;
            padding-top: 70px
            
        }
        #messages-container{
            position: relative;
            padding-bottom: 60px;
            background-image : url('https://wourki.com/image/chat.jpg');
            display: none;
        }
        #chats{
            max-height: 439.5px;
            overflow-y: scroll;
        }
        .send-message{
            align-self: flex-start;
            background-color: #FC494C;
            color: white;
            max-width:300px;
            padding: 10px;
            padding-bottom: 25px;
            border-radius: 10px;
            border-top-right-radius: 0;
            position: relative;
            min-width: 150px;
            margin-top: 10px;

        }
        .received-message{
            align-self: flex-end;
            background-color: #E91E63;
            color: white;
            max-width:300px;
            padding: 10px;
            padding-bottom: 25px;
            border-radius: 10px;
            border-top-left-radius: 0;
            position: relative;
            min-width: 150px;
            margin-top: 10px;
        }
        .message-datetime{
            position: absolute;
            bottom: 2px;
            right: 5px;
            font-size: 7pt;

        }
        .message-delete{
            position: absolute;
            bottom: 2px;
            left: 5px;
            cursor: pointer;
        }
        .chat-controls{
            height:60px;
            width:100%;
            position: absolute;
            bottom: 0;
            right: 0;
            z-index: 10;
            border-top: solid 1px #ddd;
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: #F5F5F5;
        }
        
        .attachment{
            height:60px;
            width:100%;
            position: absolute;
            bottom: 60px;
            right: 0;
            z-index: 10;
            border-top: solid 1px #ddd;
            background-color: #F5F5F5;
            display: none;
        }
        .message-attachment{
            width: 100%;
            background-color : #64202F;
            border-radius : 10px;
            margin-bottom:20px;
            cursor: pointer

        }
        #chat-header{
            height:50px;
            width:100%;
            position: absolute;
            top: 0;
            right: 0;
            z-index: 10;
            border-bottom: solid 1px #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #F5F5F5;
        }
        .send-button{
            background-color: white;
            padding: 8px 40px;
            border: solid 0;
            border-radius: 5px;
        }
        #message-to-send{
            width: 80%
        }
        #info-top{
            margin-left : 10px;
        }
        #last-visit{
            margin-right : 10px;
        }
        .more-button{
            margin-right: 10px;
        }
        li{
            list-style: none
        }
        #block-message{
            display: none;
        }
        .no-scroll::-webkit-scrollbar {
        display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scroll {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        }
        .filepond{
            position: relative;
            width:100%;
            top:7px;
        }
@media only screen and (max-width: 992px) {
  #messages-container {
    margin-top: 30px;
  }
}

@media only screen and (max-width: 768px) {
    .send-button{
    padding: 8px 15px;
    }
}

    </style>
@endsection
@section('content')
    @include('frontend.my-account.tabs')
        <section class="container-fluid ads-page">
        <div class="row">
            <div class="wrapper">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-comment"></i>
                                گفت و گو ها
                            </div>
                            <div class="panel-body">
                                    @if(count($chats) == 0)
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <div class="alert alert-warning">
                                                {!! nl2br($noChatMessage) !!}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                <div class="row" style="padding: 10px">
                                    <div class="col-xs-12 col-md-5" id="chats">
                                        <!-- Admin Support Chat -->
                                            <div class="chat-item" id="support">
                                                <img src="{{ url()->to('image/logo.jpg')}} " class="img-thumbnail"
                                                height="60px" width="60px" />
                                                <span class="person-info">پشتیبانی</span><img style="margin-right: 10px" src="{{ url()->to('img/tick.png')}} " width="15px" height="15px" />
                                                @if($adminNewMessages != 0)
                                                <span class="message-count btn btn-xs btn-success">{{  $adminNewMessages}}</span>
                                                @endif
                                            </div>
                                        @foreach ($chats as $chat)
                                            <div class="chat-item" id="{{$chat->id}}">
                                                <img src="{{!$chat->contact->thumbnail_photo ? url()->to('img/avatar.png') : url()->to('image/store_photos'). '/' . $chat->contact->thumbnail_photo }} " class="img-thumbnail"
                                                height="60px" width="60px" />
                                                <span class="person-info">{{  $chat->contact->first_name. ' ' . $chat->contact->last_name}}</span>
                                                @if($chat->newMessages != 0)
                                                <span class="message-count btn btn-xs btn-success">{{  $chat->newMessages}}</span>
                                                @endif
                                            </div>
                                        @endforeach
   
                                        
                                    </div>                                   

                                    <div class="col-xs-12 col-md-7 no-scroll" id="messages-container">

                                        <div id="messages">

                                        </div>
                                        <section id="chat-header">
                                            <li class="dropdown more-button">
                                                <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a style="display: flex;align-items:center;padding:10px" data-toggle="modal" data-target="#productModal" id="add-product" href=""><i style="color: skyblue;font-size : 15px;margin-left:5px" class="fa fa-shopping-cart"></i>اشاره به محصول یا خدمت</a></li>
                                                    <li><a style="display: flex;align-items:center;padding:10px" id="block-chat" href=""><i style="color: red;font-size : 15px;margin-left:5px" class="fa fa-ban"></i>مسدود کردن</a></li>
                                                    <li><a style="display: flex;align-items:center;padding:10px" data-toggle="modal" data-target="#reportModal" href=""><i style="color: orange;font-size : 15px;margin-left:5px" class="fa fa-exclamation-circle"></i>گزارش تخلف</a></li>
                                                    <li><a style="display: flex;align-items:center;padding:10px" data-toggle="modal" data-target="#deleteModal" href=""><i style="color:red;font-size : 15px;margin-left:5px" class="fa fa-trash"></i>حذف گفت و گو</a></li>
                                                    <li><a style="display: flex;align-items:center;padding:10px" href="{{route('chats.rules.get')}}"><i style="color:green;font-size : 15px;margin-left:5px" class="fa fa-book"></i>قوانین</a></li>
                                                </ul>
                                                <span id="last-visit"></span>
                                            </li>
                                                <span id="info-top">
                                                     
                                                </span>
                                        </section>
                                        <section class="attachment" id="attachment">

                                        </section>
                                        <section class="chat-controls">
                                            @if ($blocked)
                                            <div id="admin-block-message" class="list-group-item list-group-item-danger">
                                                شما از طرف مدیریت سایت مسدود شده اید و اجازه گفت و گو ندارید
                                            </div>
                                            @endif
                                            <div id="block-message" class="list-group-item list-group-item-danger">
                                                شما از طرف مخاطب مسدود شده این و اجازه گفت و گو ندارید
                                            </div>
                                            <button class="send-button" id="send-button"><i class="fa fa-paper-plane fa-lg"></i></button>
                                            <input type="text" id="message-to-send" name="quantity" placeholder="پیام خود را تایپ کنید ..." class="form-control input-md">

                                        </section>
                                        <input class="filepond" 
                                            data-allow-reorder="true"
                                            data-max-file-size="3MB"
                                            name="file" type="file" id="file" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">اشاره به محصول یا خدمت</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="products">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-pink" id="close-btn-third" data-dismiss="modal">بستن</button>
                    </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">گزارش تخلف</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="report-text">متن گزارش خود را بنویسید:</label>
                            <textarea style="height:100px;" name="body" id="report-text" cols="30"
                                        required rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button onclick="report(event)" class="btn btn-default">گزارش</button>
                        <button class="btn btn-pink" id="close-btn" data-dismiss="modal">بستن</button>
                    </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">حذف گفت و گو</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <p style="color:red">آیا اطمینان دارید؟ گفت و گو حذف شده غیر قابل بازیابی خواهد بود.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button onclick="deleteChat(event)"  class="btn btn-default">حذف</button>
                        <button class="btn btn-pink" id="close-btn-second" data-dismiss="modal">بستن</button>
                    </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script>
        // Get a reference to the file input element
        var inputElement = document.querySelector('#file');
                FilePond.setOptions({
                    server:{
                        url : '{{route("upload.temp")}}',
                        headers : {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    } 
                });
        FilePond.registerPlugin(FilePondPluginImagePreview)
        // Create a FilePond instance
        var pond = FilePond.create(
            inputElement,
            {
                labelIdle : '<div style="display:flex;justify-content:center;align-items:center;cursor:pointer"><span style="font-size:12px;margin-right:10px">جهت درج تصویر لطفا فایل خود را به اینجا بکشید و یا کلیک کنید </span> <i class="fa fa-picture-o" style="font-size:20px"></i></div>',
                imagePreviewHeight: 170,
                stylePanelLayout: 'compact',
                credits : null,
                allowMultiple: false,
                labelFileProcessing : ' در حال آپلود لطفا منتظر بمانید',
                labelFileProcessingComplete : 'آپلود فایل کامل شد',
                labelTapToCancel : 'لغو آپلود',
                labelTapToUndo : 'حذف تصویر'
            });

    </script>
    <script>
        
        var page = 1;
        var pageRestriction = true;
        var selectedId = -1;
        var selectedChat = null;
        var chatable_name = null;
        var chatable_id = null;
        $('#messages').on('scroll', function() {
            let div = $(this).get(0);
            let d = $(this);
            if(div.scrollTop == 0 && pageRestriction && selectedId != 'support') {
                var firstMessage = $(this).children().eq(1);
                page = page + 1;
                getMessages(selectedId , page);
                d.scrollTop(firstMessage.position().top);
        }
        });
        $("#message-to-send").keyup(function(event) {
            if (event.keyCode === 13) {
                $("#send-button").click();
            }
        });
        function removeAttachment(){
            chatable_id=null;
            chatable_name=null;
            $('#attachment').hide();
        }
        function addAttachment(type , id , name , photo){
            chatable_name = type;
            chatable_id = id;
            $('#attachment').html(`<div style="display:flex;align-items:center;padding:10px">
                                <img width="80" height="80" src="${photo}" alt="photo" />
                                <div style="margin-right:20px" style="display:flex;flex-direction:column; justify-content:center;cursor:pointer">
                                <span>${name}</span>
                                </div>
                                <button onclick="removeAttachment()" type="button" style="margin-right : 30px" class="close"
                                aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                                </div>`);
            $('#attachment').show();
            $('#close-btn-third').click();
            $('#close-btn-fourth').click();
        }
        $('#add-product').click(function(){
            $.ajax({
                type: 'get',
                url: '{{url()->to("api/chats/product/get")}}' + '/' + selectedId.toString(),
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                success: function (response) {
                        var products = response;
                        $('#products').html(``);
                        for(var i=0; i<products.length;i++){
                            $('#products').append(`<div onclick="addAttachment('${products[i].store_type}' , ${products[i].id} , '${products[i].name}' , '${products[i].photo_url}')" class="col-md-3" style="display:flex;align-items:center;cursor:pointer">
                                <img width="60" height="60" src="${products[i].photo_url}" alt="photo" />
                                <div style="display:flex;flex-direction:column; justify-content:space-between;cursor:pointer">
                                <span>${products[i].name}</span>
                                <span>${products[i].price} تومان</span>
                                </div>
                                </div>`);
                        }
                    }
                })
        });
                function deleteChat(event){
                $.ajax({
                type: 'delete',
                url: '{{url()->to("api/chats/delete")}}' + '/' + selectedId.toString(),
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                success: function (response) {
                    document.getElementById('close-btn-second').click();
                    swal('موفقیت آمیز', 'گفت و گو با موفقیت حذف شد', 'success');
                    document.getElementById(selectedId.toString()).remove();
                    document.getElementById('messages-container').style.display = 'none'
                    }
                })
            }
                function report(){
                $.ajax({
                type: 'post',
                url: '{{url()->to("api/chats/report")}}',
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                data: {
                    chat_id : selectedId,
                    text : document.getElementById('report-text').value
                },
                success: function (response) {
                    document.getElementById('close-btn').click();
                    swal('موفقیت آمیز', 'گزارش شما با موفقیت ثبت شد', 'success');
                    
                    }
                })
            }
        $('document').ready(function (){

        // getting query params
        var urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has('chat')){
                selectedId = urlParams.get('chat');
                var selectedProduct = urlParams.get('product');
                //getting messages
                $('.chat-item').removeClass('selected');
                $('#' + selectedId).addClass('selected');
                $('#messages-container').show();
                if({{!$blocked ? 'true' : 'false'}}){
                $('#admin-block-message').hide();
                $('#send-button').show();
                $('#message-to-send').show();
                $('#chat-header').css('display' , 'flex');
                getMessages(selectedId , 1)
                }
                else{
                    $('#messages').html(``);
                    $('#admin-block-message').show();
                    $('#send-button').hide();
                    $('#message-to-send').hide();
                    $('#chat-header').hide();
                }
                if(urlParams.has('product')){
                    $.ajax({
                type: 'get',
                url: '{{url()->to("api/chats/single-product/get")}}?chat_id=' + selectedId.toString() + '&product_id=' + selectedProduct.toString(),
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                success: function (response) {
                    addAttachment(response.store.store_type , response.id , response.name , response.photo_url)
                }
            });
                }
                // removing query params
                var uri = window.location.href.toString();
                if (uri.indexOf("?") > 0) {
                    var clean_uri = uri.substring(0, uri.indexOf("?"));
                    window.history.replaceState({}, document.title, clean_uri);
                }
            }
            $('#block-chat').click(function(event){
                event.preventDefault();
                $.ajax({
                type: 'patch',
                url: '{{url()->to("api/chats/block/")}}/' + selectedId.toString(),
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                data: {
                    block : selectedChat.you_blocked ? 0 : 1
                },
                success: function (response) {
                    if(!selectedChat.you_blocked){
                    $('#block-chat').html(`<i style="color: green;font-size : 15px;margin-left:5px" class="fa fa-undo"></i>رفع انسداد`);
                    swal('موفقیت آمیز', 'کاربر مورد نظر مسدود شد', 'success');
                    selectedChat.you_blocked = true;
                    }
                    else{
                    $('#block-chat').html(`<i style="color: red;font-size : 15px;margin-left:5px" class="fa fa-ban"></i>مسدود کردن`);
                    swal('موفقیت آمیز', 'کاربر مورد نظر از حالت مسدود خارج شد', 'success');
                    selectedChat.you_blocked = false;
                    }

                }
            });

            });
            $('.chat-item').click(function(event){
                page=1;
                pageRestriction = true;
                
                //changing all chats to not selected
                $('.chat-item').removeClass('selected');
                $(this).addClass('selected');
                $('#messages-container').show();
                if($(this).attr('id') == 'support'){
                    $('#file').show();
                    //getting messages
                    getAdminMessages()
                    selectedId = $(this).attr('id');
                    $('#admin-block-message').hide();
                    $('#send-button').show();
                    $('#message-to-send').show();
                    $('#chat-header').hide();
                    return;
                }
                $('#file').hide();
                //getting messages
                if({{!$blocked ? 'true' : 'false'}}){
                $('#admin-block-message').hide();
                $('#send-button').show();
                $('#message-to-send').show();
                $('#chat-header').css('display' , 'flex');
                getMessages($(this).attr('id') , 1)
                selectedId = $(this).attr('id');
                }
                else{
                    $('#messages').html(``);
                    $('#admin-block-message').show();
                    $('#send-button').hide();
                    $('#message-to-send').hide();
                    $('#chat-header').hide();

                }
            });
            $('#send-button').click(function(event){
                var message = $('#message-to-send').val();
                if(message && message != '')
                sendMessage(message,selectedId);
                $('#message-to-send').val('');
            });
        });
        function sendMessage(message , chat_id){
            if(chat_id == 'support'){
                $.ajax({
                type: 'post',
                url: '{{url()->to("api/chats/support/send")}}',
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                data : {
                    message,
                    file : $('input[name="file"]').val() == "" ? null : $('input[name="file"]').val()
                },
                success: function (response) {
                pond.removeFile();
                $('#messages').append(`
                <div id="support-delete-${response.message_sent.id}" class="send-message">
                    ${response.message_sent.attached_file != null ? `<a href="{{URL::to('my-account/chats/images')}}/${response.message_sent.id}" ><img style="border-radius: 0;width:100%;height:auto;max-width:500px;margin-bottom:10px" src="{{URL::to('my-account/chats/images')}}/${response.message_sent.id}" /></a>` : ``}
                    <span class="message-content not-processed">${response.message_sent.message}</span>
                    <span class="message-datetime">${response.message_sent.updated_at} </span>
                    <span class="message-delete"><i class="fa fa-trash"></i></span>
                </div>`);
                    $('.message-content.not-processed').each(function(i , el){
                        transformHyperlink(el);
                        el.classList.remove('not-processed');
                    })
                    var d = $('#messages');
                    d.scrollTop(d.prop("scrollHeight"));
                }
            });
                return;
            }
            var data ={
                    content : message
                }
            if(chatable_id != null){
                data.chatable_id = chatable_id
            }
            if(chatable_name != null){
                data.chatable_name = chatable_name
            }
            $.ajax({
                type: 'post',
                url: '{{url()->to("api/chats/message/send/")}}/' + chat_id.toString(),
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                data
                ,
                success: function (response) {
                        $('#messages').append(`
                <div id="delete-${response.message_sent.id}" class="send-message">
                    ${response.message_sent.chatable ? `<div class="message-attachment" onclick="goToChatable('${response.message_sent.chatable.user_name ?  response.message_sent.chatable.user_name : "null"}',${response.message_sent.chatable_id})">
                        <div style="display:flex;align-items:center;padding:10px">
                        <img width="80" height="80" src="${response.message_sent.chatable.photo_url}" alt="photo" style="border-radius : 5px" />
                        <div style="margin-right:5px" style="display:flex;flex-direction:column; justify-content:center;cursor:pointer">
                        <span>${response.message_sent.chatable.name}</span>
                        </div>
                        </div>
                    </div>` : ``}
                    <span class="message-content not-processed">${response.message_sent.content}</span>
                    <span class="message-datetime">${response.message_sent.persian_datetime} </span>
                    <span class="message-delete" onclick="deleteMessage(${response.message_sent.id})"><i class="fa fa-trash"></i></span>
                </div>`);
                $('#attachment').hide();
                var d = $('#messages');
                d.scrollTop(d.prop("scrollHeight"));
                 chatable_id=null;
                 chatable_name=null;
                    $('.message-content.not-processed').each(function(i , el){
                        transformHyperlink(el);
                        el.classList.remove('not-processed');
                    })
                }
            });
        }
        function goToChatable(slug,id){
            if(slug == "null")
                window.location.href = "{{url()->to('product')}}" + "/" + id
                else{
                window.location.href = "{{url()->to('store')}}" + "/" + slug
                }
        }
        function getMessages(chat_id , page){

            $.ajax({
                type: 'get',
                url: '{{url()->to("api/chats/message/get/paginated/")}}/' + chat_id.toString() + "?page=" + page.toString(),
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                data: {},
                success: function (response) {
                    var messages = response.messages.data;
                    if(response.messages.last_page <= page){
                        pageRestriction = false
                    }
                    if(page == 1){
                    //updating top-info
                    var stores = response.chat.contact.stores;
                    if(stores.length > 0){
                        $('#info-top').html(`فروشگاه ها : `);
                        for(var i = 0; i < stores.length ; i++){
                            $('#info-top').append(`<a href="{{url()->to('store')}}/${stores[i].user_name}">${stores[i].name}</a>`)
                            if(i != stores.length - 1) $('#info-top').append(' - ');
                        }
                    }
                    $('#messages').html(``);
                    selectedChat = response.chat;
                    var link = '';
                    var name = '';
                    $('#last-visit').html(`آخرین بازدید : ${selectedChat.persian_last_visit_datetime}`);
                    if(selectedChat.you_blocked)
                    $('#block-chat').html(`<i style="color: green;font-size : 15px;margin-left:5px" class="fa fa-undo"></i>رفع انسداد`);
                    else{
                    $('#block-chat').html(`<i style="color: red;font-size : 15px;margin-left:5px" class="fa fa-ban"></i>مسدود کردن`);
                    }
                    if(selectedChat.contact_blocked){
                    $('#send-button').hide();
                    $('#message-to-send').hide();
                    $('#block-message').show();
                    }else{
                    $('#send-button').show();
                    $('#message-to-send').show();
                    $('#block-message').hide();
                    }
                    $("#messages").append(`<div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="alert alert-warning" id="warning-message">
                                    {!! nl2br($noMessages) !!}
                                </div>
                            </div>
                        </div>`);
                    }
                    else{
                        $("#warning-message").remove();
                    }
                    if(page == 1){
                    for(var i = 0;i<messages.length;i++){
                        if(messages[i].is_sent == true){
                            if(messages[i].read == true)
                            $('#messages').append(`
                                        <div id="delete-${messages[i].id}" class="send-message">
                                            ${messages[i].chatable ? `<div class="message-attachment" onclick="goToChatable('${messages[i].chatable.user_name ? messages[i].chatable.user_name : "null"}',${messages[i].chatable_id})">
                                                 <div style="display:flex;align-items:center;padding:10px">
                                                 <img width="80" height="80" src="${messages[i].chatable.photo_url}" alt="photo" style="border-radius : 5px" />
                                                 <div style="margin-right:5px" style="display:flex;flex-direction:column; justify-content:center;cursor:pointer">
                                                 <span>${messages[i].chatable.name}</span>
                                                 </div>
                                                 </div>
                                             </div>` : ``}
                                            <span class="message-content not-processed">${messages[i].content}</span>
                                            <span class="message-datetime"><i class="fa fa-check"></i> ${messages[i].persian_datetime} </span>
                                            <span class="message-delete" onclick="deleteMessage(${messages[i].id})"><i class="fa fa-trash"></i></span>
                                        </div>`);
                            else
                            $('#messages').append(`
                                        <div id="delete-${messages[i].id}" class="send-message">
                                            ${messages[i].chatable ? `<div class="message-attachment" onclick="goToChatable('${messages[i].chatable.user_name ? messages[i].chatable.user_name  : "null"}',${messages[i].chatable_id})">
                                                 <div style="display:flex;align-items:center;padding:10px">
                                                 <img width="80" height="80" src="${messages[i].chatable.photo_url}" alt="photo" style="border-radius : 5px" />
                                                 <div style="margin-right:5px" style="display:flex;flex-direction:column; justify-content:center;cursor:pointer">
                                                 <span>${messages[i].chatable.name}</span>
                                                 </div>
                                                 </div>
                                             </div>` : ``}
                                            <span class="message-content not-processed">${messages[i].content}</span>
                                            <span class="message-datetime">${messages[i].persian_datetime} </span>
                                            <span class="message-delete" onclick="deleteMessage(${messages[i].id})" ><i class="fa fa-trash"></i></span>
                                        </div>`);
                                
                        }
                        else{
                            $('#messages').append(`
                            <div class="received-message">
                                ${messages[i].chatable ? `<div class="message-attachment" onclick="goToChatable('${messages[i].chatable.user_name ? messages[i].chatable.user_name  : "null"}',${messages[i].chatable_id})">
                                                 <div style="display:flex;align-items:center;padding:10px">
                                                 <img width="80" height="80" src="${messages[i].chatable.photo_url}" alt="photo" style="border-radius : 5px" />
                                                 <div style="margin-right:5px" style="display:flex;flex-direction:column; justify-content:center;cursor:pointer">
                                                 <span>${messages[i].chatable.name}</span>
                                                 </div>
                                                 </div>
                                </div>` : ``}
                                <span class="message-content not-processed">${messages[i].content}</span>
                                <span class="message-datetime">${messages[i].persian_datetime}</span>
                            </div>`);
                        }
                    }
                    var d = $('#messages');
                    d.scrollTop(d.prop("scrollHeight"));
                }
                else{
                    for(var i = messages.length - 1;i>= 0;i--){
                        if(messages[i].is_sent == true){
                            if(messages[i].read == true)
                            $('#messages').prepend(`
                                        <div id="delete-${messages[i].id}" class="send-message">
                                            ${messages[i].chatable ? `<div class="message-attachment" onclick="goToChatable('${messages[i].chatable.user_name ? messages[i].chatable.user_name  : "null"}',${messages[i].chatable_id})">
                                                 <div style="display:flex;align-items:center;padding:10px">
                                                 <img width="80" height="80" src="${messages[i].chatable.photo_url}" alt="photo" style="border-radius : 5px" />
                                                 <div style="margin-right:5px" style="display:flex;flex-direction:column; justify-content:center;cursor:pointer">
                                                 <span>${messages[i].chatable.name}</span>
                                                 </div>
                                                 </div>
                                             </div>` : ``}
                                            <span class="message-content not-processed">${messages[i].content}</span>
                                            <span class="message-datetime"><i class="fa fa-check"></i> ${messages[i].persian_datetime} </span>
                                            <span class="message-delete" onclick="deleteMessage(${messages[i].id})"><i class="fa fa-trash"></i></span>
                                        </div>`);
                            else
                            $('#messages').prepend(`
                                        <div id="delete-${messages[i].id}" class="send-message">
                                                 ${messages[i].chatable ? `<div class="message-attachment" onclick="goToChatable('${messages[i].chatable.user_name ? messages[i].chatable.user_name  : "null"}',${messages[i].chatable_id})">
                                                 <div style="display:flex;align-items:center;padding:10px">
                                                 <img width="80" height="80" src="${messages[i].chatable.photo_url}" alt="photo" style="border-radius : 5px" />
                                                 <div style="margin-right:5px" style="display:flex;flex-direction:column; justify-content:center;cursor:pointer">
                                                 <span>${messages[i].chatable.name}</span>
                                                 </div>
                                                 </div>
                                             </div>` : ``}
                                            <span class="message-content not-processed">${messages[i].content}</span>
                                            <span class="message-datetime">${messages[i].persian_datetime} </span>
                                            <span class="message-delete" onclick="deleteMessage(${messages[i].id})" ><i class="fa fa-trash"></i></span>
                                        </div>`);
                                
                        }
                        else{
                            $('#messages').prepend(`
                            <div class="received-message">
                                            ${messages[i].chatable ? `<div class="message-attachment" onclick="goToChatable('${messages[i].chatable.user_name ? messages[i].chatable.user_name  : "null"}',${messages[i].chatable_id})">
                                                 <div style="display:flex;align-items:center;padding:10px">
                                                 <img width="80" height="80" src="${messages[i].chatable.photo_url}" alt="photo" style="border-radius : 5px" />
                                                 <div style="margin-right:5px" style="display:flex;flex-direction:column; justify-content:center;cursor:pointer">
                                                 <span>${messages[i].chatable.name}</span>
                                                 </div>
                                                 </div>
                                             </div>` : ``}
                                <span class="message-content not-processed">${messages[i].content}</span>
                                <span class="message-datetime">${messages[i].persian_datetime}</span>
                            </div>`);
                        }
                    }
                }
                    $('.message-content.not-processed').each(function(i , el){
                        transformHyperlink(el);
                        el.classList.remove('not-processed');
                    }) 
                }
            });
        }
            function getAdminMessages(){
            $.ajax({
                type: 'get',
                url: '{{url()->to("api/chats/support/get")}}',
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                success: function (response) {
                    $('#messages').html(``);
                    var messages = response.messages

                    for(var i = 0;i<messages.length;i++){
                        if(messages[i].user_id != null){
                            if(messages[i].view == 1)
                            $('#messages').append(`
                                        <div id="support-delete-${messages[i].id}" class="send-message">
                                            ${messages[i].attached_file != null ? `<a href="{{URL::to('my-account/chats/images')}}/${messages[i].id}" ><img style="border-radius: 0;width:100%;height:auto;max-width:500px;margin-bottom:10px" src="{{URL::to('my-account/chats/images')}}/${messages[i].id}" /></a>` : ``}
                                            <span class="message-content not-processed">${messages[i].message}</span>
                                            <span class="message-datetime"><i class="fa fa-check"></i> ${messages[i].updated_at} </span>
                                            <span class="message-delete" onclick="deleteAdminMessage(${messages[i].id})"><i class="fa fa-trash"></i></span>
                                        </div>`);
                            else
                            $('#messages').append(`
                                        <div id="support-delete-${messages[i].id}" class="send-message">
                                            ${messages[i].attached_file != null ? `<a href="{{URL::to('my-account/chats/images')}}/${messages[i].id}" ><img style="border-radius: 0;width:100%;height:auto;max-width:500px;margin-bottom:10px" src="{{URL::to('my-account/chats/images')}}/${messages[i].id}" /></a>` : ``}
                                            <span class="message-content not-processed">${messages[i].message}</span>
                                            <span class="message-datetime">${messages[i].updated_at} </span>
                                            <span class="message-delete" onclick="deleteAdminMessage(${messages[i].id})" ><i class="fa fa-trash"></i></span>
                                        </div>`);
                                
                        }
                        else{
                            $('#messages').append(`
                            <div class="received-message">
                                ${messages[i].attached_file != null ? `<a href="{{URL::to('my-account/chats/images')}}/${messages[i].id}" ><img style="border-radius: 0;width:100%;height:auto;max-width:500px;margin-bottom:10px" src="{{URL::to('my-account/chats/images')}}/${messages[i].id}" /></a>` : ``}
                                <span class="message-content not-processed">${messages[i].message}</span>
                                <span class="message-datetime">${messages[i].updated_at}</span>
                            </div>`);
                        }
                    }
                    var d = $('#messages');
                    d.scrollTop(d.prop("scrollHeight"));
                    $('.message-content.not-processed').each(function(i , el){
                        transformHyperlink(el);
                        el.classList.remove('not-processed');
                    })
                }
            });
            }
            function deleteMessage(message_id){
            $.ajax({
                type: 'delete',
                url: '{{url()->to("api/chats/message/delete/")}}/' + message_id.toString(),
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                success: function (response) {
                    document.getElementById('delete-' + message_id).remove();
                }
            });
            }
            function deleteAdminMessage(message_id){
                $.ajax({
                type: 'delete',
                url: '{{url()->to("api/chats/support/delete/")}}/' + message_id.toString(),
                headers : {
                    Authorization : 'Bearer {{Cookie::get("X_AJAX_TOKEN")}}'
                },
                success: function (response) {
                    document.getElementById('support-delete-' + message_id).remove();
                }
            });
            }

    </script>
@endsection