-- Database Schema for IT Equipment Management System
-- Run this in your Supabase SQL editor to create the required tables

-- Inventory Items Table
CREATE TABLE IF NOT EXISTS inventory_items (
    id SERIAL PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    total_qty INTEGER DEFAULT 0,
    unit VARCHAR(50),
    responsible VARCHAR(255),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Serial Numbers Table
CREATE TABLE IF NOT EXISTS serial_numbers (
    id SERIAL PRIMARY KEY,
    item_id INTEGER REFERENCES inventory_items(id) ON DELETE CASCADE,
    serial_number VARCHAR(255) NOT NULL,
    brand VARCHAR(255),
    status VARCHAR(50) DEFAULT 'Available', -- Available, Withdrawn, Repair
    job_number VARCHAR(255),
    withdrawer_name VARCHAR(255),
    dept_used VARCHAR(255),
    building VARCHAR(255),
    floor VARCHAR(50),
    receive_date DATE,
    withdraw_date TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Repair Records Table
CREATE TABLE IF NOT EXISTS repair_records (
    id SERIAL PRIMARY KEY,
    device_name VARCHAR(255) NOT NULL,
    symptom TEXT,
    status VARCHAR(50) DEFAULT 'Pending', -- Pending, Repairing, Fixed, Returned
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_serial_numbers_item_id ON serial_numbers(item_id);
CREATE INDEX IF NOT EXISTS idx_serial_numbers_status ON serial_numbers(status);
CREATE INDEX IF NOT EXISTS idx_repair_records_status ON repair_records(status);

-- Disable Row Level Security (RLS) for development
-- In production, you should enable RLS and create appropriate policies instead
ALTER TABLE inventory_items DISABLE ROW LEVEL SECURITY;
ALTER TABLE serial_numbers DISABLE ROW LEVEL SECURITY;
ALTER TABLE repair_records DISABLE ROW LEVEL SECURITY;

-- Enable real-time subscriptions (optional, for live updates)
-- alter publication supabase_realtime add table inventory_items;
-- alter publication supabase_realtime add table serial_numbers;
-- alter publication supabase_realtime add table repair_records;