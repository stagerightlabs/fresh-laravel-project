FROM postgres:17

LABEL authors="Ryan Durham <ryan@stagerightlabs.com>"

# Set up the www-data user as UID 1000
ARG NAME=www-data
ENV NAME ${NAME}
RUN userdel www-data && \
    useradd -m -u 1000 -G postgres -s /bin/bash ${NAME} && \
    chown -R ${NAME}:${NAME} /home/${NAME}

# Add the postgres user to the www-data group
RUN usermod -a -G www-data postgres

# Install vim
RUN apt update && apt install vim -y

# Copy over the .psqlrc configuration file
COPY --chown=postgres:postgres .psqlrc /var/lib/postgresql/

# Copy our init.sql into the container
# This will only be run if the persistence volume is empty
COPY --chown=postgres:postgres init.sql /docker-entrypoint-initdb.d/

WORKDIR /var/lib/postgresql
ENV PATH "$PATH:/var/lib/postgresql"
