#!/bin/bash

# Run the build
npm run build

# Copy static files to the build directory
mkdir -p public/build
cp -r static-site/* public/build/

echo "Build completed successfully!"
