#!/bin/bash

# Script to set up Git hooks for ezPump Laravel App

echo "🔧 Setting up Git hooks for ezPump Laravel App..."

# Make hooks executable
chmod +x .git/hooks/post-merge
chmod +x .git/hooks/post-checkout

# Make deployment scripts executable too
chmod +x deploy.sh
chmod +x quick-deploy.sh
chmod +x commands_after_pull.sh

echo "✅ Git hooks setup completed!"
echo ""
echo "📋 Hooks configured:"
echo "   🔗 post-merge: Runs after git pull/merge"
echo "   🔀 post-checkout: Runs after branch switching"
echo ""
echo "📋 Deployment scripts available:"
echo "   🚀 ./deploy.sh - Full deployment with error checking"
echo "   ⚡ ./quick-deploy.sh - Simple git pull + commands"
echo "   🔄 ./commands_after_pull.sh - Laravel commands only"
echo ""
echo "🎉 Your Laravel app will now automatically update after git operations!"
