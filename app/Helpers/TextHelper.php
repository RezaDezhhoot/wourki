<?php
// function transformHyperlinks() {
// const postmsg = document.getElementById("start").value;

// const urlRegex = /(((https?:\/\/)|(www\.))[^\s]+)/g;
// const detectURL = postmsg.match(urlRegex);

// let resultPost = postmsg

// detectURL.forEach(url => {
// resultPost = resultPost.replace(url, '<a href="' + url + '" role="link"> ' + url.trim() + '</a>')
// })

// document.getElementById("end").innerHTML = resultPost;
// }

namespace App\Helpers;


class TextHelper
{
    public static function transformHyperlinks($str){
        return preg_replace(
            '%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s',
            '<a href="$1">$1</a>',
            $str
        );
    }
}
