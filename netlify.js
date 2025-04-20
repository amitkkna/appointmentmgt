// netlify.js - Simple fallback script for Netlify deployment
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

console.log('Running simple Netlify deployment script...');

// Create build directory
const buildDir = path.join(__dirname, 'public', 'build');
if (!fs.existsSync(buildDir)) {
  fs.mkdirSync(buildDir, { recursive: true });
}

// Create a simple index.html file
const indexHtml = `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Doctor Appointment System</h1>
        <p>Welcome to our Doctor Appointment System. This is a static version of the site.</p>
        
        <div class="card">
            <h2>Features</h2>
            <ul>
                <li>Book appointments with doctors</li>
                <li>Manage patient records</li>
                <li>View doctor schedules</li>
                <li>Receive appointment reminders</li>
            </ul>
        </div>
        
        <div class="card">
            <h2>Contact Us</h2>
            <p>If you have any questions or need assistance, please contact us:</p>
            <p>Email: support@doctorapp.com</p>
            <p>Phone: (123) 456-7890</p>
        </div>
    </div>
</body>
</html>
`;

fs.writeFileSync(path.join(buildDir, 'index.html'), indexHtml);

// Copy static site files if they exist
const staticSiteDir = path.join(__dirname, 'static-site');
if (fs.existsSync(staticSiteDir)) {
  copyFolderRecursiveSync(staticSiteDir, buildDir);
}

console.log('Deployment script completed successfully!');

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
