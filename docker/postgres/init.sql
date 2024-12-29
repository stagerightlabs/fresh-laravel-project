CREATE USER developer;
ALTER USER developer WITH PASSWORD 'secret';

CREATE DATABASE app_dev OWNER developer;
GRANT ALL PRIVILEGES ON DATABASE app_dev TO developer;

CREATE DATABASE app_test OWNER developer;
GRANT ALL PRIVILEGES ON DATABASE app_test TO developer;
