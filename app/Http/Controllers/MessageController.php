<?php

// namespace App\Http\Controllers;

// use App\Models\Message;
// use Illuminate\Http\Request;

// class MessageController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     public function index()
//     {
//         $messages = Message::all();
//         return response()->json([
//             'success' => true,
//             'msg' => 'Messages retrieved successfully',
//             'data' => $messages
//         ], 200);
//     }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         //
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         try {
//             $request->validate(
//                 [
//                     'sender' => 'required|string',
//                     'receiver' => 'required|string',
//                     'msg' => 'required|string',
//                 ],
//                 [
//                     'sender.required' => 'Sender is required',
//                     'receiver.required' => 'Receiver is required',
//                     'message.required' => 'Message is required',
//                 ]
//             );

//             $message = Message::create($request->all());
//         } catch (\Exception $error) {
//             return response()->json([
//                 'success' => false,
//                 'msg' => 'Error occurred while sending message',
//                 'error' => $error->getMessage()
//             ], 500);
//         }
//         return response()->json([
//             'success' => true,
//             'msg' => 'Message sent successfully',
//             'data' => $message
//         ], 201);
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show(string $id)
//     {
//         try {
//             $message = Message::findOrFail($id);
//             return response()->json([
//                 'success' => true,
//                 'msg' => 'Message retrieved successfully',
//                 'data' => $message
//             ], 200);
//         } catch (\Exception $error) {
//             return response()->json([
//                 'success' => false,
//                 'msg' => 'Error occurred while retrieving message',
//                 'error' => $error->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit(Message $message)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, string $id)
//     {
//         try {
//             $request->validate(
//                 [
//                     'sender' => 'required|string',
//                     'receiver' => 'required|string',
//                     'msg' => 'required|string',
//                 ],
//                 [
//                     'sender.required' => 'Sender is required',
//                     'receiver.required' => 'Receiver is required',
//                     'message.required' => 'Message is required',
//                 ]
//             );

//             $message = Message::findOrFail($id);
//             $message->update($request->all());
//             return response()->json([
//                 'success' => true,
//                 'msg' => "Message from $message->sender updated successfully",
//                 'data' => $message
//             ], 200);
            
//         } catch (\Exception $error) {
//             return response()->json([
//                 'success' => false,
//                 'msg' => 'Error occurred while updating message',
//                 'error' => $error->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(string $id)
//     {
//         try {
//             $message = Message::findOrFail($id);
//             $message->delete();
//             return response()->json([
//                 'success' => true,
//                 'msg' => "Message from $message->sender deleted successfully"
//             ], 200);
//         } catch (\Exception $error) {
//             return response()->json([
//                 'success' => false,
//                 'msg' => 'Error occurred while deleting message',
//                 'error' => $error->getMessage()
//             ], 500);
//         }
//     }
// }



namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Listar todas as mensagens
     */
    public function index()
    {
        $messages = Message::with('sender', 'receiver')->get();
        return response()->json([
            'success' => true,
            'msg' => 'Messages retrieved successfully',
            'data' => $messages
        ], 200);
    }

    /**
     * Enviar uma nova mensagem
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'sender_id' => 'required|exists:users,id',
                    'receiver_id' => 'required|exists:users,id',
                    'msg' => 'required|string',
                ],
                [
                    'sender_id.required' => 'Sender is required',
                    'sender_id.exists' => 'Sender must be a valid user',
                    'receiver_id.required' => 'Receiver is required',
                    'receiver_id.exists' => 'Receiver must be a valid user',
                    'msg.required' => 'Message is required',
                ]
            );

            $message = Message::create($validated);

            return response()->json([
                'success' => true,
                'msg' => 'Message sent successfully',
                'data' => $message->load('sender', 'receiver')
            ], 201);

        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Error occurred while sending message',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar mensagem específica
     */
    public function show(string $id)
    {
        try {
            $message = Message::with('sender', 'receiver')->findOrFail($id);

            return response()->json([
                'success' => true,
                'msg' => 'Message retrieved successfully',
                'data' => $message
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Error occurred while retrieving message',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar uma mensagem
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate(
                [
                    'sender_id' => 'required|exists:users,id',
                    'receiver_id' => 'required|exists:users,id',
                    'msg' => 'required|string',
                ]
            );

            $message = Message::findOrFail($id);
            $message->update($validated);

            return response()->json([
                'success' => true,
                'msg' => 'Message updated successfully',
                'data' => $message->load('sender', 'receiver')
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Error occurred while updating message',
                'error' => $error->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar uma mensagem
     */
    public function destroy(string $id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->delete();

            return response()->json([
                'success' => true,
                'msg' => "Message deleted successfully"
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'msg' => 'Error occurred while deleting message',
                'error' => $error->getMessage()
            ], 500);
        }
    }
}
