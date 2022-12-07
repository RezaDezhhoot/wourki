<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Guild;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuildApi extends Controller
{
    public function index()
    {
        $guilds = Guild::with('categories')->get();
        foreach($guilds as $index =>  $guild){
            if($guild->pic){
                $guilds[$index]->pic = url()->to('/icon') . '/' . $guild->pic;
            }
        }
        return response()->json($guilds , 200);
    }

    public function getCategories(Request $request)
    {
        $guildCategories = Category::where('guild_id' , $request->id)->get();
        foreach($guildCategories as $index => $row){
            if($row->icon){
                $guildCategories[$index]->icon = url()->to('/icon') . '/' . $row->icon;
            }
        }
        if (count($guildCategories) > 0)
            return response()->json($guildCategories , 200);
        else
            return response()->json([ "status" => 200] , 400);
    }
}
