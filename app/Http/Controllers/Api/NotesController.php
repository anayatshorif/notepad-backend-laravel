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
            $note = Notes::where('user_id', $userId)
                ->where('status', true)
                ->get();
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
        try {
            $note = Notes::findOrFail($id);

            if ($note->status) {

                $note->status = false;
                $note->trashed_at = now();
                $message = 'Note moved to trash';
            } else {

                $note->status = true;
                $note->trashed_at = null;
                $message = 'Note restored from trash';
            }

            $note->save();

            return response()->json(['message' => $message, 'status' => $note->status], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Note not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function trash()
    {
        try {
            $userId = auth()->id();
            $trashedNotes = Notes::where('user_id', $userId)
                ->where('status', false)
                ->get();

            return response()->json($trashedNotes, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function restore(string $id)
    {
        try {
            $note = Notes::findOrFail($id);

            if ($note->status) {
                return response()->json(['message' => 'Note is already active'], 400);
            }

            $note->status = true;
            $note->trashed_at = null;
            $note->save();

            return response()->json([
                'message' => 'Note restored successfully',
                'note' => $note
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Note not found'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
