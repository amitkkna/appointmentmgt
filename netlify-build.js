// netlify-build.js
import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Print environment information
console.log('=== Environment Information ===');
try {
  console.log('Node.js version:');
  execSync('node --version', { stdio: 'inherit' });

  console.log('npm version:');
  execSync('npm --version', { stdio: 'inherit' });

  console.log('Python version:');
  try {
    execSync('python --version', { stdio: 'inherit' });
  } catch (error) {
    try {
      execSync('python3 --version', { stdio: 'inherit' });
    } catch (error) {
      console.log('Python not found');
    }
  }
} catch (error) {
  console.error('Error printing environment information:', error);
}

// Install dependencies
console.log('\n=== Installing Dependencies ===');
try {
  execSync('npm install', { stdio: 'inherit' });
} catch (error) {
  console.error('Dependency installation failed:', error);
  process.exit(1);
}

// Run the build
console.log('\n=== Running Build ===');
try {
  execSync('npm run build', { stdio: 'inherit' });
} catch (error) {
  console.error('Build failed:', error);
  process.exit(1);
}

// Copy static files to build directory
console.log('\n=== Copying Static Files ===');
const buildDir = path.join(__dirname, 'public', 'build');
const staticSiteDir = path.join(__dirname, 'static-site');

if (!fs.existsSync(buildDir)) {
  fs.mkdirSync(buildDir, { recursive: true });
}

if (fs.existsSync(staticSiteDir)) {
  copyFolderRecursiveSync(staticSiteDir, buildDir);
}

console.log('Build completed successfully!');

// Function to copy a folder recursively
function copyFolderRecursiveSync(source, target) {
  // Check if source exists
  if (!fs.existsSync(source)) {
    return;
  }

  // Create target folder if it doesn't exist
  if (!fs.existsSync(target)) {
    fs.mkdirSync(target, { recursive: true });
  }

  // Copy all files and subfolders
  const files = fs.readdirSync(source);
  files.forEach(file => {
    const sourcePath = path.join(source, file);
    const targetPath = path.join(target, file);

    // If it's a directory, recursively copy it
    if (fs.lstatSync(sourcePath).isDirectory()) {
      copyFolderRecursiveSync(sourcePath, targetPath);
    } else {
      // Otherwise, copy the file
      fs.copyFileSync(sourcePath, targetPath);
    }
  });
}
