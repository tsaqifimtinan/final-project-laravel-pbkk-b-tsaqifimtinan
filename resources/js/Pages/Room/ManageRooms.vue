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
        <form @submit.prevent="submitRoomForm" enctype="multipart/form-data">
            <div class="mb-4">
            <label class="block text-gray-700">Patient ID</label>
            <input v-model="newRoom.patient_id" type="number" class="p-2 border rounded w-full" required />
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
            <th class="py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="room in filteredRooms" :key="room.id">
            <td class="py-2">
                <div v-if="editingRoomID === room.id">
                <input v-model="room.patient_id" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ room.patient_id }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingRoomID === room.id">
                <button @click="saveRoom(room)" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Save
                </button>
                <button @click="cancelEdit" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600 ml-2">
                    Cancel
                </button>
                </div>
                <div v-else>
                <button @click="editRoom(room.id)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
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
});

const fetchRooms = async (page = 1) => {
    try {
        const response = await fetch (`/api/rooms?page=${page}`);
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
        room_name: newRoom.value.room_name,
        description: newRoom.value.description,
        room_date: newRoom.value.room_date,
    };

    try {
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
        room.value.push(data);
        console.log('API Response:', data);
        resetForm();
        isAddingRoom.value = false;
    } catch (error) {
        console.error('Error adding room:', error);
    }
};

const resetForm = () => {
    newRoom.value = {
        patient_id: '',
    };
    isAddingRoom.value = false;
};

const cancelAddRoom = () => {
    resetForm();
};

const editRoom = (id) => {
    editingRoomID.value = id;
};

const saveRoom = async (room) => {
    try {
        console.log('Saving room:', room);

        const roomData = {
            patient_id: room.patient_id,
            room_name: room.room_name,
            description: room.description,
            room_date: room.room_date,
        };

        const response = await fetch(`/api/rooms/${room.id}`, {
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

        const index = rooms.value.findIndex(a => a.id === room.id);
        if (index !== -1) {
            rooms.value[index] = data;
        } else {
            rooms.value.push(data);
        }

        editingRoomID.value = null;
        await fetchRooms(currentPage.value);
    } catch (error) {
        console.error('Error saving room:', error);
    }
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

        console.log('room deleted:', id);
        rooms.value = rooms.value.filter(a => a.id !== id);
    } catch (error) {
        console.error('Error deleting room:', error);
    }
}

const searchRooms = () => {
    fetchRooms(currentPage.value);
}

const filteredRooms = computed(() => {
    return rooms.value.filter(room =>
        room.patient_id.toString().includes(searchQuery.value)
    );
});

onMounted(() => fetchRooms(currentPage.value));
</script>

<style scoped>

</style>