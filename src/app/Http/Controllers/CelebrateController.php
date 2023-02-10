<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Type;
use App\Models\Creator;
use App\Models\Template;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CelebrationRequest;

class CelebrateController extends Controller
{

    public function create()
    {
        $templates = Template::orderBy('id','ASC','usedTimes')->get();
        $types = Type::select('id','name_' .  App::getLocale() . ' as name' , 'message_' . App::getLocale() . ' as message')->orderBy('id','ASC')->get();
        return view('index',compact('templates','types'));
    }


    public function store(CelebrationRequest $request)
    {
        $youtubeCode = $request->youtubeCode;
        $youtubeCode = Str::replace('https://youtu.be/', '', $youtubeCode);
        try{
            $create = Creator::create([
                'FirstName' => $request->FirstName,
                'LastName' => $request->LastName,
                'Message' => $request->Message,
                'PageLink' => "www.piplineapp.live/" . time() . "/" . $request->FirstName .  $request->LastName ,
                'Lang' => $request->lang,
                'TemplateID' => $request->template,
                'TypeID' => $request->type,
                'youtubeCode' => $youtubeCode,
                'startTime' => $request->startTime,
                'finishTime' => $request->finishTime
            ]);

                if($create){
                    return response() -> json([
                        'id' => $create->id,
                    ]);
                }


        }catch(Exception $ex){
            // $error = $ex -> errorInfo;
            // return $error;
            return view('errors.404');
        }

    }


    public function show($id)
    {
        $celebrate = Creator::with('type','template')->find($id);
        if($celebrate){
            return view('celebration-preview',compact('celebrate'));
        }else{
            return view('errors.404');
        }
    }

    public function visit($id , $name){
        $celebrate = Creator::with('type','template')->where('PageLink','www.online-celebration/' . $id .'/' . $name)->get()->first();

        if($celebrate){
             return view('celebration',compact('celebrate'));
        }else{
            return view('errors.404');
        }
    }

    public function changeLang(Request $request){

        if (array_key_exists($request -> lang, Config::get('languages'))) {
            Session::put('applocale', $request -> lang);
        }
        return Redirect::back();

    }


}
