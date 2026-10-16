# Supabase Setup Instructions

## Step 1: Create a Supabase Project
1. Go to [supabase.com](https://supabase.com)
2. Sign up or log in to your account
3. Click "New Project"
4. Fill in the project details:
   - Project name: `it-equipment-management` (or any name you prefer)
   - Database password: Choose a strong password
   - Region: Select closest to your location
5. Click "Create new project"

## Step 2: Get Your API Keys
1. Once your project is created, go to Project Settings (gear icon)
2. Go to the "API" section
3. Copy these values:
   - **Project URL**: This is your `SUPABASE_URL`
   - **anon public**: This is your `SUPABASE_ANON_KEY`

## Step 3: Update the Configuration
Edit the `supabase.js` file and replace:
```javascript
const SUPABASE_URL = 'https://your-project.supabase.co'; // Replace with your Supabase URL
const SUPABASE_ANON_KEY = 'your-anon-key-here'; // Replace with your Supabase anon key
```

With your actual values:
```javascript
const SUPABASE_URL = 'https://your-actual-project-id.supabase.co';
const SUPABASE_ANON_KEY = 'your-actual-anon-key';
```

## Step 4: Create Database Tables
1. In your Supabase dashboard, go to the SQL Editor
2. Copy and paste the contents of `database_schema.sql`
3. Click "Run" to execute the SQL and create the tables

## Step 5: Test the Connection
1. Open any HTML file in your browser (e.g., `index.html`)
2. Open the browser console (F12)
3. You should see logs indicating Supabase initialization
4. Test the connection by running: `testSupabaseConnection()` in the console
   - Should return `true` if successful

## Step 6: Migrate Existing Data (Optional)
If you have existing data in localStorage that you want to migrate:
1. The system will automatically attempt to migrate data from localStorage to Supabase on first load
2. Check the console for migration logs
3. After successful migration, you can clear your localStorage if desired

## Table Structure
The system creates three tables:
1. `inventory_items` - Stores equipment categories and quantities
2. `serial_numbers` - Tracks individual serial numbers with status
3. `repair_records` - Manages repair tickets and their status

## Security Note
For production use, consider setting up Row Level Security (RLS) policies on your tables to restrict access appropriately. The current setup uses the anon key which is suitable for public-facing applications where row-level security is configured.

## Troubleshooting
- If you see CORS errors, make sure your Supabase project URL is correct
- If you see authentication errors, double-check your anon key
- Check the browser console for detailed error messages
- Ensure you're serving the files via HTTP(S) - some features may not work with file:// protocol