// Supabase configuration and service layer
// Replace localStorage with Supabase database

// Supabase configuration (you'll need to replace these with your actual Supabase project details)
const SUPABASE_URL = 'https://jmzrvccldldcpzyycowj.supabase.co'; // Replace with your Supabase URL
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImptenJ2Y2NsZGxkY3B6eXljb3dqIiwicm9sZSI6ImFub24iLCJpYXQiOjE3OTIxNjE5ODAsImV4cCI6MjEwNzczNzk4MH0.zyPzZf-_a-zNYuRrqNlKo27PhjYa9qYoo4lr7OV_knE'; // Replace with your Supabase anon key

// Export configuration for use by other modules (like init-supabase.js)
window.SUPABASE_CONFIG = {
    url: SUPABASE_URL,
    anonKey: SUPABASE_ANON_KEY
};

// Initialize Supabase client
let supabaseClient = null;

function initSupabase() {
    if (!supabaseClient) {
        // Check if supabase is available (in case we're using a CDN)
        if (typeof supabase !== 'undefined') {
            supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
        } else {
            console.error('Supabase client not loaded. Please include supabase.js from CDN.');
        }
    }
    return supabaseClient;
}

// Database service functions
const dbService = {
    // Inventory Items table functions
    async getItems() {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('inventory_items')
                .select('*')
                .order('created_at', { ascending: false });

            if (error) throw error;
            // Ensure we always return an array, even if data is null or not an array
            return Array.isArray(data) ? data : [];
        } catch (error) {
            console.error('Error fetching inventory items:', error);
            return [];
        }
    },

    async addItem(item) {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('inventory_items')
                .insert([item])
                .select();

            if (error) throw error;
            // When inserting, data should be an array with the inserted item(s)
            // We inserted one item, so we expect one back
            return Array.isArray(data) && data.length > 0 ? data[0] : null;
        } catch (error) {
            console.error('Error adding inventory item:', error);
            throw error;
        }
    },

    async updateItem(id, updates) {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('inventory_items')
                .update(updates)
                .eq('id', id)
                .select();

            if (error) throw error;
            // When updating, data should be an array with the updated item(s)
            // We updated one item, so we expect one back
            return Array.isArray(data) && data.length > 0 ? data[0] : null;
        } catch (error) {
            console.error('Error updating inventory item:', error);
            throw error;
        }
    },

    async deleteItem(id) {
        const supabase = initSupabase();
        try {
            const { error } = await supabase
                .from('inventory_items')
                .delete()
                .eq('id', id);

            if (error) throw error;
            return true;
        } catch (error) {
            console.error('Error deleting inventory item:', error);
            throw error;
        }
    },

    // Serial Numbers table functions
    async getSerialNumbers() {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('serial_numbers')
                .select('*')
                .order('created_at', { ascending: false });

            if (error) throw error;
            // Ensure we always return an array, even if data is null or not an array
            return Array.isArray(data) ? data : [];
        } catch (error) {
            console.error('Error fetching serial numbers:', error);
            return [];
        }
    },

    async addSerialNumber(sn) {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('serial_numbers')
                .insert([sn])
                .select();

            if (error) throw error;
            // When inserting, data should be an array with the inserted item(s)
            // We inserted one item, so we expect one back
            return Array.isArray(data) && data.length > 0 ? data[0] : null;
        } catch (error) {
            console.error('Error adding serial number:', error);
            throw error;
        }
    },

    async updateSerialNumber(id, updates) {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('serial_numbers')
                .update(updates)
                .eq('id', id)
                .select();

            if (error) throw error;
            // When updating, data should be an array with the updated item(s)
            // We updated one item, so we expect one back
            return Array.isArray(data) && data.length > 0 ? data[0] : null;
        } catch (error) {
            console.error('Error updating serial number:', error);
            throw error;
        }
    },

    async deleteSerialNumber(id) {
        const supabase = initSupabase();
        try {
            const { error } = await supabase
                .from('serial_numbers')
                .delete()
                .eq('id', id);

            if (error) throw error;
            return true;
        } catch (error) {
            console.error('Error deleting serial number:', error);
            throw error;
        }
    },

    // Get serial numbers for a specific item
    async getSerialNumbersByItemId(itemId) {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('serial_numbers')
                .select('*')
                .eq('item_id', itemId)
                .order('created_at', { ascending: false });

            if (error) throw error;
            return data || [];
        } catch (error) {
            console.error('Error fetching serial numbers for item:', error);
            return [];
        }
    },

    // Repair Records table functions
    async getRepairs() {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('repair_records')
                .select('*')
                .order('created_at', { ascending: false });

            if (error) throw error;
            // Ensure we always return an array, even if data is null or not an array
            return Array.isArray(data) ? data : [];
        } catch (error) {
            console.error('Error fetching repair records:', error);
            return [];
        }
    },

    async addRepair(repair) {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('repair_records')
                .insert([repair])
                .select();

            if (error) throw error;
            // When inserting, data should be an array with the inserted item(s)
            // We inserted one item, so we expect one back
            return Array.isArray(data) && data.length > 0 ? data[0] : null;
        } catch (error) {
            console.error('Error adding repair record:', error);
            throw error;
        }
    },

    async updateRepair(id, updates) {
        const supabase = initSupabase();
        try {
            const { data, error } = await supabase
                .from('repair_records')
                .update(updates)
                .eq('id', id)
                .select();

            if (error) throw error;
            // When updating, data should be an array with the updated item(s)
            // We updated one item, so we expect one back
            return Array.isArray(data) && data.length > 0 ? data[0] : null;
        } catch (error) {
            console.error('Error updating repair record:', error);
            throw error;
        }
    },

    async deleteRepair(id) {
        const supabase = initSupabase();
        try {
            const { error } = await supabase
                .from('repair_records')
                .delete()
                .eq('id', id);

            if (error) throw error;
            return true;
        } catch (error) {
            console.error('Error deleting repair record:', error);
            throw error;
        }
    }
};

// Initialize Supabase when script loads
// Note: You need to include the supabase JS library in your HTML files
// <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>

// Export for use in other modules
window.dbService = dbService;