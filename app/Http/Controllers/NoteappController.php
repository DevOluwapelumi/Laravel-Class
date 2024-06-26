<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NoteappController extends Controller
{
    public function shownote(){
        return view('noteapp.createnote');
    }
    public function addnote(Request $req){
    //    dd($req);  --- To get the Details

    // $req->image->getClientOriginalName(); -- To get the file image name

     $newname=time().$req->image->getClientOriginalName();
     $move=$req->image->move(public_path('images/'), $newname);
     if($move){
            $insert=DB::table('noteapp_table')->insert([
           'title'=>$req->title,
            'content'=>$req->content,
            'user_id'=>Auth::user()->id,
            'user_profile'=>$newname
          ]);
           if($insert) {
                return redirect('/displaynote');
           }
           else {
            return ('Not sent');
           };
     } else {
        return ' not moved';
     }
    //  return $req->image->getSize(); --- To get the image Size
    }

    public function displaynote()
{
    $user = Auth::user();
    if ($user) {
        $select = DB::table('noteapp_table')
                    ->where('user_id', $user->id)
                    ->get();
    } else {
        $select = DB::table('noteapp_table')
                    ->get();
    }

    return view('noteapp.displaynote', [
        'allnote' => $select
    ]);
}


public function displayContact()
{
    $user = Auth::user();
    if ($user) {
        $select = DB::table('contact_table')
                    ->where('user_id', $user->id)
                    ->get();
    } else {
        $select = DB::table('contact_table')
                    ->where('user_id')
                    ->get();
    }

    return view('Contact.displayContact', [
        'allcontact' => $select
    ]);
}

    public function show($id){
        $show=DB::table('noteapp_table')->where('note_id', $id)->first();
        return view('noteapp.viewnote', ['note'=>$show]);
    }

    public function edit($noteid){
        $edit=DB::table('noteapp_table')->where('note_id', $noteid)->first();
        // return $edit;
        return view('noteapp.editnote', ['notes'=>$edit]);
    }

    public function update(Request $req, $id){
        $update=DB::table('noteapp_table')->where('note_id', $id)->update(
            [
                'title'=> $req->title,
                'content'=> $req->content,
            ]
        );
        return redirect('/displaynote');
    }

public function delete($id)
{
    $delete=DB::table('noteapp_table')->where('note_id', $id)->delete();
    // Redirect back with a success message
    return redirect('/displaynote')->with('success', 'Note deleted successfully');
}

}
