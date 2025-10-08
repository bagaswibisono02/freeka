
function previewFiles() {
const previewContainer = document.getElementById('filePreview');
const fileInput = document.getElementById('fileInput');
const files = fileInput.files;

// Kosongkan kontainer sebelumnya
previewContainer.innerHTML = '';

if (files.length > 0) {
document.getElementById('tombol').classList.remove('d-none');
} else {
document.getElementById('tombol').classList.add('d-none');
}

Array.from(files).forEach(file => {
const fileReader = new FileReader();

fileReader.onload = function (e) {
const fileURL = e.target.result;
let previewElement;

if (file.type.startsWith('image/')) {
previewElement = document.createElement('img');
previewElement.src = fileURL;
previewElement.className = 'img-thumbnail m-2';
previewElement.style.maxHeight = '150px';
} else if (file.type.startsWith('video/')) {
previewElement = document.createElement('video');
previewElement.src = fileURL;
previewElement.controls = true;
previewElement.className = 'm-2';
previewElement.style.maxHeight = '200px';
}

if (previewElement) {
previewContainer.appendChild(previewElement);
}
};

fileReader.readAsDataURL(file);
});
}

// Scroll kanan-kiri jika banyak preview
function scrollLeftBtn() {
document.getElementById('filePreview').scrollBy({
left: -200,
behavior: 'smooth'
});
}

function scrollRightBtn() {
document.getElementById('filePreview').scrollBy({
left: 200,
behavior: 'smooth'
});
}





function tambahLink() {
    const table = document.getElementById('myTable').getElementsByTagName('tbody')[0];
    
    // buat baris baru
    const newRow = table.insertRow();
    
    // sel link
    const cellLink = newRow.insertCell(0);
    const inputLink = document.createElement('input');
    inputLink.type = 'url';
    inputLink.name = 'link[]';
    inputLink.className = 'form-control';
    inputLink.placeholder = 'Link Supplier';
    cellLink.appendChild(inputLink);
    
    // sel harga asli
    const cellHarga = newRow.insertCell(1);
    const inputHarga = document.createElement('input');
    inputHarga.type = 'number';
    inputHarga.name = 'hargaAsli[]';
    inputHarga.className = 'form-control';
    inputHarga.placeholder = 'Harga';
    cellHarga.appendChild(inputHarga);
}

