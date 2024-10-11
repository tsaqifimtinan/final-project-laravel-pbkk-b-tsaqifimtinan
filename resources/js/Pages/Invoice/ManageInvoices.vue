<template>
  <div>
    <h3 class="text-lg font-semibold mb-4">Manage Invoices</h3>
    <input
      v-model="searchQuery"
      @input="searchInvoices"
      type="text"
      placeholder="Search invoices..."
      class="mb-4 p-2 border rounded"
    />
    <button @click="toggleAddInvoiceForm" class="mb-4 ml-4 p-2 bg-green-500 text-white rounded hover:bg-green-600">
      Add Invoice
    </button>

    <!-- Form for adding new Invoice -->
    <div v-if="isAddingInvoice" class="mb-6">
      <h4 class="font-semibold mb-2">Add New Invoice</h4>
      <form @submit.prevent="submitInvoiceForm" enctype="multipart/form-data">
        <div class="mb-4">
          <label class="block text-gray-700">Patient ID</label>
          <input v-model="newInvoice.patient_id" type="number" class="p-2 border rounded w-full" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Invoice Number</label>
          <input v-model="newInvoice.invoice_number" type="text" class="p-2 border rounded w-full" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Description</label>
          <input v-model="newInvoice.description" type="text" class="p-2 border rounded w-full" required />
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Total Amount</label>
          <input v-model="newInvoice.total_amount" type="number" class="p-2 border rounded w-full" required />
        </div>
        <div class="flex space-x-4">
          <button type="submit" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
          <button type="button" @click="cancelAddInvoice" class="p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Cancel
          </button>
        </div>
      </form>
    </div>
    <table class="min-w-full bg-white">
      <thead>
        <tr>
          <th class="py-2 text-left">Patient ID</th>
          <th class="py-2 text-left">Invoice Number</th>
          <th class="py-2 text-left">Description</th>
          <th class="py-2 text-left">Total Amount</th>
          <th class="py-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="invoice in filteredInvoices" :key="invoice.id">
          <td class="py-2">
            <div v-if="editingInvoiceId === invoice.id">
              <input v-model="invoice.patient_id" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ invoice.patient_id }}
            </div>
          </td>
          <td class="py-2">
            <div v-if="editingInvoiceId === invoice.id">
              <input v-model="invoice.invoice_number" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ invoice.invoice_number }}
            </div>
          </td>
          <td class="py-2">
            <div v-if="editingInvoiceId === invoice.id">
              <input v-model="invoice.description" :class="{'desc-overflow': isDescrptionOverflow(invoice.description)}" class="p-2 border rounded" />
            </div>
            <div v-else :class="{'desc-overflow': isDescrptionOverflow(invoice.description)}">
              {{ invoice.description }}
            </div>
          </td>
          <td class="py-2">
            <div v-if="editingInvoiceId === invoice.id">
              <input v-model="invoice.total_amount" class="p-2 border rounded" />
            </div>
            <div v-else>
              {{ invoice.total_amount }}
            </div>
          </td>
          <td class="py-2">
            <div v-if="editingInvoiceId === invoice.id">
              <button @click="saveInvoice(invoice)" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Save
              </button>
            </div>
            <div v-else>
              <button @click="editInvoice(invoice.id)" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                Edit
              </button>
              <button @click="deleteInvoice(invoice.id)" class="p-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2">
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="mt-4">
      <button
        @click="fetchInvoices(currentPage - 1)"
        :disabled="currentPage === 1"
        class="p-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
      >
        Previous
      </button>
      <span class="mx-2">Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="fetchInvoices(currentPage + 1)"
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

const invoices = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const searchQuery = ref('');
const editingInvoiceId = ref(null);
const isAddingInvoice = ref(false);

// New Invoice form data
const newInvoice = ref({
    patient_id: '',
    invoice_number: '',
    description: '',
    total_amount: '',
});

const fetchInvoices = async (page = 1) => {
  try {
    const response = await fetch(`/api/invoices?page=${page}`);
    if (!response.ok) {
      console.error('Error fetching invoices:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }
    
    const data = await response.json();
    console.log('API response:', data); // Log the API response
    invoices.value = data.data;
    currentPage.value = data.current_page;
    totalPages.value = data.last_page;
    console.log('Invoices array:', invoices.value); // Log the Invoices array
  } catch (error) {
    console.error('Error fetching Invoices:', error);
  }
};

const toggleAddInvoiceForm = () => {
  isAddingInvoice.value = !isAddingInvoice.value;
};

// Submit the form to add a new Invoice
const submitInvoiceForm = async () => {
  const payload = {
    patient_id: newInvoice.value.patient_id,
    invoice_number: newInvoice.value.invoice_number,
    description: newInvoice.value.description,
    total_amount: newInvoice.value.total_amount,
  };

  try {
    const response = await fetch('/api/invoices', {
      method: 'POST',
      body: JSON.stringify(payload), // Send JSON payload
      headers: {
        'Content-Type': 'application/json', // Set Content-Type header to application/json
      },
    });

    if (!response.ok) {
      const errorData = await response.json();
      console.error('Server error:', errorData);
      throw new Error('Error adding Invoice');
    }

    const data = await response.json();
    invoices.value.push(data); // Add the new Invoice to the list
    resetForm(); // Clear the form after successful submission
  } catch (error) {
    console.error('Error adding Invoice:', error);
  }
};

// Reset the form after submission or cancel
const resetForm = () => {
  newInvoice.value.patient_id = '';
  newInvoice.value.invoice_number = '';
  newInvoice.value.description = '';
  newInvoice.value.total_amount = '';
  isAddingInvoice.value = false;
};

// Cancel adding a Invoice
const cancelAddInvoice = () => {
  resetForm();
};

const editInvoice = (id) => {
  editingInvoiceId.value = id;
};

const saveInvoice = async (invoice) => {
  try {
    console.log('Saving Invoice:', invoice); // Log the Invoice being saved

    // Prepare JSON payload for sending Invoice data
    const payload = {
      patient_id: invoice.patient_id,
      invoice_number: invoice.invoice_number,
      description: invoice.description,
      total_amount: invoice.total_amount,
    };

    // Send PUT request with JSON payload
    const response = await fetch(`/api/invoices/${invoice.id}`, {
      method: 'PUT',
      body: JSON.stringify(payload), // Send the JSON payload
      headers: {
        'Content-Type': 'application/json', // Set Content-Type header to application/json
      },
    });

    if (!response.ok) {
      console.error('Error saving Invoice:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }

    const data = await response.json();
    console.log('Saved Invoice response:', data); // Log the response from the server

    // Update the Invoices list with the saved Invoice data
    const index = invoices.value.findIndex(d => d.id === invoice.id);
    if (index !== -1) {
      invoices.value[index] = {
        ...invoices.value[index],
        ...data,
      }; // Update the specific Invoice with the new data
    } else {
      // If Invoice is not in the list (unlikely in an edit case), add it
      invoices.value.push(data);
    }

    editingInvoiceId.value = null;

    // Fetch the updated list of Invoices
    await fetchInvoices(currentPage.value); // Ensure fetchInvoices is awaited to update the state correctly

  } catch (error) {
    console.error('Error saving Invoice:', error);
  }
};

const cancelEdit = () => {
  editingInvoiceId.value = null;
  fetchInvoices(currentPage.value); // Re-fetch Invoices to reset any unsaved changes
};

const deleteInvoice = async (id) => {
  try {
    const response = await fetch(`/api/invoices/${id}`, {
      method: 'DELETE',
    });

    // Check if the request was successful
    if (!response.ok) {
      console.error('Error deleting Invoice:', response.status, response.statusText);
      const errorText = await response.text();
      console.error('Error response text:', errorText);
      return;
    }

    // Remove the Invoice from the list after successful deletion
    invoices.value = invoices.value.filter(invoice => invoice.id !== id);
    console.log('Invoice deleted successfully');
  } catch (error) {
    console.error('Error deleting Invoice:', error);
  }
};

const searchInvoices = () => {
  fetchInvoices(currentPage.value);
};

const filteredInvoices = computed(() => {
  return invoices.value.filter(invoice =>
    // Ensure Invoice.description exists and is a string before calling toLowerCase
    (invoice.description && typeof invoice.description === 'string') &&
    invoice.description.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const isDescrptionOverflow = (description) => {
    return description.length > 50; // Adjust the length as needed
};

onMounted(() => fetchInvoices(currentPage.value));
</script>

<style scoped>
.desc-overflow {
    font-size: 0.8rem; /* Adjust the font size as needed */
}
</style>