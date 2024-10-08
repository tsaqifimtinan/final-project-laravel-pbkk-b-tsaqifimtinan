<?php
namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController {
    public function index() {
        $rooms = Room::paginate(10);
        return response()->json($rooms);
    }

    public function store (Request $request) {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
            ]);

            Room::create([
                'patient_id' => $validatedData['patient_id'],
            ]);
            
            return response()->json([
                'message' => 'Room created',
                'data' => $validatedData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Room creation failed'], 500);
        }
    }

    public function update (Request $request, $id) {
        try {
            $room = Room::findOrFail($id);
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
            ]);

            $room->update([
                'patient_id' => $validatedData['patient_id'],
            ]);

            return response()->json([
                'message' => 'Room updated',
                'data' => $validatedData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Room update failed'], 500);
        }
    }

    public function destroy($id) {
        try {
            $room = Room::findOrFail($id);
            $room->delete();
            return response()->json(['message' => 'Room deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Room deletion failed'], 500);
        }
    }
}