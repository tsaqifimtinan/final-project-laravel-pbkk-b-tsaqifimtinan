<?php
namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController {
    public function index() {
        $rooms = Room::paginate(10);
        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'room_type' => 'required|string',
                'room_number' => 'required|string',
            ]);

            $room = new Room();
            $room->patient_id = $validatedData['patient_id'];
            $room->room_type = $validatedData['room_type'];
            $room->room_number = $validatedData['room_number'];
            $room->save();

            return response()->json(['success' => 'Room added successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Room creation failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'patient_id' => 'sometimes|integer',
                'room_type' => 'sometimes|string',
                'room_number' => 'sometimes|string',
            ]);
    
            // Find the room by ID
            $room = Room::findOrFail($id);
    
            // Update the room's data
            if (isset($validatedData['patient_id'])) {
                $room->patient_id = $validatedData['patient_id'];
            }
            if (isset($validatedData['room_type'])) {
                $room->room_type = $validatedData['room_type'];
            }
            if (isset($validatedData['room_number'])) {
                $room->room_number = $validatedData['room_number'];
            }
    
            // Save the updated room data
            $room->save();
    
            // Log the updated room data
            \Log::info('Updated room data:', $room->toArray());
    
            // Return the updated room data
            return response()->json([
                'message' => 'Room updated successfully',
                'data' => $room,
            ]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error updating room: ' . $e->getMessage());
    
            // Return a JSON response with the error message
            return response()->json([
                'message' => 'Error updating room',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id) {
        try {
            $room = Room::findOrFail($id);
            $room->delete();
            return response()->json(['message' => 'Room deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Room deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}