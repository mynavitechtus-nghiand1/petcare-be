-- Test Database Initialization Script
-- This script sets up the test database with proper permissions

-- Create test database if it doesn't exist
-- (This is handled by POSTGRES_DB environment variable)

-- Grant all privileges to laravel user for test database
GRANT ALL PRIVILEGES ON DATABASE laravel_test TO laravel;

-- Connect to test database and set up schema
\c laravel_test;

-- Grant schema privileges
GRANT ALL ON SCHEMA public TO laravel;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO laravel;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO laravel;

-- Set default privileges for future tables
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO laravel;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO laravel;

-- Enable UUID extension for test database
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Create a test-specific function to reset sequences
CREATE OR REPLACE FUNCTION reset_sequences()
RETURNS void AS $$
DECLARE
    r RECORD;
BEGIN
    FOR r IN (SELECT schemaname, tablename, attname, seq_name
              FROM pg_class
              JOIN pg_namespace ON pg_namespace.oid = pg_class.relnamespace
              JOIN pg_attribute ON pg_attribute.attrelid = pg_class.oid
              JOIN pg_attrdef ON pg_attrdef.adrelid = pg_class.oid
              WHERE pg_class.relkind = 'S'
              AND pg_namespace.nspname = 'public')
    LOOP
        EXECUTE 'ALTER SEQUENCE ' || r.seq_name || ' RESTART WITH 1';
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- Grant execute permission on reset function
GRANT EXECUTE ON FUNCTION reset_sequences() TO laravel;
