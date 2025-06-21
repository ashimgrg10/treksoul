const map = L.map('map').setView([28.6031, 77.3105], 13);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Sample trail path (mock data)
const trailPath = [
  [28.6012, 77.3023],
  [28.6031, 77.3105],
  [28.6082, 77.3164]
];

// Draw the trail
L.polyline(trailPath, { color: 'blue' }).addTo(map);

// Add markers
L.marker(trailPath[0]).addTo(map).bindPopup('Trail Start');
L.marker(trailPath[trailPath.length - 1]).addTo(map).bindPopup('Trail End');
