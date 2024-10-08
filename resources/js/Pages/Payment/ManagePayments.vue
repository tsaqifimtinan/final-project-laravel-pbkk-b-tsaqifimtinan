<template>
    <div>
        <h3 class="text-lg font-semibold mb-4">Manage payments</h3>
        <input
        v-model="searchQuery"
        @input="searchPayments"
        type="text"
        placeholder="Search payments..."
        class="mb-4 p-2 border rounded"
        />
        <button @click="toggleAddPaymentForm" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
        Add payment
        </button>

        <!-- Form for adding new payment -->
        <div v-if="isAddingPayment" class="mb-6">
        <h4 class="font-semibold mb-2">Add New Payment</h4>
        <form @submit.prevent="submitPaymentForm" enctype="multipart/form-data">
            <div class="mb-4">
            <label class="block text-gray-700">Patient ID</label>
            <input v-model="newPayment.patient_id" type="number" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Payment Method</label>
            <input v-model="newPayment.payment_method" type="number" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Description</label>
            <input v-model="newPayment.description" type="date" class="p-2 border rounded w-full" required />
            </div>
            <div class="mb-4">
            <label class="block text-gray-700">Payment Date</label>
            <input v-model="newPayment.payment_date" type="text" class="p-2 border rounded w-full" required />
            </div>
            <div class="flex space-x-4">
            <button type="submit" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            <button type="button" @click="cancelAddpayment" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Cancel
            </button>
            </div>
        </form>
        </div>
        <table class="min-w-full bg-white">
        <thead>
            <tr>
            <th class="py-2 text-left">Patient ID</th>
            <th class="py-2 text-left">Doctor ID</th>
            <th class="py-2 text-left">payment Date</th>
            <th class="py-2 text-left">payment_date</th>
            <th class="py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="payment in filteredpayments" :key="payment.id">
            <td class="py-2">
                <div v-if="editingPaymentID === payment.id">
                <input v-model="payment.patient_id" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ payment.patient_id }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingPaymentID === payment.id">
                <input v-model="payment.payment_method" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ payment.payment_method }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingPaymentID === payment.id">
                <input v-model="payment.description" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ payment.description }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingPaymentID === payment.id">
                <input v-model="payment.payment_date" class="p-2 border rounded" />
                </div>
                <div v-else>
                {{ payment.payment_date }}
                </div>
            </td>
            <td class="py-2">
                <div v-if="editingPaymentID === payment.id">
                <button @click="savePayment(payment)" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Save
                </button>
                <button @click="cancelEdit" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600 ml-2">
                    Cancel
                </button>
                </div>
                <div v-else>
                <button @click="editpayment(payment.id)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                    Edit
                </button>
                <button @click="deletepayment(payment.id)" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2">
                    Delete
                </button>
                </div>
            </td>
            </tr>
        </tbody>
        </table>
        <div class="mt-4">
        <button
            @click="fetchPayments(currentPage - 1)"
            :disabled="currentPage === 1"
            class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
        >
            Previous
        </button>
        <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
        <button
            @click="fetchPayments(currentPage + 1)"
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

const payments = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const editingPaymentID = ref(null);
const isAddingPayment = ref(false);

// New payment form data
const newPayment = ref({
    patient_id: '',
    payment_method: '',
    description: '',
    payment_date: '',
});

const fetchPayments = async (page = 1) => {
    try {
        const response = await fetch (`/api/payments?page=${page}`);
        if (!response.ok) {
            console.error('Error fetching payments:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        console.log('API Response:', data);
        payments.value = data.data;
        currentPage.value = data.current_page;
        totalPages.value = data.last_page;
    } catch (error) {
        console.error('Error fetching payments:', error);
    }
};

const toggleAddPaymentForm = () => {
    isAddingPayment.value = !isAddingPayment.value;
};

const submitPaymentForm = async () => {
    const paymentData = {
        patient_id: newPayment.value.patient_id,
        payment_method: newPayment.value.payment_method,
        description: newPayment.value.description,
        payment_date: newPayment.value.payment_date,
    };

    try {
        const response = await fetch('/api/payments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(paymentData),
        });

        if (!response.ok) {
            console.error('Error adding payment:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        payment.value.push(data);
        console.log('API Response:', data);
        resetForm();
        isAddingPayment.value = false;
    } catch (error) {
        console.error('Error adding payment:', error);
    }
};

const resetForm = () => {
    newPayment.value = {
        patient_id: '',
        payment_method: '',
        description: '',
        payment_date: '',
    };
    isAddingPayment.value = false;
};

const cancelAddpayment = () => {
    resetForm();
};

const editpayment = (id) => {
    editingPaymentID.value = id;
};

const savePayment = async (payment) => {
    try {
        console.log('Saving payment:', payment);

        const paymentData = {
            patient_id: payment.patient_id,
            payment_method: payment.payment_method,
            description: payment.description,
            payment_date: payment.payment_date,
        };

        const response = await fetch(`/api/payments/${payment.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(paymentData),
        });

        if (!response.ok) {
            console.error('Error saving payment:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        const data = await response.json();
        console.log('API Response:', data);

        const index = payments.value.findIndex(a => a.id === payment.id);
        if (index !== -1) {
            payments.value[index] = data;
        } else {
            payments.value.push(data);
        }

        editingPaymentID.value = null;
        await fetchPayments(currentPage.value);
    } catch (error) {
        console.error('Error saving payment:', error);
    }
};

const deletepayment = async (id) => {
    try {
        const response = await fetch(`/api/payments/${id}`, {
            method: 'DELETE',
        });

        if (!response.ok) {
            console.error('Error deleting payment:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response text:', errorText);
            return;
        }

        console.log('payment deleted:', id);
        payments.value = payments.value.filter(a => a.id !== id);
    } catch (error) {
        console.error('Error deleting payment:', error);
    }
}

const searchPayments = () => {
    fetchPayments(currentPage.value);
}

const filteredpayments = computed(() => {
    return payments.value.filter(payment =>
        payment.patient_id.toString().includes(searchQuery.value) ||
        payment.payment_method.toString().includes(searchQuery.value) ||
        payment.description.includes(searchQuery.value) ||
        payment.payment_date.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

onMounted(() => fetchPayments(currentPage.value));
</script>

<style scoped>

</style>