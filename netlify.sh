#!/bin/bash

# Print Node.js version
echo "Node.js version:"
node --version

# Print npm version
echo "npm version:"
npm --version

# Print Python version
echo "Python version:"
python --version || python3 --version

# Run the build
npm run build

# Copy static files to the build directory
mkdir -p public/build
cp -r static-site/* public/build/

echo "Build completed successfully!"
