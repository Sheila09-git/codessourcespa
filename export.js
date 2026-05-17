function downloadCarte() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); 
    const img = document.getElementById('menuImage');
    doc.addImage(img, 'JPEG', 0, 0, 297, 210); 
    
    doc.save("Menu-Excellence-BBQ.pdf");
}