<template>
    <div>
        <h3 class="text-lg font-semibold mb-4">Manage rooms</h3>
        <input
            v-model="searchQuery"
            @input="searchRooms"
            type="text"
            placeholder="Search rooms..."
            class="mb-4 p-2 border rounded"
        />
        <button @click="toggleAddRoomForm" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
            Add room
        </button>

        <!-- Form for adding new room -->
        <div v-if="isAddingRoom" class="mb-6">
            <h4 class="font-semibold mb-2">Add New room</h4>
            <form @submit.prevent="submitRoomForm">
                <div class="mb-4">
                    <label class="block text-gray-700">Patient ID</label>
                    <input v-model="newRoom.patient_id" type="number" class="p-2 border rounded w-full" required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Room Type</label>
                    <input v-model="newRoom.room_type" type="text" class="p-2 border rounded w-full" required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Room Number</label>
                    <input v-model="newRoom.room_number" type="text" class="p-2 border rounded w-full" required />
                </div>
                <div class="flex space-x-4">
                    <button type="submit" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                    <button type="button" @click="cancelAddRoom" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 text-left">Patient ID</th>
                    <th class="py-2 text-left">Room Type</th>
                    <th class="py-2 text-left">Room Number</th>
                    <th class="py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="room in filteredRooms" :key="room.id">
                    <td class="py-2">
                        <div v-if="editingRoomID === room.id">
                            <input v-model="editingRoom.patient_id" class="p-2 border rounded" />
                        </div>
                        <div v-else>
                            {{ room.patient_id }}
                        </div>
                    </td>
                    <td class="py-2">
                        <div v-if="editingRoomID === room.id">
                            <input v-model="editingRoom.room_type" class="p-2 border rounded" />
                        </div>
                        <div v-else>
                            {{ room.room_type }}
                        </div>
                    </td>
                    <td class="py-2">
                        <div v-if="editingRoomID === room.id">
                            <input v-model="editingRoom.room_number" class="p-2 border rounded" />
                        </div>
                        <div v-else>
                            {{ room.room_number }}
                        </div>
                    </td>
                    <td class="py-2">
                        <div v-if="editingRoomID === room.id">
                            <button @click="saveRoom()" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Save
                            </button>
                            <button @click="cancelEdit" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600 ml-2">
                                Cancel
                            </button>
                        </div>
                        <div v-else>
                            <button @click="editRoom(room)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Edit
                            </button>
                            <button @click="deleteRoom(room.id)" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4">
            <button
                @click="fetchRooms(currentPage - 1)"
                :disabled="currentPage === 1"
                class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
            >
                Previous
            </button>
            <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
            <button
                @click="fetchRooms(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
            >
                Next
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const rooms = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const editingRoomID = ref(null);
const isAddingRoom = ref(false);

// New room form data
const newRoom = ref({
    patient_id: '',
    room_type: '',
    room_number: '',
});

// Editing room form data
const editingRoom = ref({
    patient_id: '',
    room_type: '',
    room_number: '',
});

const fetchRooms = async (page = 1) => {
    try {
        const response = await fetch(`/api/rooms?page=${page}`);
        if (!response.ok) {
            console.error('Error fetching rooms:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        console.log('API Response:', data);
        rooms.value = data.data;
        currentPage.value = data.current_page;
        totalPages.value = data.last_page;
    } catch (error) {
        console.error('Error fetching rooms:', error);
    }
};

const toggleAddRoomForm = () => {
    isAddingRoom.value = !isAddingRoom.value;
};

const submitRoomForm = async () => {
    const roomData = {
        patient_id: newRoom.value.patient_id,
        room_type: newRoom.value.room_type,
        room_number: newRoom.value.room_number,
    };

    try {
        // Fetch existing rooms to check for duplicate patient_id
        const existingRoomsResponse = await fetch('/api/rooms');
        if (!existingRoomsResponse.ok) {
            console.error('Error fetching existing rooms:', existingRoomsResponse.status, existingRoomsResponse.statusText);
            const errorText = await existingRoomsResponse.text();
            console.error('Error response text:', errorText);
            return;
        }

        let existingRooms = await existingRoomsResponse.json();
        console.log('Existing rooms:', existingRooms);

        // Check if the response is wrapped in a data property
        if (existingRooms.data && Array.isArray(existingRooms.data)) {
            existingRooms = existingRooms.data;
        }

        if (!Array.isArray(existingRooms)) {
            console.error('Expected an array of rooms but got:', existingRooms);
            return;
        }

        const isDuplicatePatientId = existingRooms.some(room => room.patient_id === roomData.patient_id);

        if (isDuplicatePatientId) {
            alert('A room with this patient ID already exists.');
            return;
        }

        // Proceed with form submission
        const response = await fetch('/api/rooms', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(roomData),
        });

        if (!response.ok) {
            console.error('Error adding room:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        rooms.value.push(data);
        console.log('API Response:', data);
        resetForm();
        isAddingRoom.value = false;
    } catch (error) {
        console.error('Error adding room:', error);
        alert(`Error adding room: ${error.message}`);
    }
};

const resetForm = () => {
    newRoom.value = {
        patient_id: '',
        room_type: '',
        room_number: '',
    };
    isAddingRoom.value = false;
};

const cancelAddRoom = () => {
    resetForm();
};

const editRoom = (room) => {
    editingRoomID.value = room.id;
    editingRoom.value = { ...room };
};

const saveRoom = async () => {
    try {
        console.log('Saving room:', editingRoom.value);

        const roomData = {
            patient_id: editingRoom.value.patient_id,
            room_type: editingRoom.value.room_type,
            room_number: editingRoom.value.room_number,
        };

        // Proceed with saving the room
        const response = await fetch(`/api/rooms/${editingRoomID.value}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(roomData),
        });

        if (!response.ok) {
            console.error('Error saving room:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        console.log('API Response:', data);

        const index = rooms.value.findIndex(a => a.id === editingRoomID.value);
        if (index !== -1) {
            Object.assign(rooms.value[index], data);
        }

        editingRoomID.value = null;

        // Fetch the newest data
        await fetchRooms(currentPage.value);
    } catch (error) {
        console.error('Error saving room:', error);
    }
};

const cancelEdit = () => {
    editingRoomID.value = null;
};

const deleteRoom = async (id) => {
    try {
        const response = await fetch(`/api/rooms/${id}`, {
            method: 'DELETE',
        });

        if (!response.ok) {
            console.error('Error deleting room:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        console.log('Room deleted:', id);
        rooms.value = rooms.value.filter(a => a.id !== id);
    } catch (error) {
        console.error('Error deleting room:', error);
    }
};

const searchRooms = () => {
    fetchRooms(currentPage.value);
};

const filteredRooms = computed(() => {
    return rooms.value.filter(room =>
        room.patient_id && room.patient_id.toString().includes(searchQuery.value)
    );
});

onMounted(() => fetchRooms(currentPage.value));
</script>

<style scoped>
/* Add your styles here */
</style>