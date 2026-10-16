// Initialize Supabase configuration
// This file should be included in all HTML files after supabase.js

// Supabase configuration - MUST MATCH THE VALUES IN supabase.js
const SUPABASE_URL = 'https://jmzrvccldldcpzyycowj.supabase.co'; // Replace with your actual Supabase project URL
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImptenJ2Y2NsZGxkY3B6eXljb3dqIiwicm9sZSI6ImFub24iLCJpYXQiOjE3OTIxNjE5ODAsImV4cCI6MjEwNzczNzk4MH0.zyPzZf-_a-zNYuRrqNlKo27PhjYa9qYoo4lr7OV_knE'; // Replace with your actual Supabase anon key

// Initialize Supabase client
window.initSupabase = function() {
    if (typeof supabase === 'undefined') {
        console.error('Supabase library not loaded. Please include <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>');
        return null;
    }

    try {
        const supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
        console.log('Supabase client initialized successfully');
        return supabaseClient;
    } catch (error) {
        console.error('Failed to initialize Supabase client:', error);
        return null;
    }
};

// Test connection function
window.testSupabaseConnection = async function() {
    const supabase = window.initSupabase();
    if (!supabase) {
        return false;
    }

    try {
        // Try to fetch from one of the tables to test connection
        const { data, error } = await supabase
            .from('inventory_items')
            .select('count')
            .limit(1);

        if (error) {
            console.error('Supabase connection test failed:', error);
            return false;
        }

        console.log('Supabase connection test successful');
        return true;
    } catch (error) {
        console.error('Supabase connection test error:', error);
        return false;
    }
};

// Initialize when script loads
// Note: In production, you would call initSupabase() in your individual files