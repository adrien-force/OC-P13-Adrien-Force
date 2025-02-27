#!/bin/bash

# Add local domains to /etc/hosts if not present
if ! grep -q "greengoodies.local" /etc/hosts; then
    echo "127.0.0.1 greengoodies.local api.greengoodies.local" | sudo tee -a /etc/hosts > /dev/null
fi

# Build and start containers
docker compose up -d --build

echo "Your Symfony project is ready at http://greengoodies.local:8000"
