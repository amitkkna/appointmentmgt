// postbuild.js
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Create build directory if it doesn't exist
const buildDir = path.join(__dirname, 'public', 'build');
if (!fs.existsSync(buildDir)) {
  fs.mkdirSync(buildDir, { recursive: true });
}

// Copy static site files to build directory
const staticSiteDir = path.join(__dirname, 'static-site');
if (fs.existsSync(staticSiteDir)) {
  copyFolderRecursiveSync(staticSiteDir, path.join(__dirname, 'public', 'build'));
}

console.log('Postbuild script completed successfully!');

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
