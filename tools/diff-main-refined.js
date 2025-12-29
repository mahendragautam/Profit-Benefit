const fs = require('fs');
const path = require('path');

function extractSelectors(filePath) {
  const txt = fs.readFileSync(filePath, 'utf8');
  const regex = /(^|[\n\r\s,>+~])\.([A-Za-z0-9_-]+)/g;
  const set = new Set();
  let m;
  while ((m = regex.exec(txt)) !== null) {
    set.add(m[2]);
  }
  return set;
}

const mainPath = path.resolve(__dirname, '..', 'profit-benefit', 'assets', 'css', 'main.css');
const refinedPath = path.resolve(__dirname, '..', 'profit-benefit', 'assets', 'css', 'main.refined.css');

if (!fs.existsSync(mainPath)) { console.error('main.css not found:', mainPath); process.exit(2); }
if (!fs.existsSync(refinedPath)) { console.error('main.refined.css not found:', refinedPath); process.exit(2); }

const main = extractSelectors(mainPath);
const refined = extractSelectors(refinedPath);

const onlyMain = [...main].filter(s => !refined.has(s)).sort();
const onlyRefined = [...refined].filter(s => !main.has(s)).sort();

console.log('main selectors:', main.size);
console.log('refined selectors:', refined.size);
console.log('only-in-main count:', onlyMain.length);
console.log('only-in-refined count:', onlyRefined.length);

fs.writeFileSync(path.resolve(__dirname, '..', 'only-in-main.txt'), onlyMain.join('\n'));
fs.writeFileSync(path.resolve(__dirname, '..', 'only-in-refined.txt'), onlyRefined.join('\n'));

console.log('\nOnly-in-main examples (first 40):\n', onlyMain.slice(0,40).join('\n'));
console.log('\nOnly-in-refined examples (first 40):\n', onlyRefined.slice(0,40).join('\n'));
