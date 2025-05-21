<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Notes;
use App\Http\Controllers\Controller;

class NotesController extends Controller
{

    public function index()
    {
        try{
            $userId = auth()->id();
            $note = Notes::where('user_id', $userId)->get();
            return response()->json($note,200);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something Error',
                'error'=>$e->getMessage()
            ],500);

        }
    }


    public function store(Request $request)
    {
        $notesData=$request->validate([
            'title'=>'required|string|max:255',
            'content'=>'required|string|max:1500'
        ]);



        try{
            $note=Notes::create([
                'user_id' => auth()->id(),
                'title' => $notesData['title'],
                'content' => $notesData['content']
            ]);
            return response()->json($note,200);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something Error',
                'error'=>$e->getMessage()
            ],500);

        }
    }


    public function show(string $id)
    {

        try{
            $note=Notes::findOrFail($id);
            return response()->json($note,200);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return response()->json(['message'=>'notes not found'],400);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something Error',
                'error'=>$e->getMessage()
            ],500);

        }
    }




    public function update(Request $request, string $id)
    {
        $notes=$request->validate([
            'title'=>'sometimes|required|string|max:255',
            'content'=>'sometimes|required|string|max:1500'
        ]);
        try{
            $note=Notes::findOrFail($id);
            $note->update($notes);
            return response()->json($note,200);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return response()->json(['message'=>'notes not found'],404);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something Error',
                'error'=>$e->getMessage()
            ],500);

        }
    }


    public function destroy(string $id)
    {
        try{
            $note=Notes::findOrFail($id);
            $note->delete();
            return response()->json(['message'=>'note deleted successfully'],200);
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return response()->json(['message'=>'notes not found'],400);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Something Error',
                'error'=>$e->getMessage()
            ],500);

        }
    }
}
