#!/bin/bash

# Simple git pull + Laravel commands
echo "🔄 Pulling latest changes and updating app..."

# Pull from git
git pull && \

# Run your existing commands
./commands_after_pull.sh

echo "✅ Update complete!"
