-- Switch to laravel database and grant all permissions
\c laravel;

-- Grant all privileges on the public schema
GRANT ALL ON SCHEMA public TO laravel;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO laravel;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO laravel;

-- Set default privileges for any new tables/sequences
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO laravel;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO laravel;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON FUNCTIONS TO laravel;
